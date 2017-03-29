<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunkCollection;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5Return;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCallArguments;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5FunctionScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Float;
use Ormin\OBSLexicalParser\TES5\Converter\TES5AdditionalBlockChangesPass;

class TES5ReturnFactory  {

    /**
     * @var TES5ObjectCallArguments
     */
    private $objectCallFactory;

    /**
     * @var TES5ReferenceFactory
     */
    private $referenceFactory;

    /**
     * @var TES5BlockFunctionScopeFactory
     */
    private $localScopeFactory;

    public function __construct(TES5ObjectCallFactory $objectCallFactory,
                                TES5ReferenceFactory $referenceFactory,
                                TES5BlockFunctionScopeFactory $localScopeFactory) {
        $this->objectCallFactory = $objectCallFactory;
        $this->referenceFactory = $referenceFactory;
        $this->localScopeFactory = $localScopeFactory;
    }

    public function createCodeChunk(TES5FunctionScope $functionScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope) {

        $collection = new TES5CodeChunkCollection();

        /**
         * @todo - Rework the block types so that the information about the block type and its name is not carried within one field
         */
        if($functionScope->getBlockName() == "OnUpdate") {
            $args = new TES5ObjectCallArguments();
            $args->add(new TES5Float(TES5AdditionalBlockChangesPass::ON_UPDATE_TICK));
            $function = $this->objectCallFactory->createObjectCall($this->referenceFactory->createReferenceToSelf($globalScope), "RegisterForSingleUpdate", $multipleScriptsScope, $args);
            $collection->add($function);
        }

        $collection->add(new TES5Return());

        return $collection;


    }
} 