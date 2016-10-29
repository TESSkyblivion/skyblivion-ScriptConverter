<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Value;

use Ormin\OBSLexicalParser\TES5\AST\TES5Outputtable;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

/**
 * Interface TES5Value
 * @package Ormin\OBSLexicalParser\TES5\AST\Value
 *
 * Represents something that returns a result value upon evaluating. This might be a primitive, an expression, an data-returning object call, or an reference to a property.
 */
interface TES5Value extends TES5Outputtable
{

    /**
     * @return TES5Type
     */
    public function getType();

} 