<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Commands;

use Amp\LibeventReactor;
use Amp\Reactor;
use Amp\Thread\Dispatcher;
use Dariuszp\CliProgressBar;
use Ormin\OBSLexicalParser\Builds\BuildTargetFactory;
use Ormin\OBSLexicalParser\Commands\Dispatch\ArchiveBuildJob;
use Ormin\OBSLexicalParser\Commands\Dispatch\CompileScriptJob;
use Ormin\OBSLexicalParser\Commands\Dispatch\LoadAutoloader;
use Ormin\OBSLexicalParser\Commands\Dispatch\PrepareWorkspaceJob;
use Ormin\OBSLexicalParser\Commands\Dispatch\TranspileChunkJob;
use Ormin\OBSLexicalParser\Commands\Dispatch\TranspileScriptJob;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Amp\Promise;

class BuildScriptCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('skyblivion:parser:buildScript')
            ->setDescription('Create artifact from OBScript source')
            ->addArgument('target', InputArgument::REQUIRED, "The build target")
            ->addArgument('scriptName', InputArgument::REQUIRED, "Script name");

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(60);

        try {

            $target = $input->getArgument('target');
            $scriptName = $input->getArgument('scriptName');

            $buildTarget = BuildTargetFactory::get($target);

            if (
                (count(array_slice(scandir($buildTarget->getWorkspacePath()), 2)) > 0) ||
                (count(array_slice(scandir($buildTarget->getTranspiledPath()), 2))) > 0 ||
                (count(array_slice(scandir($buildTarget->getArtifactsPath()), 2))) > 0
            ) {
                $output->writeln("Target " . $target . " current build dir not clean, archive it manually.");
                return;
            }

            try {
                $task = new TranspileScriptJob(unserialize(file_get_contents('app/graph')),$buildTarget->getTargetName(), $scriptName);
                $task->run();
            } catch (ConversionException $e) {

                $output->writeln("Exception occured.");
                $output->writeln(get_class($e));
                $output->writeln($e->getMessage());

            }

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

            $output->writeln("Build completed.");

            $compileLog = file_get_contents($buildTarget->getCompileLogPath());

            var_dump($compileLog);

        } catch (\LogicException $e) {
            $output->writeln("Unknown target " . $target . ", exiting.");
            return;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }

    }


}