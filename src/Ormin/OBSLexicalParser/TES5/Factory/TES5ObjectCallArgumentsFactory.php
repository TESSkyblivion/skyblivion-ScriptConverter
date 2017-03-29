<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4FunctionArguments;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCallArguments;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5LocalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;

class TES5ObjectCallArgumentsFactory {

    private $valueFactory;

    public function __construct(TES5ValueFactory $factory) {
        $this->valueFactory = $factory;
    }


    public function createArgumentList(TES4FunctionArguments $arguments = null, TES5CodeScope $codeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {

        $list = new TES5ObjectCallArguments();

        if ($arguments === null) {
            return $list;
        }

        foreach ($arguments->getValues() as $argument) {
            $newValue = $this->valueFactory->createValue($argument, $codeScope, $globalScope, $multipleScriptsScope);
            $list->add($newValue);
        }

        return $list;

    }

} 