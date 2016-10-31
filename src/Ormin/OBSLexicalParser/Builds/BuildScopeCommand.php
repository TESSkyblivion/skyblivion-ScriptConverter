<?php
/**
 * Created by PhpStorm.
 * Date: 10/31/16
 * Time: 2:16 PM
 */

namespace Ormin\OBSLexicalParser\Builds;


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
     * @param string $sourcePath
     * @return TES5GlobalScope
     */
    public function buildScope($sourcePath);

}