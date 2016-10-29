<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Value\Primitive;


use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5String implements TES5Primitive{

    private $string;

    function __construct($string)
    {
        if(substr($string,0,1) != '"') {
            $string = '"'.$string;
        }

        if(substr($string,-1) != '"') {
            $string .= '"';
        }

        $this->string = $string;
    }

    public function output() {
        return [$this->string];
    }

    public function getType() {
        return TES5BasicType::T_STRING();
    }

    public function getValue() {
        return $this->string;
    }

} 