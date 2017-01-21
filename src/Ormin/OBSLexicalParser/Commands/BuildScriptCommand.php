<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Commands;

use Ormin\OBSLexicalParser\Builds\Build;
use Ormin\OBSLexicalParser\Builds\BuildTarget;
use Ormin\OBSLexicalParser\Builds\BuildTargetFactory;
use Ormin\OBSLexicalParser\Commands\Dispatch\CompileScriptJob;
use Ormin\OBSLexicalParser\Commands\Dispatch\PrepareWorkspaceJob;
use Ormin\OBSLexicalParser\Commands\Dispatch\TranspileScriptJob;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildScriptCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('skyblivion:parser:buildScript')
            ->setDescription('Create artifact from OBScript source')
            ->addArgument('scriptName', InputArgument::REQUIRED, "Script name")
            ->addArgument('targets', InputArgument::OPTIONAL, "The build targets", BuildTarget::DEFAULT_TARGETS)
            ->addArgument('buildPath', InputArgument::OPTIONAL, "Build folder", Build::DEFAULT_BUILD_PATH);

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(60);

        try {

            $targets = $input->getArgument('targets');
            $scriptName = $input->getArgument('scriptName');

            $buildPath = $input->getArgument('buildPath');
            $build = new Build($buildPath);
            $buildTargets = BuildTargetFactory::getCollection($targets, $build);


            if (!$buildTargets->canBuild()) {
                $output->writeln("Targets current build dir not clean, archive them manually or run ./clean.sh.");
                return;
            }

            try {
                $task = new TranspileScriptJob($buildTargets, $scriptName);
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
            $prepareCommand = new PrepareWorkspaceJob($buildTargets);
            $prepareCommand->run();

            $output->writeln("Workspace prepared...");

            $task = new CompileScriptJob($buildTargets, $build->getCompileLogPath());
            $task->run();

            $output->writeln("Build completed.");

            $compileLog = file_get_contents($build->getCompileLogPath());

            var_dump($compileLog);

        } catch (\LogicException $e) {
            $output->writeln("LogicException ".$e->getMessage());
            return;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }

    }


}