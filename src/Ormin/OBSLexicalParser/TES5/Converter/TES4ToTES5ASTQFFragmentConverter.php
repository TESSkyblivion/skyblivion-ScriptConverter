<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Converter;

use Ormin\OBSLexicalParser\TES4\AST\TES4FragmentTarget;
use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5BlockList;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\TES5Script;
use Ormin\OBSLexicalParser\TES5\AST\TES5Target;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Factory\TES5FragmentFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5PropertiesFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ReferenceFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ValueFactory;
use Ormin\OBSLexicalParser\TES5\Other\TES5FragmentType;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;

class TES4ToTES5ASTQFFragmentConverter
{

    /**
     * @var ESMAnalyzer
     * Oblivion binary data analyzer.
     */
    private $esmAnalyzer;

    /**
     * @var TES5FragmentFactory
     */
    private $fragmentFactory;

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
     * @var TES5NameTransformer
     */
    private $nameTransformer;

    public function __construct(ESMAnalyzer $esmAnalyzer, TES5FragmentFactory $fragmentFactory, TES5ValueFactory $valueFactory, TES5ReferenceFactory $referenceFactory, TES5PropertiesFactory $propertiesFactory, TES5NameTransformer $nameTransformer)
    {
        $this->esmAnalyzer = $esmAnalyzer;
        $this->fragmentFactory = $fragmentFactory;
        $this->valueFactory = $valueFactory;
        $this->referenceFactory = $referenceFactory;
        $this->propertiesFactory = $propertiesFactory;
        $this->nameTransformer = $nameTransformer;
    }


    /**
     * @param TES4FragmentTarget $fragmentTarget
     * @param TES5GlobalScope $globalScope
     * @param TES5MultipleScriptsScope $multipleScriptsScope
     * @return TES5Target
     * @throws ConversionException
     */
    public function convert(TES4FragmentTarget $fragmentTarget, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {
        $fragment = $this->fragmentFactory->createFragment(TES5FragmentType::T_QF(), "Fragment_0", $globalScope, $multipleScriptsScope, $fragmentTarget->getCodeChunks());

        $blockList = new TES5BlockList();
        $blockList->add($fragment);

        $script = new TES5Script($globalScope, $blockList);

        $target = new TES5Target($script, $fragmentTarget->getOutputPath());

        return $target;

    }

} 