<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Expression\Operators;
use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class TES5BinaryExpressionOperator
 * @method static TES5BinaryExpressionOperator OPERATOR_ADD()
 * @method static TES5BinaryExpressionOperator OPERATOR_SUBSTRACT()
 * @method static TES5BinaryExpressionOperator OPERATOR_MULTIPLY()
 * @method static TES5BinaryExpressionOperator OPERATOR_DIVIDE()
 * @package Ormin\OBSLexicalParser\TES5\AST\Operators
 */
class TES5BinaryExpressionOperator extends AbstractEnumeration {

    const OPERATOR_ADD = "+";
    const OPERATOR_SUBSTRACT = "-";
    const OPERATOR_MULTIPLY = "*";
    const OPERATOR_DIVIDE = "/";

} 