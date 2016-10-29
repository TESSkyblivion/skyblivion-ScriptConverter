<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall;


use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunk;
use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

class TES4Function implements TES4Callable, TES4Value, TES4CodeChunk{

    private $functionCall;

    private $arguments;

    public function __construct(TES4FunctionCall $functionCall, TES4FunctionArguments $arguments) {
        $this->functionCall = $functionCall;
        $this->arguments = $arguments;
    }

    public function getCalledOn() {
        return null;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4FunctionArguments
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4FunctionCall
     */
    public function getFunctionCall()
    {
        return $this->functionCall;
    }

    public function getData() {
        throw new ConversionException("TES4Function::getData() - not supported");
    }

    public function getFunction() {
        return $this;
    }

    public function hasFixedValue() {
        return false;
    }


    public function filter(\Closure $c) {
        $filtered = [];
        if($c($this->functionCall)) {
            $filtered[] = $this->functionCall;
        }

        return array_merge($filtered, $this->arguments->filter($c));
    }

}