<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Value\Primitive;


use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;

interface TES5Primitive extends TES5Value
{

    public function getValue();

} 