<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Expression;


use Ormin\OBSLexicalParser\TES4\AST\Expression\Operators\TES4LogicalExpressionOperator;
use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

class TES4LogicalExpression implements TES4Expression {

    /**
     * @var TES4Expression
     */
    private $leftValue;

    /**
     * @var TES4Expression
     */
    private $rightValue;

    /**
     * @var Operators\TES4LogicalExpressionOperator
     */
    private $operator;

    public function __construct(TES4Value $left, TES4LogicalExpressionOperator $operator, TES4Value $right) {
        $this->leftValue = $left;
        $this->operator = $operator;
        $this->rightValue = $right;
    }

    /**
     * @return TES4Value
     */
    public function getLeftValue()
    {
        return $this->leftValue;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Expression\Operators\TES4LogicalExpressionOperator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return TES4Value
     */
    public function getRightValue()
    {
        return $this->rightValue;
    }


    public function getData() {

        switch($this->operator) {
            case TES4LogicalExpressionOperator::OPERATOR_AND(): {
                return $this->leftValue->getData() && $this->rightValue->getData();
            }

            case TES4LogicalExpressionOperator::OPERATOR_OR(): {
                return $this->leftValue->getData() || $this->rightValue->getData();
            }

        }

        throw new ConversionException("Unknown TES4LogicalExpressionOperator");

    }

    public function hasFixedValue() {
        return $this->leftValue->hasFixedValue() && $this->rightValue->hasFixedValue();
    }

    public function filter(\Closure $c)
    {
        return array_merge($this->leftValue->filter($c), $this->rightValue->filter($c));
    }
} 