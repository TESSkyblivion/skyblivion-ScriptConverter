<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Value;


class TES4ApiToken implements TES4Reference {

    private $token;

    public function __construct($token) {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->token;
    }

    public function hasFixedValue() {
        return true;
    }

    public function filter(\Closure $c) {

        if($c($this)) {
            return [$this];
        }

        return [];

    }

} 