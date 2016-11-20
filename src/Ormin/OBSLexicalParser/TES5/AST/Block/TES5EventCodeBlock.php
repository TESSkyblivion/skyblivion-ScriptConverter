<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Block;


use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunk;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5FunctionScope;

class TES5EventCodeBlock implements TES5CodeBlock {

    /**
     * @var TES5CodeScope
     */
    private $codeScope;

    /**
     * @var TES5FunctionScope
     */
    private $functionScope;

    public function __construct(TES5FunctionScope $functionScope, TES5CodeScope $chunks) {
        $this->functionScope = $functionScope;
        $this->codeScope = $chunks;
    }

    public function output() {

        $codeLines = [];

        $functionSignatureFlat = [];
        foreach($this->functionScope->getVariables() as $localVariable) {
            $functionSignatureFlat[] = $localVariable->getPropertyType()->output().' '.$localVariable->getPropertyName();
        }

        $functionSignature = implode(', ',$functionSignatureFlat);
        $codeLines[] = "Event ".$this->functionScope->getBlockName().'('.$functionSignature.')';

        $codeLines = array_merge($codeLines,$this->codeScope->output());

        $codeLines[] = "EndEvent";
        return $codeLines;
    }

    /**
     * @return string
     */
    public function getBlockType()
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

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Scope\TES5FunctionScope
     */
    public function getFunctionScope()
    {
        return $this->functionScope;
    }




} 