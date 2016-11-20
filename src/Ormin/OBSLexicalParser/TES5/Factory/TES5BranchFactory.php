<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES4\AST\Code\Branch\TES4Branch;
use Ormin\OBSLexicalParser\TES4\AST\Code\Branch\TES4ElseSubBranch;
use Ormin\OBSLexicalParser\TES4\AST\Code\Branch\TES4SubBranch;
use Ormin\OBSLexicalParser\TES5\AST\Code\Branch\TES5Branch;
use Ormin\OBSLexicalParser\TES5\AST\Code\Branch\TES5ElseSubBranch;
use Ormin\OBSLexicalParser\TES5\AST\Code\Branch\TES5SubBranch;
use Ormin\OBSLexicalParser\TES5\AST\Code\Branch\TES5SubBranchList;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunkCollection;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Expression\TES5Expression;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5LocalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;

class TES5BranchFactory  {

    /**
     * @var TES5LocalScopeFactory
     */
    private $localScopeFactory;

    /**
     * @var TES5CodeScopeFactory
     */
    private $codeScopeFactory;

    /**
     * @var TES5ChainedCodeChunkFactory
     */
    private $codeChunkFactory;

    /**
     * @var TES5ValueFactory
     */
    private $valueFactory;

    public function __construct(TES5LocalScopeFactory $localScopeFactory, TES5CodeScopeFactory $codeScopeFactory, TES5ValueFactory $valueFactory) {
        $this->localScopeFactory = $localScopeFactory;
        $this->codeScopeFactory = $codeScopeFactory;
        $this->valueFactory = $valueFactory;
    }

    //UGLY but w/e PLEASE FIX THAT PLEASEE :((
    public function setCodeChunkFactory(TES5ChainedCodeChunkFactory $chainedCodeChunkFactory) {
        $this->codeChunkFactory = $chainedCodeChunkFactory;
    }

    public function createCodeChunk(TES4Branch $chunk, TES5CodeScope $codeScope, \Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope) {


        $mainBranch = $this->convertSubBranch($chunk->getMainBranch(), $codeScope, $globalScope, $multipleScriptsScope);

        $branchList = $chunk->getElseifBranches();
        $convertedElseIfBranches = null;

        if($branchList !== null) {
            $convertedElseIfBranches = new TES5SubBranchList();
            foreach($branchList->getSubBranches() as $subBranch) {
                $convertedElseIfBranches->add($this->convertSubBranch($subBranch, $codeScope, $globalScope, $multipleScriptsScope));
            }
        }

        $elseBranch = $chunk->getElseBranch();
        $convertedElseBranch = null;
        if($elseBranch !== null) {
            $convertedElseBranch = $this->convertElseBranch($elseBranch, $codeScope, $globalScope, $multipleScriptsScope);
        }

        $collection = new TES5CodeChunkCollection();
        $collection->add(new TES5Branch(
            $mainBranch,
            $convertedElseIfBranches,
            $convertedElseBranch
        ));

        return $collection;

    }

    public function createSimpleBranch(TES5Expression $expression, TES5LocalScope $parentScope) {
        return new TES5Branch(
            new TES5SubBranch($expression, $this->codeScopeFactory->createCodeScope($this->localScopeFactory->createRecursiveScope($parentScope)))
        );
    }

    private function convertElseBranch(TES4ElseSubBranch $branch, TES5CodeScope $outerCodeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope) {
        $outerLocalScope = $outerCodeScope->getLocalScope();

        $newScope = $this->localScopeFactory->createRecursiveScope($outerLocalScope);

        $newCodeScope = $this->codeScopeFactory->createCodeScope($newScope);

        $branchChunks = $branch->getCodeChunks();

        if($branchChunks !== null) {

            foreach ($branchChunks->getCodeChunks() as $codeChunk) {
                $codeChunks = $this->codeChunkFactory->createCodeChunk($codeChunk, $newCodeScope, $globalScope, $multipleScriptsScope);

                if($codeChunks !== null) {
                    foreach ($codeChunks as $newCodeChunk) {
                        $newCodeScope->add($newCodeChunk);
                    }
                }
            }

        }

        return new TES5ElseSubBranch(
            $newCodeScope
        );
    }

    /**
     * @param TES4SubBranch $branch
     * @param TES5CodeScope $outerCodeScope
     * @param TES5GlobalScope $globalScope
     * @return TES5SubBranch
     */
    private function convertSubBranch(TES4SubBranch $branch, TES5CodeScope $outerCodeScope, \Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {
        $outerLocalScope = $outerCodeScope->getLocalScope();

        $expression = $this->valueFactory->createValue($branch->getExpression(), $outerCodeScope, $globalScope, $multipleScriptsScope);

        $newScope = $this->localScopeFactory->createRecursiveScope($outerLocalScope);

        $newCodeScope = $this->codeScopeFactory->createCodeScope($newScope);

        $branchChunks = $branch->getCodeChunks();

        if($branchChunks !== null) {

            foreach ($branchChunks->getCodeChunks() as $codeChunk) {
                $codeChunks = $this->codeChunkFactory->createCodeChunk($codeChunk, $newCodeScope, $globalScope, $multipleScriptsScope);

                if($codeChunks !== null) {
                    foreach ($codeChunks as $newCodeChunk) {
                        $newCodeScope->add($newCodeChunk);
                    }
                }

            }

        }

        return new TES5SubBranch(
            $expression,
            $newCodeScope
        );
    }

} 