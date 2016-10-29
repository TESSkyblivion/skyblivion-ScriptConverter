<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Code;


class TES4CodeChunks {

    /**
     * @var TES4CodeChunk[]
     */
    private $codeChunks;

    public function add(TES4CodeChunk $chunk) {
        $this->codeChunks[] = $chunk;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunk[]
     */
    public function getCodeChunks()
    {
        return $this->codeChunks;
    }

    public function filter(\Closure $c) {

        $filtered = [];
        foreach($this->codeChunks as $codeChunk) {

            $filtered = array_merge($filtered, $codeChunk->filter($c));

        }

        return $filtered;

    }


} 