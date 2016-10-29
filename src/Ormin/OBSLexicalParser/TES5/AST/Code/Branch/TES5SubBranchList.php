<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Code\Branch;


class TES5SubBranchList {

    /**
     * @var TES5SubBranch[]
     */
    private $branchList = [];

    public function add(TES5SubBranch $declaration) {
        $this->branchList[] = $declaration;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Code\Branch\TES5SubBranch[]
     */
    public function getBranchList()
    {
        return $this->branchList;
    }



} 