<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Value\Primitive;

use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5None implements TES5Primitive
{

    public function output()
    {
        return ['None'];
    }

    public function getType()
    {
        return TES5BasicType::T_FORM();
    }

    public function getValue() {
        return null;
    }
} 