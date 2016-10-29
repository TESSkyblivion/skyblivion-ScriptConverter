<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall;


use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

class TES4FunctionArguments {

    /**
     * @var TES4Value[]
     */
    private $values = [];

    public function add(TES4Value $declaration) {
        $this->values[] = $declaration;
    }

    public function count() {
        return count($this->values);
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value[]
     */
    public function getValues()
    {
        return $this->values;
    }

    public function popValue($index) {
        $v = $this->values;
        $newValue = [];
        $toReturn = null;
        foreach($v as $k => $value) {
            if($k === $index) {
                $toReturn = $value;
            } else {
                $newValue[] = $value;
            }
        }

        if($toReturn === null) {
            throw new ConversionException("Cannot pop index ".$index);
        }

        $this->values = $newValue;

        return $toReturn;
    }

    public function getValue($i) {
        if(!isset($this->values[$i])) {
            return null;
        }

        return $this->values[$i];
    }

    public function setValue($i, TES4Value $value) {
        $this->values[$i] = $value;
    }

    public function filter(\Closure $c) {

        $filtered = [];
        foreach($this->values as $value) {
            $filtered = array_merge($filtered,$value->filter($c));
        }

        return $filtered;

    }


} 