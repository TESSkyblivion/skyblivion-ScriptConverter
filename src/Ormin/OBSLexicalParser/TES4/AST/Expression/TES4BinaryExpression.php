<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Expression;

use Ormin\OBSLexicalParser\TES4\AST\Expression\Operators\TES4BinaryExpressionOperator;
use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

class TES4BinaryExpression implements TES4Expression
{

    /**
     * @var TES4Value
     */
    private $leftValue;

    /**
     * @var \Ormin\OBSLexicalParser\TES4\AST\Expression\Operators\TES4BinaryExpressionOperator
     */
    private $operator;

    /**
     * @var TES4Value
     */
    private $rightValue;

    public function __construct(TES4Value $left, TES4BinaryExpressionOperator $operator, TES4Value $right)
    {
        $this->leftValue = $left;
        $this->operator = $operator;
        $this->rightValue = $right;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value
     */
    public function getLeftValue()
    {
        return $this->leftValue;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Expression\Operators\TES4BinaryExpressionOperator
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

    public function getData()
    {
        switch ($this->operator) {
            case TES4BinaryExpressionOperator::OPERATOR_ADD(): {
                return $this->leftValue->getData() + $this->rightValue->getData();
            }

            case TES4BinaryExpressionOperator::OPERATOR_DIVIDE(): {
                return $this->leftValue->getData() / $this->rightValue->getData();
            }

            case TES4BinaryExpressionOperator::OPERATOR_MULTIPLY(): {
                return $this->leftValue->getData() * $this->rightValue->getData();
            }

            case TES4BinaryExpressionOperator::OPERATOR_SUBSTRACT(): {
                return $this->leftValue->getData() - $this->rightValue->getData();
            }

        }

        throw new ConversionException("Unknown TES4BinaryExpressionOperator");
    }


    public function hasFixedValue()
    {
        return $this->leftValue->hasFixedValue() && $this->rightValue->hasFixedValue();
    }


    public function filter(\Closure $c)
    {
        return array_merge($this->leftValue->filter($c), $this->rightValue->filter($c));
    }

}