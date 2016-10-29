<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */
namespace Ormin\OBSLexicalParser\TES4\AST\Code;


use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Reference;
use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value;

class TES4VariableAssignation  implements TES4CodeChunk {

    private $reference;

    private $value;

    public function __construct(TES4Reference $reference, TES4Value $value) {
        $this->reference = $reference;
        $this->value = $value;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Value\TES4Reference
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value
     */
    public function getValue()
    {
        return $this->value;
    }


    public function filter(\Closure $c) {
        $filtered = [];
        if($c($this->reference)) {
            $filtered[] = $this->reference;
        }

        $filtered = array_merge($filtered, $this->value->filter($c));

        return $filtered;
    }

} 