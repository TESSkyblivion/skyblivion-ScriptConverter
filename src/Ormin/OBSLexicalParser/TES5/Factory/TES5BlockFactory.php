<?php

namespace Ormin\OBSLexicalParser\TES5\Factory;

use Ormin\OBSLexicalParser\TES5\AST\Block\TES5EventBlockList;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5EventCodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5FunctionScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES4\AST\Block\TES4CodeBlock;
use Ormin\OBSLexicalParser\TES5\Converter\TES5AdditionalBlockChangesPass;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

class TES5BlockFactory
{

    /**
     * @var TES5ChainedCodeChunkFactory
     */
    private $codeChunkFactory;

    /**
     * @var TES5BlockFunctionScopeFactory
     */
    private $blockFunctionScopeFactory;

    /**
     * @var TES5CodeScopeFactory
     */
    private $codeScopeFactory;

    /**
     * @var TES5AdditionalBlockChangesPass
     */
    private $changesPass;

    /**
     * @var TES5LocalScopeFactory
     */
    private $localScopeFactory;

    /**
     * @var TES5InitialBlockCodeFactory
     */
    private $initialBlockCodeFactory;

    public function __construct(TES5ChainedCodeChunkFactory $chainedCodeChunkFactory,
                                TES5BlockFunctionScopeFactory $blockFunctionScopeFactory,
                                TES5CodeScopeFactory $codeScopeFactory,
                                TES5AdditionalBlockChangesPass $changesPass,
                                TES5LocalScopeFactory $localScopeFactory,
                                TES5InitialBlockCodeFactory $initialBlockCodeFactory)
    {

        $this->codeChunkFactory = $chainedCodeChunkFactory;
        $this->blockFunctionScopeFactory = $blockFunctionScopeFactory;
        $this->codeScopeFactory = $codeScopeFactory;
        $this->changesPass = $changesPass;
        $this->localScopeFactory = $localScopeFactory;
        $this->initialBlockCodeFactory = $initialBlockCodeFactory;
    }

    private function mapBlockType($blockType)
    {

        switch (strtolower($blockType)) {

            case 'gamemode': {
                $newBlockType = "OnUpdate";
                break;
            }

            case 'onactivate': {
                $newBlockType = "OnActivate";
                break;
            }

            case 'oninit': {
                $newBlockType = "OnInit";
                break;
            }

            case 'onsell': {
                $newBlockType = "OnSell";
                break;
            }

            case 'ondeath': {
                $newBlockType = "OnDeath";
                break;
            }

            case 'onload': {
                $newBlockType = "OnLoad";
                break;
            }

            case 'onactorequip': {
                $newBlockType = "OnObjectEquipped";
                break;
            }

            case 'ontriggeractor': {
                $newBlockType = "OnTriggerEnter";
                break;
            }

            case 'onadd': {
                $newBlockType = "OnContainerChanged";
                break;
            }

            case 'onequip': {
                $newBlockType = "OnEquipped";
                break;
            }

            case 'onunequip': {
                $newBlockType = "OnUnequipped";
                break;
            }

            case 'ondrop': {
                $newBlockType = "OnContainerChanged";
                break;
            }

            case 'ontriggermob': {
                $newBlockType = "OnTriggerEnter";
                break;
            }

            case 'ontrigger': {
                $newBlockType = "OnTrigger";
                break;
            }

            case 'onhitwith': {
                $newBlockType = "OnHit";
                break;
            }

            case 'onhit': {
                $newBlockType = "OnHit";
                break;
            }

            case 'onalarm': {
                $newBlockType = "OnUpdate";
                break;
            }

            case 'onstartcombat': {
                $newBlockType = "OnCombatStateChanged";
                break;
            }

            case 'onpackagestart': {
                $newBlockType = "OnPackageStart";
                break;
            }

            case 'onpackagedone': {
                $newBlockType = "OnPackageEnd";
                break;
            }

            case 'onpackageend': {
                $newBlockType = "OnPackageEnd";
                break;
            }

            case 'onpackagechange': {
                $newBlockType = "OnPackageChange";
                break;
            }

            case 'onmagiceffecthit': {
                $newBlockType = "OnMagicEffectApply";
                break;
            }

            case 'onreset': {
                $newBlockType = "OnReset";
                break;
            }

            case 'scripteffectstart': {
                $newBlockType = "OnEffectStart";
                break;
            }

            case 'scripteffectupdate': {
                $newBlockType = "OnUpdate";
                break;
            }

            case 'scripteffectfinish': {
                $newBlockType = "OnEffectFinish";
                break;
            }

            default: {
                throw new ConversionException("Cannot find new block type out of " . $blockType);
            }


        }

        return $newBlockType;
    }

    public function createNewBlock($blockType, TES5FunctionScope $functionScope = null)
    {
        if ($functionScope === null) {
            $functionScope = new TES5FunctionScope($blockType);
        }
        $newBlock = new TES5EventCodeBlock($functionScope, $this->codeScopeFactory->createCodeScope($this->localScopeFactory->createRootScope($functionScope)));
        return $newBlock;
    }

    /**
     * @param TES5MultipleScriptsScope $multipleScriptsScope
     * @param TES5GlobalScope $globalScope
     * @param TES4CodeBlock $block
     * @return TES5EventBlockList
     */
    public function createBlock(TES5MultipleScriptsScope $multipleScriptsScope, TES5GlobalScope $globalScope, TES4CodeBlock $block)
    {

        $blockList = new TES5EventBlockList();
        $blockType = $block->getBlockType();

        if (strtolower($blockType) == "menumode") {
            return $blockList;
        }

        $newBlockType = $this->mapBlockType($blockType);
        $blockFunctionScope = $this->blockFunctionScopeFactory->createFromBlockType($newBlockType);

        $newBlock = $this->createNewBlock($newBlockType, $blockFunctionScope);

        $conversionScope = $this->initialBlockCodeFactory->addInitialCode($multipleScriptsScope, $globalScope, $newBlock);
        if ($block->getChunks() !== null) {

            foreach ($block->getChunks()->getCodeChunks() as $codeChunk) {

                $codeChunks = $this->codeChunkFactory->createCodeChunk($codeChunk, $newBlock->getCodeScope(), $globalScope, $multipleScriptsScope);

                if ($codeChunks !== null) {

                    foreach ($codeChunks as $newCodeChunk) {
                        $conversionScope->add($newCodeChunk);
                    }

                }

            }

            $this->changesPass->modify($block, $blockList, $newBlock, $globalScope, $multipleScriptsScope);
            $blockList->add($newBlock);

        }
        return $blockList;

    }

}