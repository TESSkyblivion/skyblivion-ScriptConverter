<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Commands;

use Ormin\OBSLexicalParser\Builds\Build;
use Ormin\OBSLexicalParser\Builds\BuildTarget;
use Ormin\OBSLexicalParser\Builds\BuildTargetFactory;
use Ormin\OBSLexicalParser\Builds\QF\Factory\Map\StageMap;
use Ormin\OBSLexicalParser\Commands\Dispatch\CompileScriptJob;
use Ormin\OBSLexicalParser\Commands\Dispatch\PrepareWorkspaceJob;
use Ormin\OBSLexicalParser\Commands\Dispatch\TranspileScriptJob;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestStageMap extends Command
{

    protected function configure()
    {
        $this
            ->setName('skyblivion:testStageMap');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(60);
        $originalStageMap = $this->buildStageMap(BuildTargetFactory::get(BuildTarget::BUILD_TARGET_QF,new Build(Build::DEFAULT_BUILD_PATH)), "QF_FGC01Rats_01035713");

        $stageMap = new StageMap($originalStageMap);

        foreach($stageMap->getStageIds() as $stageId)
        {
            $output->writeln($stageId.' - '.implode(' ', $originalStageMap[$stageId]));
            $output->write($stageId." - ");
            $map = $stageMap->getStageTargetsMap($stageId);

            foreach($map as $val) {
                $output->write($val." ");
            }
            $output->write("",true);
        }

        $output->writeln("Mapping index print");

        foreach($stageMap->getMappedTargetsIndex() as $originalTargetIndex => $mappedTargetIndexes) {

            $output->writeln($originalTargetIndex.' - '.implode(" ",$mappedTargetIndexes));
        }
    }

    private function buildStageMap(BuildTarget $target, $resultingFragmentName)
    {
        $sourcePath = $target->getSourceFromPath($resultingFragmentName);
        $scriptName = pathinfo($sourcePath, PATHINFO_FILENAME);
        $stageMapFile = pathinfo($sourcePath, PATHINFO_DIRNAME). "/" .$scriptName.".map";
        $stageMapContent = file($stageMapFile);
        $stageMap = [];
        foreach($stageMapContent as $stageMapLine)
        {
            $e = explode("-",$stageMapLine);
            $stageId = trim($e[0]);
            /**
             * Clear the rows
             */
            $stageRowsRaw = explode(" ", $e[1]);
            $stageRows = [];

            foreach($stageRowsRaw as $v) {
                if(trim($v) != "") {
                    $stageRows[] = trim($v);
                }
            }

            $stageMap[$stageId] = $stageRows;
        }

        return $stageMap;
    }
}