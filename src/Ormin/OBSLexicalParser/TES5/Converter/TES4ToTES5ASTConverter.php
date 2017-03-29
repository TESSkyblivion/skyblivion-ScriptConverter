<?php

namespace Ormin\OBSLexicalParser\TES5\Converter;

use Ormin\OBSLexicalParser\TES4\AST\TES4Target;
use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5BlockList;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5EventCodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5FunctionCodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCallArguments;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5FunctionScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\TES5Script;
use Ormin\OBSLexicalParser\TES5\AST\TES5Target;
use Ormin\OBSLexicalParser\TES5\Factory\TES5BlockFactory;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectCallFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5PropertiesFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ReferenceFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5StaticGlobalScopesFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ValueFactory;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;

class TES4ToTES5ASTConverter
{

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
     * @var \Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectCallFactory
     */
    private $objectCallFactory;

    /**
     * @var \Ormin\OBSLexicalParser\TES5\Factory\TES5ReferenceFactory
     */
    private $referenceFactory;

    public function __construct(ESMAnalyzer $ESMAnalyzer, TES5BlockFactory $blockFactory, TES5ObjectCallFactory $objectCallFactory, TES5ReferenceFactory $referenceFactory)
    {
        $this->esmAnalyzer = $ESMAnalyzer;
        $this->blockFactory = $blockFactory;
        $this->objectCallFactory = $objectCallFactory;
        $this->referenceFactory = $referenceFactory;
    }

    /**
     * @param TES4Target $target The script to be converted
     * @param TES5GlobalScope $globalScope The script's global scope
     * @param TES5MultipleScriptsScope $multipleScriptsScope The scope under which we're converting
     * @return TES5Target
     * @throws ConversionException
     */
    public function convert(TES4Target $target, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {

        $script = $target->getScript();
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

                    $newFunctionScope = new TES5FunctionScope($blockType ."_". $i);

                    foreach($block->getFunctionScope()->getVariables() as $signatureVariable) {
                        $newFunctionScope->addVariable($signatureVariable);
                    }

                    $function = new TES5FunctionCodeBlock(null, $newFunctionScope, $block->getCodeScope());
                    $functions[] = $function;

                    if ($localScopeArguments === null) {

                        $localScopeArguments = new TES5ObjectCallArguments();
                        foreach ($block->getFunctionScope()->getVariables() as $localVariable) {
                            $localScopeArguments->add($this->referenceFactory->createReferenceToVariable($localVariable));
                        }

                    }

                    ++$i;

                }

                //Create the proxy block.
                $proxyBlock = $this->blockFactory->createNewBlock($blockType, clone $block->getFunctionScope());

                foreach ($functions as $function) {
                    $blockList->add($function);

                    $functionCall = $this->objectCallFactory->createObjectCall(
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

        $result = new TES5Target(new TES5Script($globalScope, $blockList),$target->getOutputPath());
        return $result;
    }

} 