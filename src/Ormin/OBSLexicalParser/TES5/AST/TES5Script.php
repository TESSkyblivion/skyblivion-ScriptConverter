<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST;


use Ormin\OBSLexicalParser\TES5\AST\Block\TES5BlockList;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;

class TES5Script implements TES5Outputtable {


    /**
     * @var TES5ScriptHeader
     */
    private $scriptHeader;

    /**
     * @var TES5GlobalScope
     */
    private $propertyList;

    /**
     * @var TES5BlockList
     */
    private $blockList;

    public function __construct(TES5GlobalScope $globalScope, TES5BlockList $blockList = null) {
        $this->scriptHeader = $globalScope->getScriptHeader();
        $this->propertyList = $globalScope;
        $this->blockList = $blockList;
    }

    public function output() {
        $output = [];
        $output = array_merge($output,$this->scriptHeader->output());
        $output = array_merge($output,$this->propertyList->output());
        $output = array_merge($output,$this->blockList->output());
        return implode(PHP_EOL,$output);
    }

    /**
     * @return TES5BlockList
     */
    public function getBlockList()
    {
        return $this->blockList;
    }

    /**
     * @return TES5ScriptHeader
     */
    public function getScriptHeader()
    {
        return $this->scriptHeader;
    }

    /**
     * @return TES5GlobalScope
     */
    public function getGlobalScope()
    {
        return $this->propertyList;
    }


} 