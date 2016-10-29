<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall;


class TES4FunctionCall {

    private $functionName;

    public function __construct($functionName) {
        $this->functionName = $functionName;
    }

    /**
     * @return mixed
     */
    public function getFunctionName()
    {
        return $this->functionName;
    }



} 