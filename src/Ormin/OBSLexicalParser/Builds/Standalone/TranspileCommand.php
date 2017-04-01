<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds\Standalone;

use Ormin\OBSLexicalParser\Builds\Build;
use Ormin\OBSLexicalParser\DI\TES5ValueFactoryFunctionFiller;
use Ormin\OBSLexicalParser\TES4\AST\TES4Target;
use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\Context\TypeMapper;
use Ormin\OBSLexicalParser\TES5\Converter\TES4ToTES5ASTConverter;
use Ormin\OBSLexicalParser\TES5\Converter\TES5AdditionalBlockChangesPass;
use Ormin\OBSLexicalParser\TES5\Factory\TES5BlockFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5BlockFunctionScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5BranchFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ChainedCodeChunkFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5CodeScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ExpressionFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5InitialBlockCodeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5LocalScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5LocalVariableListFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectCallArgumentsFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectCallFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectPropertyFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5PrimitiveValueFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ReferenceFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ReturnFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ValueFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5VariableAssignationConversionFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5VariableAssignationFactory;
use Ormin\OBSLexicalParser\TES5\Service\MetadataLogService;
use Ormin\OBSLexicalParser\TES5\Service\TES5TypeInferencer;
use Ormin\OBSLexicalParser\Builds\Service\StandaloneParsingService;

class TranspileCommand implements \Ormin\OBSLexicalParser\Builds\TranspileCommand
{

    /**
     * @var StandaloneParsingService
     */
    private $parserService;

    /**
     * @var \Ormin\OBSLexicalParser\TES5\Converter\TES4ToTES5ASTConverter
     */
    private $converter;

    public function __construct(StandaloneParsingService $standaloneParsingService)
    {
        $this->parserService = $standaloneParsingService;
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

        $converter = new TES4ToTES5ASTConverter(
            $analyzer,
            new TES5BlockFactory(
                new TES5ChainedCodeChunkFactory($valueFactory, $returnFactory, $assignationConversionFactory, $branchFactory, $localVariableFactory),
                $blockLocalScopeFactory,
                $codeScopeFactory,
                new TES5AdditionalBlockChangesPass($objectCallFactory, $blockLocalScopeFactory, $codeScopeFactory, $expressionFactory, $referenceFactory, $branchFactory, $assignationFactory, $localScopeFactory),
                $localScopeFactory,
                new TES5InitialBlockCodeFactory($branchFactory, $expressionFactory, $referenceFactory, $objectCallFactory)
            ),
            $objectCallFactory,
            $referenceFactory
        );

        $this->converter = $converter;

    }


    public function transpile($sourcePath, $outputPath, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {
        $tes4Target = new TES4Target($this->parserService->parseScript($sourcePath), $outputPath);


        $target = $this->converter->convert($tes4Target, $globalScope, $multipleScriptsScope);
        return $target;
    }


} 