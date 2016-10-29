<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Value\Primitive;


use Ormin\OBSLexicalParser\TES5\Factory\TES5TypeFactory;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

class TES5Integer implements TES5Primitive{

    private $integer;

    function __construct($integer)
    {
        $this->integer = $integer;
    }

    public function output() {
        return [$this->integer];
    }

    public function getType() {
        return TES5BasicType::T_INT();
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->integer;
    }


} 