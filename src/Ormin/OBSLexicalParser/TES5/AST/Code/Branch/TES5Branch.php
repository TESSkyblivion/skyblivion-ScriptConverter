<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Code\Branch;


use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunk;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

class TES5Branch implements TES5CodeChunk{

    private $mainBranch;

    /**
     * @var TES5SubBranchList
     */
    private $elseifBranches;

    private $elseBranch;

    public function __construct(TES5SubBranch $mainBranch, TES5SubBranchList $elseifBranches = null, TES5ElseSubBranch $elseBranch = null) {
        $this->mainBranch = $mainBranch;
        $this->elseifBranches = $elseifBranches;
        $this->elseBranch = $elseBranch;
    }

    public function output() {

        $codeLines = [];
        $expressionOutput = $this->mainBranch->getExpression()->output();
        $expressionOutput = $expressionOutput[0];
        $codeLines[] = "If(".$expressionOutput.")";

        $codeLines = array_merge($codeLines,$this->mainBranch->getCodeScope()->output());

        if($this->elseifBranches !== null) {
            foreach($this->elseifBranches->getBranchList() as $branch) {
                $expressionOutput = $branch->getExpression()->output();
                $expressionOutput = $expressionOutput[0];
                $codeLines[] = "ElseIf(".$expressionOutput.")";
                $codeLines = array_merge($codeLines,$branch->getCodeScope()->output());
            }
        }

        if($this->elseBranch !== null) {
            $codeLines[] = "Else";
            $codeLines = array_merge($codeLines,$this->elseBranch->getCodeScope()->output());
        }

        $codeLines[] = "EndIf";

         return $codeLines;

    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Code\Branch\TES5ElseSubBranch
     */
    public function getElseBranch()
    {
        return $this->elseBranch;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Code\Branch\TES5SubBranchList
     */
    public function getElseifBranches()
    {
        return $this->elseifBranches;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Code\Branch\TES5SubBranch
     */
    public function getMainBranch()
    {
        return $this->mainBranch;
    }

} 