<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Value\ObjectAccess;

use Ormin\OBSLexicalParser\TES4\AST\COde\TES4CodeChunk;
use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4Callable;
use Ormin\OBSLexicalParser\TES4\AST\Value\TES4ApiToken;
use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4Function;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

class TES4ObjectCall implements TES4Callable, TES4ObjectAccess, TES4CodeChunk {

    private $called;

    private $function;

    public function __construct(TES4ApiToken $apiToken, TES4Function $function) {
        $this->called = $apiToken;
        $this->function = $function;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Value\TES4ApiToken
     */
    public function getCalledOn()
    {
        return $this->called;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4Function
     */
    public function getFunction()
    {
        return $this->function;
    }

    public function getData() {
        throw new ConversionException("TES4Function::getData() - not supported");
    }


    public function hasFixedValue() {
        return false;
    }

    public function filter(\Closure $c) {

        return array_merge($this->called->filter($c), $this->function->filter($c));
    }


}
