<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Value;


interface TES4Value {

    /**
     * Get the value representation
     * @return mixed
     */
    public function getData();

    /**
     * Is this value related to execution or not?
     * @return bool
     */
    public function hasFixedValue();


    public function filter(\Closure $c);

} 