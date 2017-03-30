<?php

namespace Ormin\OBSLexicalParser\TES5\Factory\Functions;

use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4Function;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Bool;

class ReturnFalseFactory implements FunctionFactory
{

    public function convertFunction(TES5Referencer $calledOn, TES4Function $function, TES5CodeScope $codeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {
        //@INCONSISTENCE - Will always return false
        return new TES5Bool(false);

    }
}