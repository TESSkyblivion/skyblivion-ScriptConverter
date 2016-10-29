<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Expression;


use Ormin\OBSLexicalParser\TES4\AST\Expression\Operators\TES4ArithmeticExpressionOperator;
use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

class TES4ArithmeticExpression implements TES4Expression {

    /**
     * @var TES4Value
     */
    private $leftValue;

    /**
     * @var Operators\TES4ArithmeticExpressionOperator
     */
    private $operator;

    /**
     * @var TES4Value
     */
    private $rightValue;

    public function __construct(TES4Value $leftValue, TES4ArithmeticExpressionOperator $operator, TES4Value $rightValue) {
        $this->leftValue = $leftValue;
        $this->operator = $operator;
        $this->rightValue = $rightValue;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value
     */
    public function getLeftValue()
    {
        return $this->leftValue;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Expression\Operators\TES4ArithmeticExpressionOperator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value
     */
    public function getRightValue()
    {
        return $this->rightValue;
    }

    public function getData() {

        switch($this->operator) {
            case TES4ArithmeticExpressionOperator::OPERATOR_EQUAL(): {
                return $this->leftValue->getData() == $this->rightValue->getData();
            }

            case TES4ArithmeticExpressionOperator::OPERATOR_GREATER(): {
                return $this->leftValue->getData() > $this->rightValue->getData();
            }

            case TES4ArithmeticExpressionOperator::OPERATOR_GREATER_OR_EQUAL(): {
                return $this->leftValue->getData() >= $this->rightValue->getData();
            }

            case TES4ArithmeticExpressionOperator::OPERATOR_LESS(): {
                return $this->leftValue->getData() < $this->rightValue->getData();
            }

            case TES4ArithmeticExpressionOperator::OPERATOR_LESS_OR_EQUAL(): {
                return $this->leftValue->getData() <= $this->rightValue->getData();
            }

            case TES4ArithmeticExpressionOperator::OPERATOR_NOT_EQUAL(): {
                return $this->leftValue->getData() != $this->rightValue->getData();
            }

        }

        throw new ConversionException("Unknown TES4ArithmeticExpressionOperator");
    }

    public function hasFixedValue() {
        return $this->leftValue->hasFixedValue() && $this->rightValue->hasFixedValue();
    }


    public function filter(\Closure $c)
    {
        return array_merge($this->leftValue->filter($c), $this->rightValue->filter($c));
    }

} 