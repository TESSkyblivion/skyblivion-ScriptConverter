<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Expression;


use Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5LogicalExpressionOperator;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;
use Ormin\OBSLexicalParser\TES5\Factory\TES5TypeFactory;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5LogicalExpression implements TES5Expression {

    /**
     * @var \Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value
     */
    private $leftValue;

    /**
     * @var \Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value
     */
    private $rightValue;

    /**
     * @var Operators\TES5LogicalExpressionOperator
     */
    private $operator;

    public function __construct(TES5Value $left, TES5LogicalExpressionOperator $operator, TES5Value $right) {
        $this->leftValue = $left;
        $this->operator = $operator;
        $this->rightValue = $right;
    }

    public function output() {
        $leftOutput = $this->leftValue->output();
        $leftOutput = $leftOutput[0];
        $rightOutput = $this->rightValue->output();
        $rightOutput = $rightOutput[0];
        return ['('.$leftOutput.' '.$this->operator->value().' '.$rightOutput.')'];
    }

    public function getType() {
        return TES5BasicType::T_BOOL();
    }

} 