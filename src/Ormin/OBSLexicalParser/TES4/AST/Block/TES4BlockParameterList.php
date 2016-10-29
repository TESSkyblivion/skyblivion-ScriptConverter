<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Block;


class TES4BlockParameterList {

    /**
     * @var TES4BlockParameter[]
     */
    private $variableList = [];

    public function add(TES4BlockParameter $declaration) {
        $this->variableList[] = $declaration;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Block\TES4BlockParameter[]
     */
    public function getVariableList()
    {
        return $this->variableList;
    }

    public function filter(\Closure $c) {

        $filtered = [];

        foreach($this->variableList as $variable) {
            if($c($variable)) {
                $filtered = $variable;
            }
        }

        return $filtered;
    }


} 