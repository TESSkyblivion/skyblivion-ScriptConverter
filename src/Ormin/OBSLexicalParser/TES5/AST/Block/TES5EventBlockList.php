<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Block;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5EventCodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\TES5Outputtable;

class TES5EventBlockList {


    /**
     * @var TES5CodeBlock[]
     */
    private $blocks = [];

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Block\TES5EventCodeBlock[]
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    public function add(TES5EventCodeBlock $block) {
        $this->blocks[] = $block;
    }

} 