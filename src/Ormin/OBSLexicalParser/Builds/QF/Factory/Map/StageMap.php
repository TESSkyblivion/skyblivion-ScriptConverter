<?php

namespace Ormin\OBSLexicalParser\Builds\QF\Factory\Map;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

/**
 * Class StageMap
 * Represents the stage map. Will map original target indexes to mapped target indexes and duplicate them as needed
 * Target duplication algorithm assumes that stages go only forward, i.e., that the quest progression is stage by
 * stage.
 * @package Ormin\OBSLexicalParser\Builds\QF\Factory\Map
 */
class StageMap
{

    private $stageMap = [];

    private $mappedTargetsIndex = [];

    public function __construct($stageMap)
    {
        /**
         * Sort them by stage ids
         */
        ksort($stageMap);

        /**
         * Do some validation
         */
        $length = NULL;
        foreach($stageMap as $stageId => $rows) {
            if($length == NULL) {
                $length = count($rows);
            } else if($length != count($rows)) {
                throw new ConversionException("Invalid stage map metadata - stageID ". $stageId." expected ". $length." rows but had ".count($rows));
            }
        }

        $nextFreeIndex = $length; //Next free index equals length, as per all 0-N arrays
        $resultStageMap = $stageMap;
        $targetsStateMap = [];
        $mappedTargetsIndex = [];
        /**
         * Traverse through the map, and duplicate targets as needed
         */
        foreach($stageMap as $stageId => $rows) {

            $targetIndex = 0;
            foreach($rows as $row) {

                if($row) {

                    if(isset($targetsStateMap[$targetIndex])) {

                        if($targetsStateMap[$targetIndex] === false) {
                            //We're changing the state from not used to used, in which case we need to do the duplication
                            if(!isset($mappedTargetsIndex[$targetIndex])) {
                                $mappedTargetsIndex[$targetIndex] = [];
                            }

                            $mappedTargetsIndex[$targetIndex][] = $nextFreeIndex;

                            $fillInValue = 0;
                            foreach($resultStageMap as $resultStageId => $resultRows) {

                                //This stage id marks the start of the block, so we switch the fillin value
                                if($resultStageId == $stageId) {
                                    $fillInValue = 1;
                                }

                                //Check if this stage id result is 0, if so - switch out the block
                                if(!$stageMap[$resultStageId][$targetIndex]) {
                                    $fillInValue = 0;
                                }

                                $resultStageMap[$resultStageId][] = $fillInValue;

                                /**
                                 * If we already started filling out, remove original target as it wont be used anymore
                                 */
                                if($resultStageId >= $stageId) {
                                    $resultStageMap[$resultStageId][$targetIndex] = 0;
                                }

                            }

                            ++$nextFreeIndex; //Increase the index

                        }

                    }

                    //We mark that the target is now used, perhaps was already used in which case this operation
                    //is doing nothing
                    $targetsStateMap[$targetIndex] = true;

                } else {

                    if(isset($targetsStateMap[$targetIndex])) {
                        //We mark that the target state changed to false ( perhaps it was already false, in which case
                        //this operation is doing nothing
                        $targetsStateMap[$targetIndex] = false;
                    } //If the target was not used before, then we just continue, as it's still not used.
                }

                ++$targetIndex;
            }


        }

        $this->stageMap = $resultStageMap;
        $this->mappedTargetsIndex = $mappedTargetsIndex;
    }

    public function getStageIds()
    {
        return array_keys($this->stageMap);
    }

    public function getStageTargetsMap($stageId)
    {
        return $this->stageMap[$stageId];
    }

    public function getMappedTargetsIndex() {
        return $this->mappedTargetsIndex;
    }


}