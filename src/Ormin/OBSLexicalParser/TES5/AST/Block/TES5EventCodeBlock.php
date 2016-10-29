<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Block;


use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunk;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5LocalScope;
use Ormin\OBSLexicalParser\TES5\AST\TES5Outputtable;

class TES5EventCodeBlock implements TES5CodeBlock {


    /**
     * @var string
     */
    private $blockType;

    /**
     * @var TES5CodeScope
     */
    private $codeScope;

    /**
     * @var TES5LocalScope
     */
    private $localScope;

    public function __construct($blockType, TES5LocalScope $localScope, TES5CodeScope $chunks) {
        $this->blockType = $blockType;
        $this->localScope = $localScope;
        $this->codeScope = $chunks;
    }

    public function output() {

        $codeLines = [];

        $localScope = [];
        foreach($this->localScope->getLocalVariables() as $localVariable) {
            $localScope[] = $localVariable->getPropertyType()->output().' '.$localVariable->getPropertyName();
        }

        $localScope = '('.implode(', ',$localScope).')';
        $codeLines[] = "Event ".$this->blockType.$localScope;

        $codeLines = array_merge($codeLines,$this->codeScope->output());

        $codeLines[] = "EndEvent";
        return $codeLines;
    }

    /**
     * @return string
     */
    public function getBlockType()
    {
        return $this->blockType;
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
     * @return \Ormin\OBSLexicalParser\TES5\AST\Scope\TES5LocalScope
     */
    public function getLocalScope()
    {
        return $this->localScope;
    }




} 