<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 21.12.15
 * Time: 00:41
 */

namespace Ormin\OBSLexicalParser\Builds;


use Ormin\OBSLexicalParser\TES5\Graph\TES5ScriptDependencyGraph;

class TES5BuildPlanBuilder
{

    /**
     * @var TES5ScriptDependencyGraph
     */
    private $graph;

    public function __construct(TES5ScriptDependencyGraph $graph) {
        $this->graph = $graph;
    }

    public function createBuildPlan(BuildSourceFilesCollection $scripts, $threads = 4) {
        $codeScripts = [];
        /**
         * Mapping script names to build names
         */
        $scriptToBuild = [];

        foreach($scripts->getIterator() as $buildName => $buildScripts)
        {
            foreach($buildScripts as $k => $v) {
                $scriptName = substr($v,0,-4);
                $scriptNameKey = strtolower($scriptName);
                $codeScripts[$scriptNameKey] = $scriptName;
                $scriptToBuild[$scriptNameKey] = $buildName;
            }
        }

        $preparedChunks = [];
        $nonpairedScripts = [];

        $previousCount = count($codeScripts);
        /**
         * Prepare chunks of scripts and push lone scripts into a different array
         */
        while(count($codeScripts) > 0) {
            $currentScript = current($codeScripts);

            $preparedChunk = $this->graph->getScriptsToCompile($currentScript);

            if(count($preparedChunk) > 1) {

                /**
                 * Chunk mapped per-build
                 */
                $preparedMappedChunk = [];

                foreach($preparedChunk as $chunkScript) {

                    $chunkScriptKey = strtolower($chunkScript);
                    if(isset($codeScripts[$chunkScriptKey]))
                    {
                        unset($codeScripts[$chunkScriptKey]);
                    }


                    if(!isset($preparedMappedChunk[$scriptToBuild[$chunkScriptKey]])) {
                        $preparedMappedChunk[$scriptToBuild[$chunkScriptKey]] = [];
                    }

                    $preparedMappedChunk[$scriptToBuild[$chunkScriptKey]][] = $chunkScript;

                }

                $preparedChunks[] = $preparedMappedChunk;
            } else {
                $nonpairedChunkScript = $preparedChunk[0];
                $nonpairedScripts[] = $nonpairedChunkScript;
                $nonpairedChunkScriptKey = strtolower($nonpairedChunkScript);
                if(isset($codeScripts[$nonpairedChunkScriptKey]))
                {
                    unset($codeScripts[$nonpairedChunkScriptKey]);
                }
            }

            if(count($codeScripts) >= $previousCount) {
                throw new \LogicException("Error in planning build, circuit breaker on.");
            } else {
                $previousCount = count($codeScripts);
            }

        }

        $threadBuckets = [];
        $threadBucketsSizes = [];
        $bucket = 0;

        foreach($preparedChunks as $chunk) {

            if(!isset($threadBucketsSizes[$bucket])) {
                $threadBucketsSizes[$bucket] = 0;
            }

            $threadBuckets[$bucket][] = $chunk;

            foreach($chunk as $chunkBuild => $chunkScripts) {
                $threadBucketsSizes[$bucket] += count($chunkScripts);
            }

            ++$bucket;
            if($bucket == $threads) {
                $bucket = 0;
            }
        }

        //Evening the buckets
        $biggestBucket = max($threadBucketsSizes);

        foreach($threadBuckets as $bucketKey => $bucket) {
            $bucketSize = $threadBucketsSizes[$bucketKey];
            $neededScripts = $biggestBucket - $bucketSize;
            $eveningChunk = [];

            if($neededScripts >= count($nonpairedScripts)) {
                foreach($nonpairedScripts as $nonpairedScript) {

                    $chunkScriptBuild = $scriptToBuild[strtolower($nonpairedScript)];

                    if(!isset($eveningChunk[$chunkScriptBuild]))
                    {
                        $eveningChunk[$chunkScriptBuild] = [];
                    }

                    $eveningChunk[$chunkScriptBuild][] = $nonpairedScript;
                }

                $threadBuckets[$bucketKey][] = $eveningChunk;
                //Not sure if should be here but prolly yes?
                $threadBucketsSizes[$bucketKey] += count($nonpairedScripts);
                $nonpairedScripts = [];
                break;
            }

            $sliceOfNonpairedScripts = array_slice($nonpairedScripts, 0, $neededScripts);

            foreach($sliceOfNonpairedScripts as $sliceOfNonpairedScript) {

                $chunkScriptBuild = $scriptToBuild[strtolower($sliceOfNonpairedScript)];

                if(!isset($eveningChunk[$chunkScriptBuild]))
                {
                    $eveningChunk[$chunkScriptBuild] = [];
                }

                $eveningChunk[$chunkScriptBuild][] = $sliceOfNonpairedScript;
            }

            $threadBuckets[$bucketKey][] = $eveningChunk;
            $threadBucketsSizes[$bucketKey] += $neededScripts;
            $nonpairedScripts = array_slice($nonpairedScripts, $neededScripts);

        }

        $restChunks = [];
        $restChunkBucket = 0;

        foreach($nonpairedScripts as $nonpairedScript) {

            $singleScriptChunk = [];
            $chunkScriptBuild = $scriptToBuild[strtolower($nonpairedScript)];

            if(!isset($singleScriptChunk[$chunkScriptBuild]))
            {
                $singleScriptChunk[$chunkScriptBuild] = [];
            }


            $singleScriptChunk[$chunkScriptBuild][] = $nonpairedScript;
            $restChunks[$restChunkBucket][] = $singleScriptChunk;

            $restChunkBucket++;
            if($restChunkBucket == $threads) {
                $restChunkBucket = 0;
            }

        }

        foreach($restChunks as $bucketKey => $restOfScriptsChunks) {
            foreach($restOfScriptsChunks as $restOfScriptsChunk) {
                $threadBuckets[$bucketKey][] = $restOfScriptsChunk;
            }
        }

        return $threadBuckets;
    }

}