<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds\QF;

use Ormin\OBSLexicalParser\Builds\Build;
use Ormin\OBSLexicalParser\Builds\Service\FragmentsParsingService;
use Ormin\OBSLexicalParser\DI\TES5ValueFactoryFunctionFiller;
use Ormin\OBSLexicalParser\Input\FragmentsReferencesBuilder;
use Ormin\OBSLexicalParser\TES4\AST\TES4FragmentTarget;
use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\Context\TypeMapper;
use Ormin\OBSLexicalParser\TES5\Converter\TES4ToTES5ASTQFFragmentConverter;
use Ormin\OBSLexicalParser\TES5\Converter\TES4ToTES5ASTTIFFragmentConverter;
use Ormin\OBSLexicalParser\TES5\Converter\TES5AdditionalBlockChangesPass;
use Ormin\OBSLexicalParser\TES5\Factory\TES5BlockFunctionScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5BranchFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ChainedCodeChunkFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5CodeScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ExpressionFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5FragmentFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5FragmentFunctionScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5LocalScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5LocalVariableListFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectCallArgumentsFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectCallFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectPropertyFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5PrimitiveValueFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5PropertiesFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ReferenceFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ReturnFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ValueFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5VariableAssignationConversionFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5VariableAssignationFactory;
use Ormin\OBSLexicalParser\TES5\Service\MetadataLogService;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;
use Ormin\OBSLexicalParser\TES5\Service\TES5TypeInferencer;

class TranspileCommand implements \Ormin\OBSLexicalParser\Builds\TranspileCommand
{

    /**
     * @var FragmentsParsingService
     */
    private $parsingService;

    /**
     * @var TES4ToTES5ASTTIFFragmentConverter
     */
    private $converter;

    /**
     * @var FragmentsReferencesBuilder
     */
    private $fragmentsReferenceBuilder;

    public function __construct(FragmentsParsingService $fragmentsParsingService)
    {
        $this->parsingService = $fragmentsParsingService;
    }

    public function initialize(Build $build)
    {
        $typeMapper = new TypeMapper();
        $analyzer = new ESMAnalyzer($typeMapper,'Oblivion.esm');
        $primitiveValueFactory = new TES5PrimitiveValueFactory();
        $metadataLogService = new MetadataLogService($build);
        $blockLocalScopeFactory = new TES5BlockFunctionScopeFactory();
        $codeScopeFactory = new TES5CodeScopeFactory();
        $expressionFactory = new TES5ExpressionFactory();
        $typeInferencer = new TES5TypeInferencer($analyzer,'./BuildTargets/Standalone/Source/');
        $objectPropertyFactory = new TES5ObjectPropertyFactory($typeInferencer);
        $objectCallFactory = new TES5ObjectCallFactory($typeInferencer);
        $referenceFactory = new TES5ReferenceFactory($objectCallFactory, $objectPropertyFactory);
        $assignationFactory = new TES5VariableAssignationFactory($referenceFactory);
        $localVariableFactory = new TES5LocalVariableListFactory();

        $localScopeFactory = new TES5LocalScopeFactory();
        $valueFactory = new TES5ValueFactory($objectCallFactory, $referenceFactory, $expressionFactory, $assignationFactory, $objectPropertyFactory, $analyzer, $primitiveValueFactory, $typeInferencer, $metadataLogService);
        $filler = new TES5ValueFactoryFunctionFiller();
        $objectCallArgumentsFactory = new TES5ObjectCallArgumentsFactory($valueFactory);
        $filler->fillFunctions($valueFactory, $objectCallFactory, $objectCallArgumentsFactory, $referenceFactory, $expressionFactory, $assignationFactory, $objectPropertyFactory, $analyzer, $primitiveValueFactory, $typeInferencer, $metadataLogService);
        $branchFactory = new TES5BranchFactory(
            $localScopeFactory,
            $codeScopeFactory,
            $valueFactory
        );

        $assignationConversionFactory = new TES5VariableAssignationConversionFactory($objectCallFactory, $referenceFactory, $valueFactory, $assignationFactory, $branchFactory, $expressionFactory, $typeInferencer);

        $returnFactory = new TES5ReturnFactory($objectCallFactory, $referenceFactory, $blockLocalScopeFactory);

        $chainedCodeChunkFactory = new TES5ChainedCodeChunkFactory($valueFactory, $returnFactory, $assignationConversionFactory, $branchFactory, $localVariableFactory);

        $converter = new TES4ToTES5ASTQFFragmentConverter(
            $analyzer,
            new TES5FragmentFactory($chainedCodeChunkFactory, new TES5FragmentFunctionScopeFactory(), $codeScopeFactory, new TES5AdditionalBlockChangesPass($objectCallFactory, $blockLocalScopeFactory, $codeScopeFactory, $expressionFactory, $referenceFactory, $branchFactory, $assignationFactory, $localScopeFactory), $localScopeFactory),
            $valueFactory,
            $referenceFactory,
            new TES5PropertiesFactory(),
            new TES5NameTransformer()
        );


        $this->converter = $converter;
        $this->fragmentsReferenceBuilder = new FragmentsReferencesBuilder();

    }


    public function transpile($sourcePath, $outputPath, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {

        $AST = $this->parsingService->parseScript($sourcePath);
        $convertedScript = $this->converter->convert(new TES4FragmentTarget($AST, $outputPath), $globalScope, $multipleScriptsScope);
        return $convertedScript;

    }


} 