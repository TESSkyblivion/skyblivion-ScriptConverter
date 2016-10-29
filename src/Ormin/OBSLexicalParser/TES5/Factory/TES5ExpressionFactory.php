<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5ArithmeticExpressionOperator;
use Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5BinaryExpressionOperator;
use Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5LogicalExpressionOperator;
use Ormin\OBSLexicalParser\TES5\AST\Expression\TES5ArithmeticExpression;
use Ormin\OBSLexicalParser\TES5\AST\Expression\TES5BinaryExpression;
use Ormin\OBSLexicalParser\TES5\AST\Expression\TES5BoolCastedExpression;
use Ormin\OBSLexicalParser\TES5\AST\Expression\TES5Expression;
use Ormin\OBSLexicalParser\TES5\AST\Expression\TES5LogicalExpression;
use Ormin\OBSLexicalParser\TES5\AST\Expression\TES5TrueBooleanExpression;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;

class TES5ExpressionFactory {

    public function createLogicalExpression(TES5Value $left, TES5LogicalExpressionOperator $operator, TES5Value $right) {
        return new TES5LogicalExpression($left,$operator,$right);
    }

    public function createArithmeticExpression(TES5Value $left, TES5ArithmeticExpressionOperator $operator, TES5Value $right) {
        return new TES5ArithmeticExpression($left,$operator,$right);
    }

    public function createTrueBooleanExpression(TES5Value $valueToBeTrue) {
        return new TES5TrueBooleanExpression($valueToBeTrue);
    }

    public function createBoolCastedExpression(TES5Value $value) {
        return new TES5BoolCastedExpression($value);
    }

    public function createBinaryExpression(TES5Value $left, TES5BinaryExpressionOperator $operator, TES5Value $right) {
        return new TES5BinaryExpression($left, $operator, $right);
    }

} 