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

    public function createBuildPlan($scripts, $threads = 4) {
        $lowerScripts = [];
        foreach($scripts as $k => $v) {
            $scripts[$k] = substr($v,0,-4);
            $lowerScripts[$k] = strtolower($scripts[$k]);
        }

        $preparedChunks = [];
        $nonpairedScripts = [];

        while(count($scripts) > 0) {
            $currentScript = current($scripts);


            $preparedChunk = $this->graph->getScriptsToCompile($currentScript);

            if(count($preparedChunk) > 1) {
                $preparedChunks[] = $preparedChunk;
            } else {
                $nonpairedScripts[] = $preparedChunk[0];
            }

            foreach($preparedChunk as $chunkScript) {


                $key = array_search(strtolower($chunkScript), $lowerScripts);
                if($key !== false) {
                    unset($scripts[$key]);
                    unset($lowerScripts[$key]);
                }
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
            $threadBucketsSizes[$bucket] += count($chunk);
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
            if($neededScripts >= count($nonpairedScripts)) {
                $threadBuckets[$bucketKey][] = $nonpairedScripts;
                break;
            }

            $threadBuckets[$bucketKey][] = array_slice($nonpairedScripts, 0, $neededScripts);
            $threadBucketsSizes[$bucketKey] += $neededScripts;
            $nonpairedScripts = array_slice($nonpairedScripts, $neededScripts);

        }


        $restChunks = [];
        $restChunkBucket = 0;

        foreach($nonpairedScripts as $nonpairedScript) {
            $restChunks[$restChunkBucket][] = [$nonpairedScript];

            $restChunkBucket++;
            if($restChunkBucket == $threads) {
                $restChunkBucket = 0;
            }

        }

        foreach($restChunks as $bucketKey => $restOfScripts) {
            $threadBuckets[$bucketKey] = array_merge($threadBuckets[$bucketKey],$restOfScripts);
            $threadBucketsSizes[$bucketKey] += count($restOfScripts);
        }

        return $threadBuckets;
    }

}