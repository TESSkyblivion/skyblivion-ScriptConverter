<?php

namespace Ormin\OBSLexicalParser\TES5\AST\Code;


use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5None;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;

class TES5VariableAssignation  implements TES5CodeChunk {

    /**
     * @var \Ormin\OBSLexicalParser\TES5\AST\Object\TES5Reference
     */
    private $reference;

    /**
     * @var \Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value
     */
    private $value;

    public function __construct(TES5Referencer $reference, TES5Value $value) {
        $this->reference = $reference;
        $this->value = $value;
    }

    public function output() {

        $referenceOutput = $this->reference->output();
        $referenceOutput = $referenceOutput[0];
        $valueOutput = $this->value->output();
        $valueOutput = $valueOutput[0];
        $code = $referenceOutput.' = '.$valueOutput;

        if($this->reference->getType() != $this->value->getType() && !$this->value instanceof TES5None) {
            $code .= " as ".$this->reference->getType()->output();
        }

        $codeLines = [$code];
        return $codeLines;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Object\TES5Reference
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value
     */
    public function getValue()
    {
        return $this->value;
    }



} 