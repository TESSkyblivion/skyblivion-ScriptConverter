<?php

namespace Ormin\OBSLexicalParser\TES5\Converter;

use Ormin\OBSLexicalParser\TES4\AST\TES4Script;
use Ormin\OBSLexicalParser\TES4\AST\TES4ScriptCollection;
use Ormin\OBSLexicalParser\TES4\AST\TES4Target;
use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5BlockList;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5EventCodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5FunctionCodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCallArguments;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\TES5Script;
use Ormin\OBSLexicalParser\TES5\AST\TES5ScriptCollection;
use Ormin\OBSLexicalParser\TES5\AST\TES5ScriptHeader;
use Ormin\OBSLexicalParser\TES5\Factory\TES5BlockFactory;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Factory\TES5PropertiesFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ReferenceFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5StaticGlobalScopesFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ValueFactory;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;

class TES4ToTES5ASTConverter
{

    private $scriptsPrefix = "TES4";

    /**
     * @var ESMAnalyzer
     * Oblivion binary data analyzer.
     */
    private $esmAnalyzer;

    /**
     * @var TES5BlockFactory
     */
    private $blockFactory;

    /**
     * @var \Ormin\OBSLexicalParser\TES5\Factory\TES5ValueFactory
     */
    private $valueFactory;

    /**
     * @var \Ormin\OBSLexicalParser\TES5\Factory\TES5ReferenceFactory
     */
    private $referenceFactory;

    /**
     * @var \Ormin\OBSLexicalParser\TES5\Factory\TES5PropertiesFactory
     */
    private $propertiesFactory;

    /**
     * @var TES5StaticGlobalScopesFactory
     */
    private $staticGlobalScopesFactory;

    /**
     * @var TES5NameTransformer
     */
    private $nameTransformer;

    public function __construct(ESMAnalyzer $ESMAnalyzer, TES5BlockFactory $blockFactory, TES5ValueFactory $valueFactory, TES5ReferenceFactory $referenceFactory, TES5PropertiesFactory $propertiesFactory, TES5StaticGlobalScopesFactory $staticGlobalScopesFactory, TES5NameTransformer $nameTransformer)
    {
        $this->esmAnalyzer = $ESMAnalyzer;
        $this->blockFactory = $blockFactory;
        $this->valueFactory = $valueFactory;
        $this->referenceFactory = $referenceFactory;
        $this->propertiesFactory = $propertiesFactory;
        $this->staticGlobalScopesFactory = $staticGlobalScopesFactory;
        $this->nameTransformer = $nameTransformer;
    }

    /**
     * @param TES4Script $script
     * @return TES5ScriptHeader
     * @throws \Ormin\OBSLexicalParser\TES5\Exception\ConversionException
     */
    private function createHeader(TES4Script $script)
    {
        $edid = $script->getScriptHeader()->getScriptName();
        $scriptName = $this->nameTransformer->transform($edid, $this->scriptsPrefix);

        return new TES5ScriptHeader($scriptName, $edid, $this->esmAnalyzer->getScriptType($edid),$this->scriptsPrefix);
    }

    /**
     * @param TES4ScriptCollection $scripts
     * @return TES5ScriptCollection
     * @throws ConversionException
     */
    public function convert(TES4ScriptCollection $scripts)
    {

        $transpiledCollection = new TES5ScriptCollection();
        $scriptHeaders = [];
        $globalScopes = [];

        /**
         * @var TES4Target $scriptTarget
         */
        foreach ($scripts->getIterator() as $k => $scriptTarget) {
            $script = $scriptTarget->getScript();
            //Create the header.
            $scriptHeader = $this->createHeader($script);
            $variableList = $script->getVariableDeclarationList();

            $globalScope = new TES5GlobalScope($scriptHeader);

            foreach ($this->esmAnalyzer->getGlobalVariables() as $globalVariable) {
                $globalScope->addGlobalVariable($globalVariable);
            }

            if ($variableList !== null) {
                $this->propertiesFactory->createProperties($variableList, $globalScope);
            }

            $scriptHeaders[$k] = $scriptHeader;
            $globalScopes[$k] = $globalScope;
        }

        //Add the static global scopes which are added by complimenting scripts..
        $staticGlobalScopes = $this->staticGlobalScopesFactory->createGlobalScopes();
        foreach ($staticGlobalScopes as $staticGlobalScope) {
            $globalScopes[] = $staticGlobalScope;
        }

        $multipleScriptsScope = new TES5MultipleScriptsScope($globalScopes);

        /**
         * @var TES4Target $scriptTarget
         */
        foreach ($scripts->getIterator() as $k => $scriptTarget) {
            $scriptHeader = $scriptHeaders[$k];
            $globalScope = $globalScopes[$k];
            $script = $scriptTarget->getScript();
            $blockList = new TES5BlockList();
            $parsedBlockList = $script->getBlockList();
            $createdBlocks = [];
            if ($parsedBlockList !== null) {
                foreach ($parsedBlockList->getBlocks() as $block) {


                    $newBlockList = $this->blockFactory->createBlock($multipleScriptsScope, $globalScope, $block);

                    foreach ($newBlockList->getBlocks() as $newBlock) {

                        if (!isset($createdBlocks[$newBlock->getBlockType()])) {
                            $createdBlocks[$newBlock->getBlockType()] = [];
                        }

                        $createdBlocks[$newBlock->getBlockType()][] = $newBlock;

                    }
                }
            }

            //todo encapsulate it to a different class.

            foreach ($createdBlocks as $blockType => $blocks) {
                if (count($blocks) > 1) {


                    /**
                     * @var TES5FunctionCodeBlock[] $functions
                     */
                    $functions = [];
                    $i = 1;

                    $localScopeArguments = null;
                    /**
                     * @var TES5EventCodeBlock $block
                     */
                    foreach ($blocks as $block) {
                        $function = new TES5FunctionCodeBlock($blockType . "_" . $i, null, $block->getLocalScope(), $block->getCodeScope());
                        $functions[] = $function;

                        if ($localScopeArguments === null) {

                            $localScopeArguments = new TES5ObjectCallArguments();
                            foreach ($block->getLocalScope()->getLocalVariables() as $localVariable) {
                                $localScopeArguments->add($this->referenceFactory->createReferenceToVariable($localVariable));
                            }

                        }

                        ++$i;

                    }

                    //Create the proxy block.
                    $proxyBlock = $this->blockFactory->createNewBlock($blockType, clone $block->getLocalScope());

                    foreach ($functions as $function) {
                        $blockList->add($function);

                        $functionCall = $this->valueFactory->createObjectCall(
                            $this->referenceFactory->createReferenceToSelf($globalScope), $function->getFunctionName(), $multipleScriptsScope, $localScopeArguments, false // hacky.
                        );

                        $proxyBlock->addChunk(
                            $functionCall
                        );
                    }


                    $blockList->add($proxyBlock);

                } else {
                    $block = current($blocks);
                    $blockList->add($block);
                }
            }

            $tesScript = new TES5Script($scriptHeader, $globalScope, $blockList);
            $transpiledCollection->add($tesScript, $scriptTarget->getOutputPath());
        }

        return $transpiledCollection;
    }

} 