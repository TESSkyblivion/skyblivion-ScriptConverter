<?php

namespace Ormin\OBSLexicalParser\Commands;

use Dariuszp\CliProgressBar;
use Ormin\OBSLexicalParser\Builds\Build;
use Ormin\OBSLexicalParser\Builds\BuildTarget;
use Ormin\OBSLexicalParser\Builds\BuildTargetFactory;
use Ormin\OBSLexicalParser\Commands\Dispatch\ArchiveBuildJob;
use Ormin\OBSLexicalParser\Commands\Dispatch\CompileScriptJob;
use Ormin\OBSLexicalParser\Commands\Dispatch\PrepareWorkspaceJob;
use Ormin\OBSLexicalParser\Commands\Dispatch\TranspileChunkJob;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Amp\Promise;

class BuildTargetCommand extends Command
{

    private $threadsNumber;

    protected function configure()
    {
        $this
            ->setName('skyblivion:parser:build')
            ->setDescription('Create artifact[s] from OBScript source')
            ->addArgument('targets', InputArgument::OPTIONAL, "The build targets", BuildTarget::DEFAULT_TARGETS)
            ->addArgument('threadsNumber', InputArgument::OPTIONAL, "Threads number", 4)
            ->addArgument('buildPath', InputArgument::OPTIONAL, "Build folder", Build::DEFAULT_BUILD_PATH);

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(10800); // 3 hours is the maximum for this command. Need more? You really screwed something, full suite for all Oblivion vanilla data takes 20 minutes. :)

        try {

            $targets = $input->getArgument('targets');
            $this->threadsNumber = $input->getArgument('threadsNumber');

            $buildPath = $input->getArgument('buildPath');
            $build = new Build($buildPath);
            $buildTargets = BuildTargetFactory::getCollection($targets, $build);

            if (!$buildTargets->canBuild()) {
                $output->writeln("Targets current build dir not clean, archive them manually or run ./clean.sh.");
                return;
            }

            $output->writeln("Starting transpiling reactor using " . $this->threadsNumber . " threads...");

            $reactor = \Amp\reactor();
            $reactor->run(function () use ($build, $buildPath, $buildTargets, $output, $reactor) {

                $errorLog = fopen($build->getErrorLogPath(), "w+");

                $buildPlan = $buildTargets->getBuildPlan($this->threadsNumber);
                $totalSourceFiles = $buildTargets->getTotalSourceFiles();

                $progressBar = new CliProgressBar($totalSourceFiles);
                $progressBar->display();

                $promises = [];
                foreach ($buildPlan as $threadBuildPlan) {

                    /**
                     * We had some problems with sharing objects inside the jobs, so thats why we pass the path.
                     * Maybe later we can just inject Build and it will be nice and clean :)
                     */
                    $task = new TranspileChunkJob($buildPath, $threadBuildPlan);

                    $deferred = new \Amp\Deferred;

                    \Amp\once(function() use($deferred, $task) {
                        $task->runTask($deferred);
                    }, 0);

                    $promise = $deferred->promise();

                    $promise->when(function (\Exception $e = null, $return = null) use ($output, $errorLog) {

                        if ($e) {
                            $output->writeln('Exception ' . get_class($e) . ' occurred in one of the threads while transpiling, progress bar will not be accurate..');
                            fwrite($errorLog, get_class($e) . PHP_EOL . $e->getMessage() . PHP_EOL);
                        }

                    });


                    $promise->watch(function ($data) use ($progressBar, $errorLog) {

                        $count = 0;
                        foreach($data['scripts'] as $build => $scripts) {
                            $count += count($scripts);
                        }

                        $progressBar->progress($count);

                        if (isset($data['exception'])) {
                            $flattenedScripts = "";
                            foreach($data['scripts'] as $build => $scripts) {
                                $flattenedScripts .= implode(', ', $scripts);
                            }

                            fwrite($errorLog, $flattenedScripts.PHP_EOL.$data['exception']);
                        }

                    });

                    $promises[] = $promise;
                }


                /**
                 * @var Promise $transpilingPromise
                 */
                $transpilingPromise = \Amp\any($promises);

                $transpilingPromise->when(function () use ($reactor, $progressBar, $errorLog) {
                    $progressBar->end();
                    fclose($errorLog);
                    $reactor->stop();
                });

            });


            $output->writeln("Preparing build workspace...");

            /*
             *
             * @TODO - Create a factory that will provide a PrepareWorkspaceJob based on running system, so we can provide a
             * native implementation for Windows
             */
            $prepareCommand = new PrepareWorkspaceJob($buildTarget->getTargetName());
            $prepareCommand->run();

            $output->writeln("Workspace prepared...");

            $task = new CompileScriptJob($buildTarget->getTargetName());
            $task->run();

            $output->writeln("Build completed, archiving ...");


            /*
             *
             * @TODO - Create a factory that will provide a PrepareWorkspaceJob based on running system, so we can provide a
             * native implementation for Windows
             */
            $prepareCommand = new ArchiveBuildJob($buildTarget->getTargetName());
            $prepareCommand->run();



        } catch (\LogicException $e) {
            $output->writeln($e->getMessage());
            return;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }

    }


}
