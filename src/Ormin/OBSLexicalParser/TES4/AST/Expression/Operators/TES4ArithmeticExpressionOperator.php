<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Expression\Operators;


use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class TES4ArithmeticExpressionOperator
 * @method static TES4ArithmeticExpressionOperator OPERATOR_EQUAL()
 * @method static TES4ArithmeticExpressionOperator OPERATOR_NOT_EQUAL()
 * @method static TES4ArithmeticExpressionOperator OPERATOR_GREATER()
 * @method static TES4ArithmeticExpressionOperator OPERATOR_GREATER_OR_EQUAL()
 * @method static TES4ArithmeticExpressionOperator OPERATOR_LESS()
 * @method static TES4ArithmeticExpressionOperator OPERATOR_LESS_OR_EQUAL()
 * @package Ormin\OBSLexicalParser\TES4\AST\Operators
 */
class TES4ArithmeticExpressionOperator extends AbstractEnumeration {

    const OPERATOR_EQUAL = "==";
    const OPERATOR_NOT_EQUAL = "!=";
    const OPERATOR_GREATER = ">";
    const OPERATOR_GREATER_OR_EQUAL = ">=";
    const OPERATOR_LESS = "<";
    const OPERATOR_LESS_OR_EQUAL = "<=";

} 