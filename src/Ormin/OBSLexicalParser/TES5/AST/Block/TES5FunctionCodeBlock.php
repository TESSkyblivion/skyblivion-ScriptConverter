<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Block;

use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunk;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5LocalScope;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

class TES5FunctionCodeBlock implements TES5CodeBlock {

    /**
     * @var string
     */
    private $functionName;

    /**
     * @var TES5CodeScope
     */
    private $codeScope;

    /**
     * @var TES5LocalScope
     */
    private $localScope;

    /**
     * @var TES5Type
     */
    private $returnType;

    public function __construct($functionName, TES5Type $returnType = null, TES5LocalScope $localScope, TES5CodeScope $chunks) {
        $this->functionName = $functionName;
        $this->localScope = $localScope;
        $this->codeScope = $chunks;
        $this->returnType = $returnType;
    }

    public function output() {

        $codeLines = [];

        $localScope = [];
        foreach($this->localScope->getLocalVariables() as $localVariable) {
            $localScope[] = $localVariable->getPropertyType()->output().' '.$localVariable->getPropertyName();
        }

        $localScope = '('.implode(', ',$localScope).')';

        $functionReturnType = ($this->returnType !== null) ? $this->returnType->value().' ' : "";

        $codeLines[] = $functionReturnType."Function ".$this->functionName.$localScope;

        $codeLines = array_merge($codeLines,$this->codeScope->output());

        $codeLines[] = "EndFunction";
        return $codeLines;
    }

    /**
     * @return string
     */
    public function getFunctionName()
    {
        return $this->functionName;
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