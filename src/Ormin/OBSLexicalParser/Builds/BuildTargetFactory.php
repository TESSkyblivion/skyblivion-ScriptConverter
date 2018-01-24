<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 11/11/2015
 * Time: 12:30 AM
 */

namespace Ormin\OBSLexicalParser\Builds;


use Ormin\OBSLexicalParser\Builds\QF\Factory\ObjectiveHandlingFactory;
use Ormin\OBSLexicalParser\Builds\QF\Factory\QFFragmentFactory;
use Ormin\OBSLexicalParser\Builds\QF\Factory\Service\MappedTargetsLogService;
use Ormin\OBSLexicalParser\Builds\Service\FragmentsParsingService;
use Ormin\OBSLexicalParser\Builds\Service\StandaloneParsingService;
use Ormin\OBSLexicalParser\DI\TES5ValueFactoryFunctionFiller;
use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES4\Parser\SyntaxErrorCleanParser;
use Ormin\OBSLexicalParser\TES5\Context\TypeMapper;
use Ormin\OBSLexicalParser\TES5\Factory\TES5BranchFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5CodeScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ExpressionFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5FragmentFunctionScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5LocalScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectCallArgumentsFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectCallFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectPropertyFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5PrimitiveValueFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ReferenceFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ValueFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5VariableAssignationFactory;
use Ormin\OBSLexicalParser\TES5\Service\MetadataLogService;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;
use Ormin\OBSLexicalParser\TES5\Service\TES5TypeInferencer;

class BuildTargetFactory
{

    public static function getCollection($targetsString, Build $build)
    {
        $targets = explode(",",$targetsString);
        $collection = new BuildTargetCollection();
        foreach($targets as $k => $v)
        {
            $collection->add(static::get(trim($v), $build));
        }

        return $collection;

    }

    public static function get($target, Build $build)
    {

        switch ($target) {

            case BuildTarget::BUILD_TARGET_STANDALONE: {
                
                $standaloneParsingService = new StandaloneParsingService(
                    new SyntaxErrorCleanParser(new \Ormin\OBSLexicalParser\TES4\Parser\TES4OBScriptGrammar())
                );
                
                return new BuildTarget(
                    BuildTarget::BUILD_TARGET_STANDALONE,
                    'TES4',
                    $build,
                    new TES5NameTransformer(),
                    new \Ormin\OBSLexicalParser\Builds\Standalone\TranspileCommand($standaloneParsingService),
                    new \Ormin\OBSLexicalParser\Builds\Standalone\CompileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\Standalone\ASTCommand(),
                    new \Ormin\OBSLexicalParser\Builds\Standalone\BuildScopeCommand($standaloneParsingService),
                    new \Ormin\OBSLexicalParser\Builds\Standalone\WriteCommand()
                );

            }

            case BuildTarget::BUILD_TARGET_TIF: {
                $fragmentsParsingService = new FragmentsParsingService(
                    new SyntaxErrorCleanParser(new \Ormin\OBSLexicalParser\TES4\Parser\TES4ObscriptCodeGrammar())
                );


                return new BuildTarget(
                    BuildTarget::BUILD_TARGET_TIF,
                    '',
                    $build,
                    new TES5NameTransformer(),
                    new \Ormin\OBSLexicalParser\Builds\TIF\TranspileCommand($fragmentsParsingService),
                    new \Ormin\OBSLexicalParser\Builds\TIF\CompileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\TIF\ASTCommand(),
                    new \Ormin\OBSLexicalParser\Builds\TIF\BuildScopeCommand(),
                    new \Ormin\OBSLexicalParser\Builds\TIF\WriteCommand()
                );
            }

            case BuildTarget::BUILD_TARGET_PF: {
                return new BuildTarget(
                    BuildTarget::BUILD_TARGET_PF,
                    '',
                    $build,
                    new TES5NameTransformer(),
                    new \Ormin\OBSLexicalParser\Builds\PF\TranspileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\PF\CompileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\PF\ASTCommand(),
                    new \Ormin\OBSLexicalParser\Builds\PF\BuildScopeCommand(),
                    new \Ormin\OBSLexicalParser\Builds\PF\WriteCommand()
                );
            }

            case BuildTarget::BUILD_TARGET_QF: {
                $fragmentsParsingService = new FragmentsParsingService(
                    new SyntaxErrorCleanParser(new \Ormin\OBSLexicalParser\TES4\Parser\TES4ObscriptCodeGrammar())
                );

                $typeMapper = new TypeMapper();
                $analyzer = new ESMAnalyzer($typeMapper,'Oblivion.esm');
                $primitiveValueFactory = new TES5PrimitiveValueFactory();
                $metadataLogService = new MetadataLogService($build);
                $codeScopeFactory = new TES5CodeScopeFactory();
                $expressionFactory = new TES5ExpressionFactory();
                $typeInferencer = new TES5TypeInferencer($analyzer,'./BuildTargets/Standalone/Source/');
                $objectCallFactory = new TES5ObjectCallFactory($typeInferencer);
                $objectPropertyFactory = new TES5ObjectPropertyFactory($typeInferencer);
                $referenceFactory = new TES5ReferenceFactory($objectCallFactory, $objectPropertyFactory);
                $assignationFactory = new TES5VariableAssignationFactory($referenceFactory);

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
                $variableAssignationFactory = new TES5VariableAssignationFactory($referenceFactory);

                return new BuildTarget(
                    BuildTarget::BUILD_TARGET_QF,
                    '',
                    $build,
                    new TES5NameTransformer(),
                    new \Ormin\OBSLexicalParser\Builds\QF\TranspileCommand($fragmentsParsingService),
                    new \Ormin\OBSLexicalParser\Builds\QF\CompileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\QF\ASTCommand(),
                    new \Ormin\OBSLexicalParser\Builds\QF\BuildScopeCommand(),
                    new \Ormin\OBSLexicalParser\Builds\QF\WriteCommand(

                        new QFFragmentFactory(
                            new MappedTargetsLogService($build),
                            new ObjectiveHandlingFactory(
                                new TES5FragmentFunctionScopeFactory(),
                                $codeScopeFactory,
                                $localScopeFactory,
                                $branchFactory,
                                $variableAssignationFactory,
                                $referenceFactory,
                                $expressionFactory
                            )
                        )


                    )
                );
            }

            default: {
                throw new \LogicException("Unknown target ".$target);
            }

        }


    }

}