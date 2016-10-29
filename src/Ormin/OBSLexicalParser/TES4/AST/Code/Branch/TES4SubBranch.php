<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Code\Branch;


use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunks;
use Ormin\OBSLexicalParser\TES4\AST\Expression\TES4Expression;
use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value;

class TES4SubBranch {

    /**
     * @var \Ormin\OBSLexicalParser\TES4\AST\Expression\TES4Expression
     */
    private $expression;


    /**
     * @var \Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunks
     */
    private $codeChunks;

    public function __construct(TES4Value $expression, TES4CodeChunks $codeChunks = null) {

        $this->expression = $expression;
        $this->codeChunks = $codeChunks;

    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunks
     */
    public function getCodeChunks()
    {
        return $this->codeChunks;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Expression\TES4Expression
     */
    public function getExpression()
    {
        return $this->expression;
    }


    public function filter(\Closure $c)
    {
        $filtered = $this->expression->filter($c);

        if($this->codeChunks !== null) {
            foreach ($this->codeChunks->getCodeChunks() as $codeChunk) {

                $filtered = array_merge($filtered, $codeChunk->filter($c));
            }
        }

        return $filtered;
    }

}