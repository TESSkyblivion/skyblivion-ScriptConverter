<?php

namespace Ormin\OBSLexicalParser\TES5\Factory\Functions;

use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4Function;
use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCallArguments;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Integer;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ExpressionFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectCallArgumentsFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectCallFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ObjectPropertyFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5PrimitiveValueFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ReferenceFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ValueFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5VariableAssignationFactory;
use Ormin\OBSLexicalParser\TES5\Service\MetadataLogService;
use Ormin\OBSLexicalParser\TES5\Service\TES5TypeInferencer;

class SetPCFactionAttackFactory implements FunctionFactory
{

    /**
     * @var string
     */


    /**
     * @var TES5ReferenceFactory
     */
    private $referenceFactory;

    /**
     * @var TES5ExpressionFactory
     */
    private $expressionFactory;

    /**
     * @var TES5VariableAssignationFactory
     */
    private $assignationFactory;

    /**
     * @var ESMAnalyzer
     */
    private $analyzer;

    /**
     * @var TES5ObjectPropertyFactory
     */
    private $objectPropertyFactory;

    /**
     * @var TES5PrimitiveValueFactory
     */
    private $primitiveValueFactory;

    /**
     * @var \Ormin\OBSLexicalParser\TES5\Service\TES5TypeInferencer
     */
    private $typeInferencer;

    /**
     * @var \Ormin\OBSLexicalParser\TES5\Service\MetadataLogService
     */
    private $metadataLogService;

    /**
     * @var TES5ValueFactory
     */
    private $valueFactory;

    /**
     * @var TES5ObjectCallFactory
     */
    private $objectCallFactory;

    /**
     * @var TES5ObjectCallArgumentsFactory
     */
    private $objectCallArgumentsFactory;

    public function __construct(TES5ValueFactory $valueFactory, TES5ObjectCallFactory $objectCallFactory, TES5ObjectCallArgumentsFactory $objectCallArgumentsFactory, TES5ReferenceFactory $referenceFactory, TES5ExpressionFactory $expressionFactory, TES5VariableAssignationFactory $assignationFactory, TES5ObjectPropertyFactory $objectPropertyFactory, ESMAnalyzer $analyzer, TES5PrimitiveValueFactory $primitiveValueFactory, TES5TypeInferencer $typeInferencer, MetadataLogService $metadataLogService)
    {

        $this->objectCallArgumentsFactory = $objectCallArgumentsFactory;
        $this->valueFactory = $valueFactory;
        $this->referenceFactory = $referenceFactory;
        $this->expressionFactory = $expressionFactory;
        $this->analyzer = $analyzer;
        $this->assignationFactory = $assignationFactory;
        $this->objectPropertyFactory = $objectPropertyFactory;
        $this->primitiveValueFactory = $primitiveValueFactory;
        $this->typeInferencer = $typeInferencer;
        $this->metadataLogService = $metadataLogService;
        $this->objectCallFactory = $objectCallFactory;
    }


    public function convertFunction(TES5Referencer $calledOn, TES4Function $function, TES5CodeScope $codeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {
        $localScope = $codeScope->getLocalScope();
        $functionArguments = $function->getArguments();
        switch ($functionArguments->getValue(1)->getData()) {

            case 0: {
                $arg = 0;
                break;
            }

            case 1: {
                $arg = 1000;
                break;
            }

            default: {
                throw new ConversionException("SetPCFactionMurder/SetPCFactionAttack argument unknown");
            }

        }

        $constantArgument = new TES5ObjectCallArguments();
        $constantArgument->add(new TES5Integer($arg));
        $faction = $this->referenceFactory->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
        $function = $this->objectCallFactory->createObjectCall(
            $faction, "SetCrimeGoldViolent", $multipleScriptsScope, $constantArgument
        );
        return $function;
    }
}