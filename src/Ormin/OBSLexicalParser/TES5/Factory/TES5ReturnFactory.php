<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunk;
use Ormin\OBSLexicalParser\TES4\AST\Code\TES4Return;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5EventCodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunkCollection;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5Return;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCallArguments;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5SelfReference;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Float;

class TES5ReturnFactory  {

    private $objectCallFactory;

    private $localScopeFactory;

    public function __construct(TES5ValueFactory $objectCallFactory, TES5BlockLocalScopeFactory $localScopeFactory) {
        $this->objectCallFactory = $objectCallFactory;
        $this->localScopeFactory = $localScopeFactory;
    }

    public function createCodeChunk() {

        $collection = new TES5CodeChunkCollection();
        $collection->add(new TES5Return());

        return $collection;


    }
} 