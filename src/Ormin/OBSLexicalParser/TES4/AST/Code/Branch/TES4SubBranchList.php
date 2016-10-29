<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Code\Branch;


class TES4SubBranchList {

    /**
     * @var TES4SubBranch[]
     */
    private $variableList = [];

    public function add(TES4SubBranch $declaration) {
        $this->variableList[] = $declaration;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Code\Branch\TES4SubBranch[]
     */
    public function getSubBranches()
    {
        return $this->variableList;
    }




} 