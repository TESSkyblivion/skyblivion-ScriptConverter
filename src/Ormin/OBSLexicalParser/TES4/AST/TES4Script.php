<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST;

use Ormin\OBSLexicalParser\TES4\AST\Block\TES4BlockList;
use Ormin\OBSLexicalParser\TES4\AST\VariableDeclaration\TES4VariableDeclarationList;

class TES4Script {

    /**
     * @var TES4ScriptHeader
     */
    private $scriptHeader;

    /**
     * @var TES4VariableDeclarationList
     */
    private $variableDeclarationList;

    /**
     * @var TES4BlockList
     */
    private $blockList;



    public function __construct(TES4ScriptHeader $scriptHeader, TES4VariableDeclarationList $declarationList = null, TES4BlockList $blockList = null) {
        $this->scriptHeader = $scriptHeader;
        $this->variableDeclarationList = $declarationList;
        $this->blockList = $blockList;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Block\TES4BlockList
     */
    public function getBlockList()
    {
        return $this->blockList;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\TES4ScriptHeader
     */
    public function getScriptHeader()
    {
        return $this->scriptHeader;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\VariableDeclaration\TES4VariableDeclarationList
     */
    public function getVariableDeclarationList()
    {
        return $this->variableDeclarationList;
    }

    public function filter(\Closure $c) {
        $variableDeclarationList = [];
        $blockList = [];

        if($this->variableDeclarationList !== null) {
            $variableDeclarationList = $this->variableDeclarationList->filter($c);
        }


        if($this->blockList !== null) {
            $blockList = $this->blockList->filter($c);
        }

        return array_merge($variableDeclarationList, $blockList);
    }



} 