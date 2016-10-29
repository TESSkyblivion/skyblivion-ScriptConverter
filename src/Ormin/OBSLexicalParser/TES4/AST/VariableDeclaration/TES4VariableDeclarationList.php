<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\VariableDeclaration;


use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunk;

class TES4VariableDeclarationList implements TES4CodeChunk {

    /**
     * @var TES4VariableDeclaration[]
     */
    private $variableList = [];

    public function add(TES4VariableDeclaration $declaration) {
        $this->variableList[] = $declaration;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\VariableDeclaration\TES4VariableDeclaration[]
     */
    public function getVariableList()
    {
        return $this->variableList;
    }

    public function filter(\Closure $c) {
        $filtered = [];
        foreach($this->variableList as $variable) {
            if($c($variable)) {
                $filtered[] = $variable;
            }
        }

        return $filtered;
    }


}