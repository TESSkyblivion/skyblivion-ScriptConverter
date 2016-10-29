<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Expression;


use Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5ArithmeticExpressionOperator;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Bool;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;
use Ormin\OBSLexicalParser\TES5\Factory\TES5TypeFactory;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

class TES5TrueBooleanExpression implements TES5Expression{

    /**
     * @var \Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value
     */
    private $value;

    public function __construct(TES5Value $value) {
        $this->value = $value;
    }

    public function getType() {
        return TES5BasicType::T_BOOL();
    }

    public function output() {
        $true = new TES5Bool(true);
        $operator = TES5ArithmeticExpressionOperator::OPERATOR_EQUAL();
        $outputValue = implode(' ',$this->value->output());
        $trueOutputValue = implode(' ',$true->output());

        return ['('.$outputValue.' '.$operator->value().' '.$trueOutputValue.')'];
    }

}