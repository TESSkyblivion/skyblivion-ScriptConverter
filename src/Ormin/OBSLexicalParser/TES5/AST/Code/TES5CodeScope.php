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

/**
 * TES5CodeScope describes scope for given chunks of code. It consists of its variable local scope and chunks that
 * are put inside this code scope.
 * Class TES5CodeScope
 * @package Ormin\OBSLexicalParser\TES5\AST\Code
 */
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

} 