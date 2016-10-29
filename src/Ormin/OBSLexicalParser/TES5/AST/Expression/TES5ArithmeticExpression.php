<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Expression;


use Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5ArithmeticExpressionOperator;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;
use Ormin\OBSLexicalParser\TES5\Factory\TES5TypeFactory;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

class TES5ArithmeticExpression implements TES5Expression {

    /**
     * @var TES5Value
     */
    private $leftValue;

    /**
     * @var Operators\TES5ArithmeticExpressionOperator
     */
    private $operator;

    /**
     * @var TES5Value
     */
    private $rightValue;

    public function __construct(TES5Value $leftValue, TES5ArithmeticExpressionOperator $operator, TES5Value $rightValue) {
        $this->leftValue = $leftValue;
        $this->operator = $operator;
        $this->rightValue = $rightValue;
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