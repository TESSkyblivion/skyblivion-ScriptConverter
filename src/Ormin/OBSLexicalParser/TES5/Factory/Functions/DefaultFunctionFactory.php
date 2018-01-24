<?php

namespace Ormin\OBSLexicalParser\TES5\Factory\Functions;

use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4Function;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectCallArgumentsFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectCallFactory;

/**
 * Class DefaultFunctionFactory
 * Converts functions which are a simple ,,just move signature over", as in - we don't do any changes to the function call
 * @package Ormin\OBSLexicalParser\TES5\Factory\Functions
 */
class DefaultFunctionFactory implements FunctionFactory
{
    /**
     * @var TES5ObjectCallFactory
     */
    private $objectCallFactory;

    /**
     * @var TES5ObjectCallArgumentsFactory
     */
    private $objectCallArgumentsFactory;

    public function __construct(TES5ObjectCallFactory $objectCallFactory, TES5ObjectCallArgumentsFactory $objectCallArgumentsFactory)
    {
        $this->objectCallArgumentsFactory = $objectCallArgumentsFactory;
        $this->objectCallFactory = $objectCallFactory;
    }


    public function convertFunction(TES5Referencer $calledOn, TES4Function $function, TES5CodeScope $codeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {
        $functionName = $function->getFunctionCall()->getFunctionName();
        $functionArguments = $function->getArguments();
        return $this->objectCallFactory->createObjectCall($calledOn, $functionName, $multipleScriptsScope, $this->objectCallArgumentsFactory->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
    }
}