<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 11/10/2015
 * Time: 10:50 PM
 */

namespace Ormin\OBSLexicalParser\Commands\Dispatch;

use Ormin\OBSLexicalParser\Builds\Build;
use Ormin\OBSLexicalParser\Builds\BuildTarget;
use Ormin\OBSLexicalParser\Builds\BuildTargetCollection;
use Ormin\OBSLexicalParser\Builds\BuildTargetFactory;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\Factory\TES5StaticGlobalScopesFactory;

class TranspileChunkJob
{

    /**
     * @var BuildTargetCollection
     */
    private $buildTargets;

    /**
     * @var string[][][]
     */
    private $buildPlan;

    /**
     * @var Build
     */
    private $build;

    /**
     * @var TES5StaticGlobalScopesFactory
     */
    private $staticGlobalScopesFactory;

    /**
     * No injection is done here because of multithreaded enviroment which fucks it up.
     * Maybe at some point we will have a proper DI into the jobs.
     * TranspileChunkJob constructor.
     * @param $buildPath
     * @param $buildPlan
     */
    public function __construct($buildPath, $buildPlan)
    {
        $this->buildPlan = $buildPlan;
        $this->build = new Build($buildPath);
        $this->staticGlobalScopesFactory = new TES5StaticGlobalScopesFactory();
        $this->buildTargets = new BuildTargetCollection();
    }


    public function runTask(\Amp\Deferred $deferred)
    {

        foreach ($this->buildPlan as $buildChunk) {

            /**
             * @var TES5GlobalScope[] $scriptsScopes
             */
            $scriptsScopes = [];

            /**
             * First, build the scripts scope
             */
            foreach($buildChunk as $buildTargetName => $buildScripts) {

                $buildTarget = $this->getBuildTarget($buildTargetName);

                foreach($buildScripts as $buildScript) {
                    //Is that even needed here?
                    $scriptName = pathinfo($buildScript, PATHINFO_FILENAME);
                    $sourcePath = $buildTarget->getSourceFromPath($scriptName);
                    $scriptsScopes[$scriptName] = $buildTarget->buildScope($sourcePath);
                }
            }

            //Add the static global scopes which are added by complimenting scripts..
            $staticGlobalScopes = $this->staticGlobalScopesFactory->createGlobalScopes();
            foreach ($staticGlobalScopes as $staticGlobalScope) {
                $scriptsScopes[] = $staticGlobalScope;
            }

            $multipleScriptsScope = new TES5MultipleScriptsScope($scriptsScopes);

            foreach($buildChunk as $buildTargetName => $buildScripts) {

                foreach ($buildScripts as $buildScript) {

                    $buildTarget = $this->getBuildTarget($buildTargetName);
                    $scriptName = pathinfo($buildScript, PATHINFO_FILENAME);
                    $globalScope = $scriptsScopes[$scriptName];
                    $sourcePath = $buildTarget->getSourceFromPath($scriptName);
                    $outputPath = $buildTarget->getTranspileToPath($scriptName);

                    try {
                        $buildTarget->transpile($sourcePath, $outputPath, $globalScope, $multipleScriptsScope);
                        $this->updateWorker($deferred, true,$buildChunk);
                    } catch (\Exception $e) {
                        $this->updateWorker($deferred, false,$buildChunk,get_class($e) . PHP_EOL . $e->getMessage() . PHP_EOL);
                    }
                }
            }

        }

        $deferred->succeed();
    }

    /**
     * @param $targetName
     * @return BuildTarget
     */
    private function getBuildTarget($targetName)
    {

        if($this->buildTargets->getByName($targetName) === null) {
            $this->buildTargets->add(BuildTargetFactory::get($targetName, $this->build));
        }

        $result = $this->buildTargets->getByName($targetName);

        if($result === null)
        {
            throw new \LogicException("Unknown build ".$targetName);
        }

        return $result;

    }

    protected function updateWorker(\Amp\Deferred $deferred, $success, $scripts, $exception = null) {

        $data = ['success' => $success, 'scripts' => $scripts];

        if($exception !== null) {
            $data['exception'] = $exception;
        }

        $deferred->update($data);

    }

}
