<?php
/**
 * Created by PhpStorm.
 * Date: 2/4/17
 * Time: 8:59 PM
 */

namespace Ormin\OBSLexicalParser\Builds\QF\Factory;


use Ormin\OBSLexicalParser\Builds\QF\Factory\Map\StageMap;
use Ormin\OBSLexicalParser\Builds\QF\Factory\Service\MappedTargetsLogService;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5BlockList;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\TES5Script;
use Ormin\OBSLexicalParser\TES5\AST\TES5Target;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\Builds\BuildTarget;
use Ormin\OBSLexicalParser\Builds\QF\Factory\Map\QuestStageScript;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5Property;
use Ormin\OBSLexicalParser\TES5\AST\TES5ScriptHeader;

class QFFragmentFactory
{
    /**
     * @var MappedTargetsLogService
     */
    private $mappedTargetsLogService;

    /**
     * @var ObjectiveHandlingFactory
     */
    private $objectiveHandlingFactory;

    public function __construct(MappedTargetsLogService $mappedTargetsLogService, ObjectiveHandlingFactory $objectiveHandlingFactory)
    {
        $this->mappedTargetsLogService = $mappedTargetsLogService;
        $this->objectiveHandlingFactory = $objectiveHandlingFactory;
    }
    
    /**
     * Joins N QF subfragments into one QF fragment that can be properly binded into Skyrim VM
     * @param BuildTarget $target
     * @param string $resultingFragmentName
     * @param QuestStageScript[] $subfragmentsTrees
     * @return TES5Target
     * @throws ConversionException
     */
    public function joinQFFragments(BuildTarget $target, $resultingFragmentName, array $subfragmentsTrees)
    {
        $stageMap = $this->buildStageMap($target, $resultingFragmentName);

        /**
         * We need script fragment for objective handling for each stage, so when parsing the script fragments,
         * we'll be marking them there, and intersecting this with stage.
         * This will give us an array of stages which don't have script fragment, but will need it anyways
         * for objective handling.
         */
        $implementedStages = [];

        $outputPath = $target->getTranspileToPath($resultingFragmentName);

        $resultingScriptHeader = new TES5ScriptHeader(
            $resultingFragmentName,
            $resultingFragmentName,
            TES5BasicType::T_QUEST(), '', true);

        $resultingBlockList = new TES5BlockList();

        $resultingGlobalScope = new TES5GlobalScope($resultingScriptHeader);

        /**
         * Add ReferenceAlias'es
         * At some point, we might port the conversion so it doesn't use the directly injected property,
         * but instead has a map to aliases and we'll map accordingly and have references point to aliases instead
         */
        $sourcePath = $target->getSourceFromPath($resultingFragmentName);
        $scriptName = pathinfo($sourcePath, PATHINFO_FILENAME);
        $aliasesFile = pathinfo($sourcePath, PATHINFO_DIRNAME). "/" .$scriptName.".aliases";
        $aliasesContent = file($aliasesFile);
        $aliasesDeclared = [];


        foreach($aliasesContent as $alias) {
            $alias = trim($alias);
            if(empty($alias)) {
                continue;
            }

            if(isset($aliasesDeclared[$alias]))
            {
                continue;
            }

            $resultingGlobalScope->add(
                new TES5Property($alias, TES5BasicType::T_REFERENCEALIAS(), $alias)
            );

            $aliasesDeclared[$alias] = true;
        }

        $propertiesNamesDeclared = [];

        foreach($subfragmentsTrees as $subfragment)
        {
            $subfragmentsTree = $subfragment->getScript();
            $subfragmentScript = $subfragmentsTree->getScript();
            $subfragmentGlobalScope = $subfragmentScript->getGlobalScope();

            foreach($subfragmentGlobalScope->getPropertiesList() as $subfragmentProperty) {
                /**
                 * Move over the properties to the new global scope
                 */
                if(isset($propertiesNamesDeclared[$subfragmentProperty->getPropertyName()])) {
                    $propertyName = $this->generatePropertyName($subfragmentScript->getScriptHeader(),$subfragmentProperty);
                    $subfragmentProperty->renameTo($propertyName);
                } else {
                    $propertyName = $subfragmentProperty->getPropertyName();
                }

                $propertiesNamesDeclared[$propertyName] = true;

                $resultingGlobalScope->add($subfragmentProperty);

            }

            $subfragmentBlocks = $subfragmentScript->getBlockList()->getBlocks();
            if(count($subfragmentBlocks) != 1) {
                throw new ConversionException("Wrong QF fragment, actual function count: ".count($subfragmentBlocks)."..");
            }

            $subfragmentBlock = $subfragmentBlocks[0];

            if($subfragmentBlock->getFunctionScope()->getBlockName() != "Fragment_0") {
                throw new ConversionException("Wrong QF fragment funcname, actual function name: ".
                    $subfragmentBlock->getFunctionScope()->getBlockName().
                    ".."
                );
            }

            $newFragmentFunctionName = "Fragment_" . $subfragment->getStage();
            if($subfragment->getLogIndex() != 0) {
                $newFragmentFunctionName .= "_" . $subfragment->getLogIndex();
            }
            $subfragmentBlock->getFunctionScope()->renameTo($newFragmentFunctionName);

            $objectiveCodeChunks = $this->objectiveHandlingFactory->generateObjectiveHandling($subfragmentBlock, $resultingGlobalScope, $stageMap->getStageTargetsMap($subfragment->getStage()));

            foreach ($objectiveCodeChunks as $newCodeChunk) {
                $subfragmentBlock->addChunk($newCodeChunk);
            }

            $resultingBlockList->add($subfragmentBlock);
            $implementedStages[$subfragment->getStage()] = true;
        }

        /**
         * Diff to find stages which we still need to mark
         */
        $nonDoneStages = array_diff($stageMap->getStageIds(), array_keys($implementedStages));

        foreach($nonDoneStages as $nonDoneStage)
        {
            $fragment = $this->objectiveHandlingFactory->createEnclosedFragment($resultingGlobalScope, $nonDoneStage, $stageMap->getStageTargetsMap($nonDoneStage));
            $resultingBlockList->add($fragment);
        }


        $this->mappedTargetsLogService->writeScriptName($resultingFragmentName);

        foreach($stageMap->getMappedTargetsIndex() as $originalTargetIndex => $mappedTargetIndexes) {
            $this->mappedTargetsLogService->add($originalTargetIndex, $mappedTargetIndexes);
        }

        $resultingTree = new TES5Script($resultingGlobalScope, $resultingBlockList);

        return new TES5Target($resultingTree, $outputPath);

    }



    private function generatePropertyName(TES5ScriptHeader $header, TES5Property $property)
    {
        return "col_".substr($property->getPropertyName(),0,-2) . "_".substr(md5($header->getScriptName()),0,4);
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

        return new StageMap($stageMap);
    }
}