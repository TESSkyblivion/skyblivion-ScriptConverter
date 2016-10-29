<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Expression\Operators;


use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class TES5LogicalExpressionOperator
 * @method static TES5LogicalExpressionOperator OPERATOR_OR()
 * @method static TES5LogicalExpressionOperator OPERATOR_AND()
 * @package Ormin\OBSLexicalParser\TES5\AST\Operators
 */
class TES5LogicalExpressionOperator extends AbstractEnumeration {

    const OPERATOR_OR = "||";
    const OPERATOR_AND = "&&";

} 