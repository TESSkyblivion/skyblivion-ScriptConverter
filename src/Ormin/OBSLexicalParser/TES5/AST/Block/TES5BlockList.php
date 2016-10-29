<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Block;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5EventCodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\TES5Outputtable;

class TES5BlockList implements TES5Outputtable {


    /**
     * @var TES5CodeBlock[]
     */
    private $blocks = [];

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Block\TES5CodeBlock[]
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    public function output() {
        $codeLines = [];
        foreach($this->blocks as $block) {
            $codeLines = array_merge($codeLines,$block->output());
        }

        return $codeLines;
    }

    public function add(TES5CodeBlock $block) {
        $this->blocks[] = $block;
    }

} 