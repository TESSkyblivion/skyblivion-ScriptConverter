<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Block;

use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunk;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5FunctionScope;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

class TES5FunctionCodeBlock implements TES5CodeBlock {

    /**
     * @var TES5CodeScope
     */
    private $codeScope;

    /**
     * @var TES5FunctionScope
     */
    private $functionScope;

    /**
     * @var TES5Type
     */
    private $returnType;

    public function __construct(TES5Type $returnType = null, TES5FunctionScope $functionScope, TES5CodeScope $chunks) {
        $this->functionScope = $functionScope;
        $this->codeScope = $chunks;
        $this->returnType = $returnType;
    }

    public function output() {

        $codeLines = [];

        $functionSignatureFlat = [];
        foreach($this->functionScope->getVariables() as $localVariable) {
            $functionSignatureFlat[] = $localVariable->getPropertyType()->output().' '.$localVariable->getPropertyName();
        }

        $functionSignature = implode(', ',$functionSignatureFlat);

        $functionReturnType = ($this->returnType !== null) ? $this->returnType->value().' ' : "";

        $codeLines[] = $functionReturnType."Function ".$this->functionScope->getBlockName().'('.$functionSignature.')';

        $codeLines = array_merge($codeLines,$this->codeScope->output());

        $codeLines[] = "EndFunction";
        return $codeLines;
    }

    /**
     * @return string
     */
    public function getFunctionName()
    {
        return $this->functionScope->getBlockName();
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope
     */
    public function getCodeScope()
    {
        return $this->codeScope;
    }

    /**
     * @param \Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope $codeScope
     */
    public function setCodeScope($codeScope)
    {
        $this->codeScope = $codeScope;
    }

    public function addChunk(TES5CodeChunk $chunk) {
        $this->codeScope->add($chunk);
    }

    public function getFunctionScope() {
        return $this->functionScope;
    }

}