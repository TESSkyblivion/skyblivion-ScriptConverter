<?php

namespace Ormin\OBSLexicalParser\Commands\Dispatch;

use Ormin\OBSLexicalParser\Builds\Build;
use Ormin\OBSLexicalParser\Builds\BuildTarget;
use Ormin\OBSLexicalParser\Builds\BuildTargetCollection;
use Ormin\OBSLexicalParser\Builds\BuildTargetFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5StaticGlobalScopesFactory;
use Ormin\OBSLexicalParser\TES5\Graph\TES5ScriptDependencyGraph;

class TranspileScriptJob
{


    /**
     * @var BuildTargetCollection
     */
    private $buildTargets;

    /**
     * @var string
     */
    private $scriptName;

    /**
     * TranspileScriptJob constructor.
     * @param BuildTargetCollection $buildTargets
     * @param $scriptName
     */
    public function __construct(BuildTargetCollection $buildTargets, $scriptName)
    {
        $this->buildTargets = $buildTargets;
        $this->scriptName = $scriptName;
    }

    public function run()
    {

        $scripts = $this->buildTargets->getScriptsToCompile($this->scriptName);
        $partitionedScripts = $this->buildTargets->getSourceFiles($scripts);

        $sourcePaths = [];
        $outputPaths = [];


        
        foreach($scripts as $buildScript) {

            $scriptName = pathinfo($buildScript, PATHINFO_FILENAME);
            $sourcePath = $buildTarget->getSourceFromPath($scriptName);
            $outputPath = $buildTarget->getTranspileToPath($scriptName);
            $sourcePaths[] = $sourcePath;
            $outputPaths[] = $outputPath;
        }

        $buildTarget->transpile($sourcePaths, $outputPaths);
    }

}