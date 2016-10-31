<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds;


use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;

interface TranspileCommand {

    public function initialize();

    public function transpile($sourcePaths, $outputPaths, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope);

} 