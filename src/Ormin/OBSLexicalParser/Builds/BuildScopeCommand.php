<?php
/**
 * Created by PhpStorm.
 * Date: 10/31/16
 * Time: 2:16 PM
 */

namespace Ormin\OBSLexicalParser\Builds;


use Ormin\OBSLexicalParser\TES5\AST\Property\Collection\TES5GlobalVariables;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;

interface BuildScopeCommand
{

    public function initialize();

    /**
     * Build a global scope for a given source path.
     *
     * Command is expected to return a valid global scope for the given script path, assuming the build target
     * it is in ( as in - global scopes are built in a different way for Standalone, different for TIF, etc. )
     *
     * Global variables are passed so that when parsing variable declarations list ( i.e. ref XXX from Obscript ),
     * we're able to tell from the start if property is a GlobalVariable or not
     *
     * @param string $sourcePath
     * @param TES5GlobalVariables $globalVariables Defined global variables used within the scope
     * @return TES5GlobalScope
     */
    public function buildScope($sourcePath, TES5GlobalVariables $globalVariables);

}