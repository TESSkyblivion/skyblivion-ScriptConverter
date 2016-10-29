<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Value\Primitive;


use Ormin\OBSLexicalParser\TES5\Factory\TES5TypeFactory;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

class TES5Float implements TES5Primitive {

    private $float;

    public function __construct($float = null) {
        $this->float = $float;
    }

    public function output() {
        return [$this->float];
    }

    public function getType() {
        return TES5BasicType::T_FLOAT();
    }


    public function getValue() {
        return $this->float;
    }

} 