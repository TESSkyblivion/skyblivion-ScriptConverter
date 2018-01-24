<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds;


use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\TES5Target;

interface TranspileCommand {

    public function initialize(Build $build);

    /**
     * @param $sourcePaths
     * @param $outputPaths
     * @param TES5GlobalScope $globalScope
     * @param TES5MultipleScriptsScope $multipleScriptsScope
     * @return TES5Target
     */
    public function transpile($sourcePaths, $outputPaths, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope);

} 