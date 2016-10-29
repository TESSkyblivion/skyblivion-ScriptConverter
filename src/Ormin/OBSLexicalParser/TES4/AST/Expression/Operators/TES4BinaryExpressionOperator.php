<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Expression\Operators;
use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class TES4BinaryExpressionOperator
 * @method static TES4BinaryExpressionOperator OPERATOR_ADD()
 * @method static TES4BinaryExpressionOperator OPERATOR_SUBSTRACT()
 * @method static TES4BinaryExpressionOperator OPERATOR_MULTIPLY()
 * @method static TES4BinaryExpressionOperator OPERATOR_DIVIDE()
 * @package Ormin\OBSLexicalParser\TES4\AST\Operators
 */
class TES4BinaryExpressionOperator extends AbstractEnumeration {

    const OPERATOR_ADD = "+";
    const OPERATOR_SUBSTRACT = "-";
    const OPERATOR_MULTIPLY = "*";
    const OPERATOR_DIVIDE = "/";

} 