<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 21.12.15
 * Time: 00:04
 */

namespace Ormin\OBSLexicalParser\TES5\Graph;


class TES5ScriptDependencyGraph
{

    private $graph;

    private $usageGraph;

    public function __construct(array $graph, array $usageGraph) {
        $this->graph = $graph;
        $this->usageGraph = $usageGraph;
    }

    public function getScriptsToCompile($scriptName) {
        $dependenciesFound = [strtolower($scriptName)];
        return array_merge([strtolower($scriptName)],$this->getDependenciesFor($scriptName, $dependenciesFound));
    }

    /**
     * Finds dependencies to this script.
     * Will resolve both the scripts this script depends on and the scripts that depend on this script
     * @param $scriptName
     * @param array $foundDependen  cies
     * @return array
     */
    private function getDependenciesFor($scriptName, array &$foundDependencies) {
        $lowerScriptName = strtolower($scriptName);
        $dependencies = [];
        if(isset($this->graph[$lowerScriptName])) {
            foreach($this->graph[$lowerScriptName] as $dependencyScript) {
                $lowerDependencyScript = strtolower($dependencyScript);
                if(in_array($lowerDependencyScript, $foundDependencies)) {
                    continue; //Do not resolve the cycle
                }

                $dependencies[] = $dependencyScript;
                $foundDependencies[] = $lowerDependencyScript;
                $dependencies = array_merge($dependencies,$this->getDependenciesFor($dependencyScript, $foundDependencies));

            }
        }


        if(isset($this->usageGraph[$lowerScriptName])) {
            foreach ($this->usageGraph[$lowerScriptName] as $usingScript) {
                $lowerUsingScript = strtolower($usingScript);
                if (in_array($lowerUsingScript, $foundDependencies)) {
                    continue; //Do not resolve the cycle
                }

                $dependencies[] = $usingScript;
                $foundDependencies[] = $lowerUsingScript;
                $dependencies = array_merge($dependencies, $this->getDependenciesFor($usingScript, $foundDependencies));
            }
        }

        return $dependencies;

    }

}