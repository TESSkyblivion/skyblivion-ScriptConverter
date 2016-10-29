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

class TES5ObjectCallArgumentsFactory {

    private $valueFactory;

    public function __construct(TES5ValueFactory $factory) {
        $this->valueFactory = $factory;
    }

    public function createArgumentList(TES4FunctionArguments $arguments,TES5CodeScope $codeScope, TES5LocalScope $localScope) {

        $list = new TES5ObjectCallArguments();

        foreach($arguments->getValues() as $argument) {
            $newValue = $this->valueFactory->createValue($argument,$codeScope, $localScope);
            $list->add($newValue);
        }

        return $list;

    }

} 