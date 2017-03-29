<?php

namespace Ormin\OBSLexicalParser\TES5\Factory\Functions;

use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4Function;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectCallArgumentsFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectCallFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ReferenceFactory;

/**
 * Class PopCalledRenameFunctionFactory
 * Pops the first argument to calledOn and renames the function
 * @package Ormin\OBSLexicalParser\TES5\Factory\Functions
 */
class PopCalledRenameFunctionFactory implements FunctionFactory
{

    /**
     * @var string
     */
    private $newFunctionName;

    /**
     * @var TES5ReferenceFactory
     */
    private $referenceFactory;

    /**
     * @var TES5ObjectCallFactory
     */
    private $objectCallFactory;

    /**
     * @var TES5ObjectCallArgumentsFactory
     */
    private $objectCallArgumentsFactory;

    public function __construct($newFunctionName, TES5ReferenceFactory $referenceFactory, TES5ObjectCallFactory $objectCallFactory, TES5ObjectCallArgumentsFactory $objectCallArgumentsFactory)
    {
        $this->newFunctionName = $newFunctionName;
        $this->referenceFactory = $referenceFactory;
        $this->objectCallArgumentsFactory = $objectCallArgumentsFactory;
        $this->objectCallFactory = $objectCallFactory;
    }


    public function convertFunction(TES5Referencer $calledOn, TES4Function $function, TES5CodeScope $codeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {
        $localScope = $codeScope->getLocalScope();
        $functionArguments = $function->getArguments();
        $calledOn = $this->referenceFactory->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
        return $this->objectCallFactory->createObjectCall($calledOn, $this->newFunctionName, $multipleScriptsScope, $this->objectCallArgumentsFactory->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
    }
}