<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunk;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5EventCodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunkCollection;

interface TES5CodeChunkFactory {

    /**
     * @param TES4CodeChunk $chunk
     * @param \Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope $codeScope
     * @param \Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope $globalScope
     * @param \Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope $multipleScriptsScope
     * @return TES5CodeChunkCollection
     * @internal param \Ormin\OBSLexicalParser\TES5\AST\Block\TES5CodeBlock $block
     */
    public function createCodeChunk(TES4CodeChunk $chunk, TES5CodeScope $codeScope, \Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope $globalScope, \Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope $multipleScriptsScope);

} 