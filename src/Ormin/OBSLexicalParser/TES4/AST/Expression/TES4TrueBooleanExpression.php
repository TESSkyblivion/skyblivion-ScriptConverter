<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Expression;


use Ormin\OBSLexicalParser\TES4\AST\Expression\Operators\TES4ArithmeticExpressionOperator;
use Ormin\OBSLexicalParser\TES4\AST\Value\Primitive\TES4Integer;
use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value;

class TES4TrueBooleanExpression implements TES4Expression{

    /**
     * @var \Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value
     */
    private $value;

    public function __construct(TES4Value $value) {
        $this->value = $value;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value
     */
    public function getLeftValue()
    {
        return $this->value;
    }

    public function getRightValue() {
        return new TES4Integer(true);
    }

    public function getOperator() {
        return TES4ArithmeticExpressionOperator::OPERATOR_EQUAL();
    }

    public function getData() {
        return $this->value->getData() === true;
    }

    public function hasFixedValue() {
        return $this->value->hasFixedValue();
    }

    public function filter(\Closure $c)
    {
        return $this->value->filter($c);
    }
} 