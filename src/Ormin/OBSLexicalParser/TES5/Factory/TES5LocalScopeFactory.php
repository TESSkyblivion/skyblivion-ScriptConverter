<?php
/**
 * Created by PhpStorm.
 * Date: 11/20/16
 * Time: 8:01 PM
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5FunctionScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5LocalScope;

class TES5LocalScopeFactory
{

    public function createRootScope(TES5FunctionScope $functionScope)
    {
        return new TES5LocalScope($functionScope);
    }

    public function createRecursiveScope(TES5LocalScope $parentScope)
    {
        return new TES5LocalScope($parentScope->getFunctionScope(), $parentScope);
    }

}