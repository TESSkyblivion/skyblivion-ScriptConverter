<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Block;


use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunks;

class TES4CodeBlock {

    /**
     * @var string
     */
    private $blockType;

    /**
     * @var TES4CodeChunks
     */
    private $chunks;

    /**
     * @var TES4BlockParameterList
     */
    private $blockParameterList;

    public function __construct($blockType, TES4BlockParameterList $blockParameterList = null, TES4CodeChunks $chunks = null) {
        $this->blockType = $blockType;
        $this->blockParameterList = $blockParameterList;
        $this->chunks = $chunks;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Block\TES4BlockParameterList
     */
    public function getBlockParameterList()
    {
        return $this->blockParameterList;
    }

    /**
     * @return string
     */
    public function getBlockType()
    {
        return $this->blockType;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunks
     */
    public function getChunks()
    {
        return $this->chunks;
    }

    public function filter(\Closure $c)
    {
        $bpl = [];
        $chunks = [];

        if($this->blockParameterList !== null) {
            $bpl = $this->blockParameterList->filter($c);
        }

        if($this->chunks !== null) {
            $chunks = $this->chunks->filter($c);
        }

        return array_merge($bpl, $chunks);
    }

}