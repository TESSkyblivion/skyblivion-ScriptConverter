<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Value\Primitive;


use Ormin\OBSLexicalParser\TES4\Types\TES4Type;

class TES4String implements TES4Primitive{

    private $data;

    public function __construct($data) {


        if(substr($data,0,1) == '"') {
            $data = substr($data,1);
        }

        if(substr($data,-1) == '"') {
            $data = substr($data,0,-1);
        }

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
        return TES4Type::T_STRING();
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