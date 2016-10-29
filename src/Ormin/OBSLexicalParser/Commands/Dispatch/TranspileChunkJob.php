<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 11/10/2015
 * Time: 10:50 PM
 */

namespace Ormin\OBSLexicalParser\Commands\Dispatch;

use Ormin\OBSLexicalParser\Builds\BuildTargetFactory;

class TranspileChunkJob extends \Threaded
{
    /**
     * @var string
     */
    private $buildTarget;

    /**
     * @var string[][]
     */
    private $buildPlan;

    public function __construct($buildTarget, $buildPlan)
    {
        $this->buildTarget = $buildTarget;
        $this->buildPlan = $buildPlan;
    }


    public function runTask(\Amp\Deferred $deferred)
    {

        $buildTarget = BuildTargetFactory::get($this->buildTarget);


        foreach ($this->buildPlan as $buildChunk) {

            $sourcePaths = [];
            $outputPaths = [];

            foreach($buildChunk as $buildScript) {

                $scriptName = pathinfo($buildScript, PATHINFO_FILENAME);
                $sourcePath = $buildTarget->getSourceFromPath($scriptName);
                $outputPath = $buildTarget->getTranspileToPath($scriptName);
                $sourcePaths[] = $sourcePath;
                $outputPaths[] = $outputPath;
            }

            try {
                $buildTarget->transpile($sourcePaths, $outputPaths);
                $this->updateWorker($deferred, true,$buildChunk);
            } catch (\Exception $e) {
                $this->updateWorker($deferred, false,$buildChunk,get_class($e) . PHP_EOL . $e->getMessage() . PHP_EOL);
            }

        }

        $deferred->succeed();
    }

    protected function updateWorker(\Amp\Deferred $deferred, $success, $scripts, $exception = null) {

        $data = ['success' => $success, 'scripts' => $scripts];

        if($exception !== null) {
            $data['exception'] = $exception;
        }

        $deferred->update($data);

    }

}
