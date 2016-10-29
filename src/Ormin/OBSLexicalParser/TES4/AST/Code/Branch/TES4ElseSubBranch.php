<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Code\Branch;


use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunks;

class TES4ElseSubBranch
{

    /**
     * @var TES4CodeChunks
     */
    private $codeChunks;

    public function __construct(TES4CodeChunks $codeChunks = null)
    {
        $this->codeChunks = $codeChunks;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunks
     */
    public function getCodeChunks()
    {
        return $this->codeChunks;
    }


    public function filter(\Closure $c)
    {
        $filtered = [];

        if ($this->codeChunks !== null) {
            foreach ($this->codeChunks->getCodeChunks() as $codeChunk) {
                $filtered = array_merge($filtered, $codeChunk->filter($c));
            }
        }

        return $filtered;
    }


}