<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds\Standalone;

use Ormin\OBSLexicalParser\TES4\AST\TES4ScriptCollection;
use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\AST\TES5Target;
use Ormin\OBSLexicalParser\TES5\Context\TypeMapper;
use Ormin\OBSLexicalParser\TES5\Converter\TES4ToTES5ASTConverter;
use Ormin\OBSLexicalParser\TES5\Converter\TES5AdditionalBlockChangesPass;
use Ormin\OBSLexicalParser\TES5\Factory\TES5BlockFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5BlockLocalScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5BranchFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ChainedCodeChunkFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5CodeScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ExpressionFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5LocalVariableListFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectPropertyFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5PrimitiveValueFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5PropertiesFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ReferenceFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ReturnFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5StaticGlobalScopesFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ValueFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5VariableAssignationConversionFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5VariableAssignationFactory;
use Ormin\OBSLexicalParser\TES5\Service\MetadataLogService;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;
use Ormin\OBSLexicalParser\TES5\Service\TES5TypeInferencer;

class TranspileCommand implements \Ormin\OBSLexicalParser\Builds\TranspileCommand
{

    /**
     * @var \Ormin\OBSLexicalParser\TES4\Parser\SyntaxErrorCleanParser
     */
    private $parser;

    /**
     * @var \Ormin\OBSLexicalParser\TES5\Converter\TES4ToTES5ASTConverter
     */
    private $converter;

    public function initialize()
    {
        $parser = new \Ormin\OBSLexicalParser\TES4\Parser\SyntaxErrorCleanParser(new \Ormin\OBSLexicalParser\TES4\Parser\TES4OBScriptGrammar());
        $typeMapper = new TypeMapper();
        $analyzer = new ESMAnalyzer($typeMapper,'Oblivion.esm');
        $primitiveValueFactory = new TES5PrimitiveValueFactory();
        $metadataLogService = new MetadataLogService('TestMetadata');
        $blockLocalScopeFactory = new TES5BlockLocalScopeFactory();
        $codeScopeFactory = new TES5CodeScopeFactory();
        $expressionFactory = new TES5ExpressionFactory();
        $typeInferencer = new TES5TypeInferencer($analyzer,'./BuildTargets/Standalone/Source/');
        $objectPropertyFactory = new TES5ObjectPropertyFactory($typeInferencer);
        $referenceFactory = new TES5ReferenceFactory($objectPropertyFactory);
        $assignationFactory = new TES5VariableAssignationFactory($referenceFactory);
        $localVariableFactory = new TES5LocalVariableListFactory();


        $valueFactory = new TES5ValueFactory($referenceFactory, $expressionFactory, $assignationFactory, $objectPropertyFactory, $analyzer, $primitiveValueFactory, $typeInferencer, $metadataLogService);
        $branchFactory = new TES5BranchFactory(
            $blockLocalScopeFactory,
            $codeScopeFactory,
            $valueFactory
        );

        $assignationConversionFactory = new TES5VariableAssignationConversionFactory($referenceFactory, $valueFactory, $assignationFactory, $branchFactory, $expressionFactory, $typeInferencer);

        $returnFactory = new TES5ReturnFactory($valueFactory, $blockLocalScopeFactory);

        $converter = new TES4ToTES5ASTConverter(
            $analyzer,
            new TES5BlockFactory(
                new TES5ChainedCodeChunkFactory($valueFactory, $returnFactory, $assignationConversionFactory, $branchFactory, $localVariableFactory),
                $blockLocalScopeFactory,
                $codeScopeFactory,
                new TES5AdditionalBlockChangesPass($valueFactory, $blockLocalScopeFactory, $codeScopeFactory, $expressionFactory, $referenceFactory, $branchFactory, $assignationFactory)

            ),
            $valueFactory,
            $referenceFactory,
            new TES5PropertiesFactory(),
            new TES5StaticGlobalScopesFactory(),
            new TES5NameTransformer()
        );


        $this->parser = $parser;
        $this->converter = $converter;

    }


    public function transpile($sourcePaths, $outputPaths)
    {
        $ASTCollection = new TES4ScriptCollection();

        foreach ($sourcePaths as $k => $sourcePath) {
            $lexer = new \Ormin\OBSLexicalParser\TES4\Lexer\ScriptLexer();
            $tokens = $lexer->lex(file_get_contents($sourcePath));
            $ASTCollection->add($this->parser->parse($tokens), $outputPaths[$k]);

        }

        $TES5ASTCollection = $this->converter->convert($ASTCollection);

        /**
         * @var TES5Target $target
         */
        foreach ($TES5ASTCollection->getIterator() as $target) {
            file_put_contents($target->getOutputPath(), $target->getScript()->output());
            passthru('lua "Utilities/beautifier.lua" "' . $target->getOutputPath() . '"');

        }

    }


} 