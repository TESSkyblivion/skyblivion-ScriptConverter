<?php

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES4\AST\Code\Branch\TES4Branch;
use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunk;
use Ormin\OBSLexicalParser\TES4\AST\Code\TES4Return;
use Ormin\OBSLexicalParser\TES4\AST\Code\TES4VariableAssignation;
use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4Callable;
use Ormin\OBSLexicalParser\TES4\AST\VariableDeclaration\TES4VariableDeclarationList;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

class TES5ChainedCodeChunkFactory implements TES5CodeChunkFactory
{

    /**
     * @var TES5BranchFactory
     */
    private $branchFactory;

    /**
     * @var TES5VariableAssignationConversionFactory
     */
    private $assignationFactory;

    /**
     * @var TES5ReturnFactory
     */
    private $returnFactory;

    /**
     * @var TES5ValueFactory
     */
    private $objectCallFactory;

    /**
     * @var TES5LocalVariableListFactory
     */
    private $localVariableListFactory;

    public function __construct(TES5ValueFactory $objectCallFactory, TES5ReturnFactory $returnFactory, TES5VariableAssignationConversionFactory $assignationFactory, TES5BranchFactory $branchFactory, TES5LocalVariableListFactory $localVariableListFactory)
    {
        $this->objectCallFactory = $objectCallFactory;
        $this->returnFactory = $returnFactory;
        $this->assignationFactory = $assignationFactory;
        $this->branchFactory = $branchFactory;
        $this->localVariableListFactory = $localVariableListFactory;
        $branchFactory->setCodeChunkFactory($this); //ugly!!!
    }

    public function createCodeChunk(TES4CodeChunk $chunk, TES5CodeScope $codeScope, \Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope $globalScope, \Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope $multipleScriptsScope)
    {

        switch (true) {

            case $chunk instanceof TES4Branch:
            {
                $codeChunks = $this->branchFactory->createCodeChunk($chunk, $codeScope, $globalScope, $multipleScriptsScope);
                break;
            }

            case $chunk instanceof TES4Return:
            {
                $codeChunks = $this->returnFactory->createCodeChunk($codeScope->getLocalScope()->getFunctionScope(), $globalScope, $multipleScriptsScope);
                break;
            }

            case $chunk instanceof TES4Callable:
            {
                $codeChunks = $this->objectCallFactory->createCodeChunk($chunk, $codeScope, $globalScope, $multipleScriptsScope);
                break;
            }


            case $chunk instanceof TES4VariableAssignation:
            {
                $codeChunks = $this->assignationFactory->createCodeChunk($chunk, $codeScope, $globalScope, $multipleScriptsScope);
                break;
            }

            case $chunk instanceof TES4VariableDeclarationList: {
                $codeChunks = $this->localVariableListFactory->createCodeChunk($chunk, $codeScope);
                break;
            }

            default: {
                throw new ConversionException("Cannot convert a chunk: ".get_class($chunk));
            }

        }

        return $codeChunks;

    }

} 