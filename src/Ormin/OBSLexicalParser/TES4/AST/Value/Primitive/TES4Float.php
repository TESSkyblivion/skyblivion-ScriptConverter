<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Value\Primitive;


use Ormin\OBSLexicalParser\TES4\Types\TES4Type;

class TES4Float implements TES4Primitive {

    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }


    public function getType() {
        return TES4Type::T_FLOAT();
    }


    public function hasFixedValue() {
        return true;
    }

    public function filter(\Closure $c)
    {
        if($c($this)) {
            return [$this];
        }

        return [];
    }

}