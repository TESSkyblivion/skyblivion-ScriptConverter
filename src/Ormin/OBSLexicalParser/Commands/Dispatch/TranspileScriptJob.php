<?php

namespace Ormin\OBSLexicalParser\Commands\Dispatch;

use Ormin\OBSLexicalParser\Builds\BuildTarget;
use Ormin\OBSLexicalParser\Builds\BuildTargetFactory;
use Ormin\OBSLexicalParser\TES5\Graph\TES5ScriptDependencyGraph;

class TranspileScriptJob
{

    private $dependencyGraph;

    /**
     * @var string
     */
    private $buildTarget;

    public function __construct(TES5ScriptDependencyGraph $dependencyGraph, $buildTarget, $script)
    {
        $this->dependencyGraph = $dependencyGraph;
        $this->buildTarget = $buildTarget;
        $this->script = $script;
    }


    public function run()
    {
        $buildTarget = BuildTargetFactory::get($this->buildTarget);

        $scripts = $this->dependencyGraph->getScriptsToCompile($this->script);

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