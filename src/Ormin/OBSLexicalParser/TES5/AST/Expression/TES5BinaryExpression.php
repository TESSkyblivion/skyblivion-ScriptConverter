<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Expression;

use Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5BinaryExpressionOperator;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;
use Ormin\OBSLexicalParser\TES5\Factory\TES5TypeFactory;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

class TES5BinaryExpression implements TES5Expression
{

    /**
     * @var TES5Value
     */
    private $leftValue;

    /**
     * @var \Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5BinaryExpressionOperator
     */
    private $operator;

    /**
     * @var TES5Value
     */
    private $rightValue;

    public function __construct(TES5Value $left, TES5BinaryExpressionOperator $operator, TES5Value $right)
    {
        $this->leftValue = $left;
        $this->operator = $operator;
        $this->rightValue = $right;
    }

    public function output()
    {
        $leftOutput = $this->leftValue->output();
        $leftOutput = $leftOutput[0];
        $rightOutput = $this->rightValue->output();
        $rightOutput = $rightOutput[0];
        return ['(' . $leftOutput . ' ' . $this->operator->value() . ' ' . $rightOutput . ')'];
    }

    public function getType()
    {

        if($this->leftValue->getType() == TES5BasicType::T_FLOAT() || $this->rightValue->getType() == TES5BasicType::T_FLOAT()) {
            return TES5BasicType::T_FLOAT();
        }

        return TES5BasicType::T_INT();
    }

} 