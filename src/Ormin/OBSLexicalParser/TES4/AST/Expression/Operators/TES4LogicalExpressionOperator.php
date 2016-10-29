<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Expression\Operators;


use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class TES4LogicalExpressionOperator
 * @method static TES4LogicalExpressionOperator OPERATOR_OR()
 * @method static TES4LogicalExpressionOperator OPERATOR_AND()
 * @package Ormin\OBSLexicalParser\TES4\AST\Operators
 */
class TES4LogicalExpressionOperator extends AbstractEnumeration {

    const OPERATOR_OR = "||";
    const OPERATOR_AND = "&&";

} 