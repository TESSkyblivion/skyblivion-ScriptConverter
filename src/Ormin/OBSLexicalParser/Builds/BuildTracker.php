<?php
/**
 * Created by PhpStorm.
 * Date: 1/25/17
 * Time: 10:26 PM
 */

namespace Ormin\OBSLexicalParser\Builds;
use Ormin\OBSLexicalParser\TES5\AST\TES5Target;


/**
 * Class BuildTracker
 * Tracks the build of the build targets
 * @package Ormin\OBSLexicalParser\Builds
 */
class BuildTracker
{
    /**
     * @var BuildTargetCollection
     */
    private $buildTargetCollection;

    /**
     * @var TES5Target[]
     * Map build target => built TES5 scripts
     */
    private $builtScripts;

    /**
     * BuildTracker constructor.
     * @param BuildTargetCollection $buildTargetCollection
     */
    public function __construct(BuildTargetCollection $buildTargetCollection)
    {
        $this->buildTargetCollection = $buildTargetCollection;
        foreach($buildTargetCollection->getIterator() as $buildTarget) {
            $this->builtScripts[$buildTarget->getTargetName()] = [];
        }
    }


    public function registerBuiltScript(BuildTarget $buildTarget, TES5Target $script)
    {
        $this->builtScripts[$buildTarget->getTargetName()][$script->getScript()->getScriptHeader()->getScriptName()] = $script;
    }

    /**
     * @param String $targetName
     * @return TES5Target[]
     */
    public function getBuiltScripts($targetName)
    {
        if(!isset($this->builtScripts[$targetName])) {
            return [];
        }

        return $this->builtScripts[$targetName];
    }


}