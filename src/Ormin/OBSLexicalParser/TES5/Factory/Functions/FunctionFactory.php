<?php

namespace Ormin\OBSLexicalParser\TES5\Factory\Functions;


use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4Function;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunk;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;

interface FunctionFactory
{
    /**
     * Convert a function from TES4 to TES5.
     * @param TES5Referencer $calledOn The reference upon which call is done ( Given A.B(), $calledOn = A )
     * @param TES4Function $function The function called ( Given A.B() , $function = B )
     * @param TES5CodeScope $codeScope Code ( branch ) scope we're in
     * @param TES5GlobalScope $globalScope Script scope we're in
     * @param TES5MultipleScriptsScope $multipleScriptsScope Container of all scripts compiled together
     * @return TES5CodeChunk
     */
    public function convertFunction(TES5Referencer $calledOn, TES4Function $function, TES5CodeScope $codeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope);

}