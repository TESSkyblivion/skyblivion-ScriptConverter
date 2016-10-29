<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;

use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunks;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5FunctionCodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\Converter\TES5AdditionalBlockChangesPass;
use Ormin\OBSLexicalParser\TES5\Other\TES5FragmentType;
use Ormin\OBSLexicalParser\TES5\Types\TES5VoidType;

class TES5FragmentFactory
{

    /**
     * @var TES5ChainedCodeChunkFactory
     */
    private $codeChunkFactory;

    /**
     * @var TES5BlockLocalScopeFactory
     */
    private $fragmentLocalScopeFactory;

    /**
     * @var TES5CodeScopeFactory
     */
    private $codeScopeFactory;

    /**
     * @var TES5AdditionalBlockChangesPass
     */
    private $changesPass;

    public function __construct(TES5ChainedCodeChunkFactory $chainedCodeChunkFactory,
                                TES5FragmentLocalScopeFactory $fragmentLocalScopeFactory,
                                TES5CodeScopeFactory $codeScopeFactory,
                                TES5AdditionalBlockChangesPass $changesPass) {

        $this->codeChunkFactory = $chainedCodeChunkFactory;
        $this->fragmentLocalScopeFactory = $fragmentLocalScopeFactory;
        $this->codeScopeFactory = $codeScopeFactory;
        $this->changesPass = $changesPass;
    }

    /**
     * @param TES5FragmentType $fragmentType
     * @param string $fragmentName
     * @param TES5GlobalScope $globalScope
     * @param TES4CodeChunks $chunks
     * @return TES5FunctionCodeBlock
     */
    public function createFragment(TES5FragmentType $fragmentType, $fragmentName, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope, TES4CodeChunks $chunks)
    {


        $fragmentLocalScope = $this->fragmentLocalScopeFactory->createFromFragmentType($fragmentType);

        $function = new TES5FunctionCodeBlock($fragmentName, new TES5VoidType(), $fragmentLocalScope, $this->codeScopeFactory->createCodeScope($this->fragmentLocalScopeFactory->createRecursiveScope($fragmentLocalScope)));


        foreach($chunks->getCodeChunks() as $codeChunk) {

            $codeChunks = $this->codeChunkFactory->createCodeChunk($codeChunk, $function->getCodeScope(), $globalScope, $multipleScriptsScope);

            if($codeChunks !== null) {

                foreach($codeChunks as $newCodeChunk) {
                    $function->addChunk($newCodeChunk);
                }

            }

        }

        return $function;

    }

}