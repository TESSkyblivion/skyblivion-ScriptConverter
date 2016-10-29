<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Value;


use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5ConcatenatedValue implements TES5Value {

    /**
     * @var TES5Value[]
     */
    private $concatenatedValues;

    /**
     * @param TES5Value[] $concatenatedValues
     */
    public function __construct(array $concatenatedValues) {
        $this->concatenatedValues = $concatenatedValues;
    }

    public function getType() {
        return TES5BasicType::T_STRING(); //concatenated value is always a string ( what else.. :) )
    }

    public function output() {

        $outputs = [];

        foreach($this->concatenatedValues as $concatValue) {

            $outputs[] = implode('',$concatValue->output());

        }

        return [implode(' + ', $outputs)];

    }

} 