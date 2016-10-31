<?php

namespace Ormin\OBSLexicalParser\TES5\Factory;

use Ormin\OBSLexicalParser\TES5\AST\Block\TES5EventBlockList;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5EventCodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5LocalScope;
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
     * @var TES5BlockLocalScopeFactory
     */
    private $blockLocalScopeFactory;

    /**
     * @var TES5CodeScopeFactory
     */
    private $codeScopeFactory;

    /**
     * @var TES5AdditionalBlockChangesPass
     */
    private $changesPass;

    public function __construct(TES5ChainedCodeChunkFactory $chainedCodeChunkFactory,
                                TES5BlockLocalScopeFactory $blockLocalScopeFactory,
                                TES5CodeScopeFactory $codeScopeFactory,
                                TES5AdditionalBlockChangesPass $changesPass)
    {

        $this->codeChunkFactory = $chainedCodeChunkFactory;
        $this->blockLocalScopeFactory = $blockLocalScopeFactory;
        $this->codeScopeFactory = $codeScopeFactory;
        $this->changesPass = $changesPass;
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

    public function createNewBlock($blockType, TES5LocalScope $localScope = null)
    {

        if ($localScope === null) {
            $localScope = new TES5LocalScope();
        } else {
            if ($localScope->getParentScope() !== null) {
                throw new ConversionException("TES5BlockFactory::createNewBlock - Local scope created must be the root, cannot be nested.");
            }
        }

        $newBlock = new TES5EventCodeBlock($blockType, $localScope, $this->codeScopeFactory->createCodeScope($this->blockLocalScopeFactory->createRecursiveScope($localScope)));
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
        $blockLocalScope = $this->blockLocalScopeFactory->createFromBlockType($newBlockType);

        $newBlock = new TES5EventCodeBlock($newBlockType, $blockLocalScope, $this->codeScopeFactory->createCodeScope($this->blockLocalScopeFactory->createRecursiveScope($blockLocalScope)));

        if ($block->getChunks() !== null) {

            foreach ($block->getChunks()->getCodeChunks() as $codeChunk) {

                $codeChunks = $this->codeChunkFactory->createCodeChunk($codeChunk, $newBlock->getCodeScope(), $globalScope, $multipleScriptsScope);

                if ($codeChunks !== null) {

                    foreach ($codeChunks as $newCodeChunk) {
                        $newBlock->addChunk($newCodeChunk);
                    }

                }

            }

            $this->changesPass->modify($block, $blockList, $newBlock, $globalScope, $multipleScriptsScope);
            $blockList->add($newBlock);

        }
        return $blockList;

    }

}