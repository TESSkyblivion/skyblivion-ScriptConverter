<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Block;

class TES4BlockList {


    /**
     * @var TES4CodeBlock[]
     */
    private $blocks = [];

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Block\TES4CodeBlock[]
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    public function add(TES4CodeBlock $block) {
        $this->blocks[] = $block;
    }

    public function filter(\Closure $c) {

        $filtered = [];

        foreach($this->blocks as $block) {
            $filtered = array_merge($filtered, $block->filter($c));
        }

        return $filtered;
    }


} 