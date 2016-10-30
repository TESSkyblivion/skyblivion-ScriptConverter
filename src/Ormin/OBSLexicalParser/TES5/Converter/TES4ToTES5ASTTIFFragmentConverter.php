<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Converter;

use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunks;
use Ormin\OBSLexicalParser\TES4\AST\VariableDeclaration\TES4VariableDeclarationList;
use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5BlockList;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\TES5Script;
use Ormin\OBSLexicalParser\TES5\AST\TES5ScriptCollection;
use Ormin\OBSLexicalParser\TES5\AST\TES5ScriptHeader;
use Ormin\OBSLexicalParser\TES5\AST\TES5Target;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Factory\TES5FragmentFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5PropertiesFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ReferenceFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ValueFactory;
use Ormin\OBSLexicalParser\TES5\Other\TES5FragmentType;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES4ToTES5ASTTIFFragmentConverter
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
     * @param string $scriptName
     * @param string $outputPath
     * @param TES4VariableDeclarationList $variableList
     * @param TES4CodeChunks $script
     * @return TES5Target
     * @throws ConversionException
     */
    public function convert($scriptName, $outputPath, TES4VariableDeclarationList $variableList, TES4CodeChunks $script)
    {

        //Create the header.
        $scriptHeader = new TES5ScriptHeader($this->nameTransformer->transform($scriptName,''), $scriptName, TES5BasicType::T_TOPICINFO(), '', true);

        $globalScope = new TES5GlobalScope($scriptHeader);

        foreach ($this->esmAnalyzer->getGlobalVariables() as $globalVariable) {
            $globalScope->addGlobalVariable($globalVariable);
        }

        if ($variableList !== null) {
            //Converting the variables to the properties.
            $this->propertiesFactory->createProperties($variableList, $globalScope);
        }

        $fragment = $this->fragmentFactory->createFragment(TES5FragmentType::T_TIF(), "Fragment_0", $globalScope, new TES5MultipleScriptsScope([$globalScope]), $script);

        $blockList = new TES5BlockList();
        $blockList->add($fragment);

        $script = new TES5Script($scriptHeader, $globalScope, $blockList);

        $collection = new TES5ScriptCollection();
        $collection->add($script, $outputPath);

        return $collection;

    }

} 