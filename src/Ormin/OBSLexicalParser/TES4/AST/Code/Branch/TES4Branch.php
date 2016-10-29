<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Code\Branch;


use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunk;

class TES4Branch implements TES4CodeChunk{

    private $mainBranch;

    private $elseifBranches;

    private $elseBranch;

    public function __construct(TES4SubBranch $mainBranch, TES4SubBranchList $elseifBranches = null, TES4ElseSubBranch $elseBranch = null) {
        $this->mainBranch = $mainBranch;
        $this->elseifBranches = $elseifBranches;
        $this->elseBranch = $elseBranch;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Code\Branch\TES4ElseSubBranch
     */
    public function getElseBranch()
    {
        return $this->elseBranch;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Code\Branch\TES4SubBranchList
     */
    public function getElseifBranches()
    {
        return $this->elseifBranches;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Code\Branch\TES4SubBranch
     */
    public function getMainBranch()
    {
        return $this->mainBranch;
    }



    public function filter(\Closure $c) {
        $filtered = $this->mainBranch->filter($c);

        if($this->elseifBranches !== null) {
            foreach ($this->elseifBranches->getSubBranches() as $elseif) {

                $filtered = array_merge($filtered, $elseif->filter($c));

            }
        }

        if($this->elseBranch !== null) {
            $filtered = array_merge($filtered, $this->elseBranch->filter($c));
        }

        return $filtered;
    }

}
