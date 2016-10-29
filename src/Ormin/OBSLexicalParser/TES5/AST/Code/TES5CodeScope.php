<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Code;

use Ormin\OBSLexicalParser\TES5\AST\Code\Branch\TES5Branch;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5LocalScope;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5LocalVariable;
use Ormin\OBSLexicalParser\TES5\AST\TES5Outputtable;
use Ormin\OBSLexicalParser\TES5\Context\TES5LocalVariableParameterMeaning;

class TES5CodeScope implements TES5Outputtable {

    /**
     * @var TES5CodeChunk[]
     */
    private $codeChunks = [];

    /**
     * @var TES5LocalScope
     */
    private $localScope;

    public function __construct(TES5LocalScope $localScope) {
        $this->localScope = $localScope;
    }

    public function output() {
        $codeLines = $this->localScope->output();

        foreach($this->codeChunks as $codeChunk) {
            $codeLines = array_merge($codeLines,$codeChunk->output());
        }

        return $codeLines;

    }

    public function clear() {
        $this->codeChunks = [];
    }

    public function add(TES5CodeChunk $chunk) {
        $this->codeChunks[] = $chunk;
    }

    public function addVariable(TES5LocalVariable $localVariable) {
        $this->localScope->addVariable($localVariable);
    }


    public function getLocalScope() {
        return $this->localScope;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Property\TES5LocalVariable[]
     */
    public function getVariables()
    {
        return $this->localScope->getVariables();
    }


    public function findVariableWithMeaning(TES5LocalVariableParameterMeaning $meaning) {
        return $this->localScope->findVariableWithMeaning($meaning);
    }


    /**
     * @return mixed
     */
    public function getCodeChunks()
    {
        return $this->codeChunks;
    }

    /**
     * @param \Ormin\OBSLexicalParser\TES5\AST\Scope\TES5LocalScope $localScope
     */
    public function setLocalScope($localScope)
    {
        $this->localScope = $localScope;
    }



} 