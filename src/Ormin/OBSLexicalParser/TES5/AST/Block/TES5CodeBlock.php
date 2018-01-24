<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Block;


use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunk;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5FunctionScope;
use Ormin\OBSLexicalParser\TES5\AST\TES5Outputtable;

interface TES5CodeBlock extends TES5Outputtable{

    /**
     * @return TES5CodeScope
     */
    public function getCodeScope();

    /**
     * @return TES5FunctionScope
     */
    public function getFunctionScope();

    /**
     * @param TES5CodeChunk $chunk
     * @return void
     */
    public function addChunk(TES5CodeChunk $chunk);

} 