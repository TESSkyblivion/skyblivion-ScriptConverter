<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES4\AST\Expression\Operators\TES4ArithmeticExpressionOperator;
use Ormin\OBSLexicalParser\TES4\AST\Expression\TES4Expression;
use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4Callable;
use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4Function;
use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4FunctionArguments;
use Ormin\OBSLexicalParser\TES4\AST\Value\Primitive\TES4Integer;
use Ormin\OBSLexicalParser\TES4\AST\Value\Primitive\TES4Primitive;
use Ormin\OBSLexicalParser\TES4\AST\Value\Primitive\TES4String;
use Ormin\OBSLexicalParser\TES4\AST\Value\TES4ApiToken;
use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Reference;
use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value;
use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunkCollection;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5Filler;
use Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5ArithmeticExpressionOperator;
use Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5BinaryExpressionOperator;
use Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5LogicalExpressionOperator;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCall;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCallArguments;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Reference;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5SelfReference;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5StaticReference;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5LocalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Bool;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Float;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Integer;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5None;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Primitive;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5String;
use Ormin\OBSLexicalParser\TES5\Context\TES5LocalVariableParameterMeaning;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Service\MetadataLogService;
use Ormin\OBSLexicalParser\TES5\Service\TES5TypeInferencer;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;
use Ormin\OBSLexicalParser\TES5\Types\TES5InheritanceGraphAnalyzer;
use \Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectProperty;
use Ormin\OBSLexicalParser\TES5\Factory\Functions\FunctionFactory;

class TES5ValueFactory
{
    /**
     * @var TES5ObjectCallFactory
     */
    private $objectCallFactory;

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
     * @var FunctionFactory[]
     */
    private $functionFactories = [];

    public function __construct(TES5ObjectCallFactory $objectCallFactory, TES5ReferenceFactory $referenceFactory, TES5ExpressionFactory $expressionFactory, TES5VariableAssignationFactory $assignationFactory, TES5ObjectPropertyFactory $objectPropertyFactory, ESMAnalyzer $analyzer, TES5PrimitiveValueFactory $primitiveValueFactory, TES5TypeInferencer $typeInferencer, MetadataLogService $metadataLogService)
    {
        $this->objectCallFactory = $objectCallFactory;
        $this->referenceFactory = $referenceFactory;
        $this->expressionFactory = $expressionFactory;
        $this->analyzer = $analyzer;
        $this->assignationFactory = $assignationFactory;
        $this->objectPropertyFactory = $objectPropertyFactory;
        $this->primitiveValueFactory = $primitiveValueFactory;
        $this->typeInferencer = $typeInferencer;
        $this->metadataLogService = $metadataLogService;
    }

    public function addFunctionFactory($functionName, FunctionFactory $factory)
    {
        $key = strtolower($functionName);
        if(isset($this->functionFactories[$key])) {
            throw new \LogicException("Function factory for function ".$functionName." already defined.");
        }

        $this->functionFactories[$key] = $factory;
    }

    private function convertArithmeticExpression(TES4Expression $expression, TES5CodeScope $codeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {

        $sets = [
            [$expression->getLeftValue(), $expression->getRightValue()],
            [$expression->getRightValue(), $expression->getLeftValue()],
        ];

        /**
         * Scenario 1 - Special functions converted on expression level
         * @var TES4Value[] $set
         */
        foreach ($sets as $set) {


            if (!$set[0] instanceof TES4Callable) {
                continue;
            }

            $function = $set[0]->getFunction();

            switch (strtolower($function->getFunctionCall()->getFunctionName())) {

                case "getweaponanimtype": {

                    $calledOn = $this->createCalledOnReferenceOfCalledFunction($set[0], $codeScope, $globalScope, $multipleScriptsScope);
                    $leftValue = $this->objectCallFactory->createObjectCall($this->objectCallFactory->createObjectCall($calledOn, "GetEquippedWeapon", $multipleScriptsScope), "GetWeaponType",$multipleScriptsScope);

                    switch ((int)$set[1]->getData()) {

                        case 0: {

                            $targetedWeaponTypes = [0];
                            break;

                        }


                        case 1: {

                            $targetedWeaponTypes = [1, 2, 3, 4];
                            break;

                        }


                        case 2: {

                            $targetedWeaponTypes = [5, 6, 8];
                            break;

                        }


                        case 3: {

                            $targetedWeaponTypes = [7, 9];
                            break;

                        }

                        default: {
                            throw new ConversionException("GetWeaponAnimType() - Unknown weapon type in expression");
                        }

                    }



                    $expressions = [];

                    foreach ($targetedWeaponTypes as $targetedWeaponType) {

                        $expressions[] = $this->expressionFactory->createArithmeticExpression(
                            $leftValue,
                            TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                            new TES5Integer($targetedWeaponType)
                        );

                    }


                    $resultExpression = $expressions[0];

                    unset($expressions[0]);

                    while(!empty($expressions)) {
                        $resultExpression = $this->expressionFactory->createLogicalExpression(
                            $resultExpression,
                            TES5LogicalExpressionOperator::OPERATOR_OR(),
                            array_pop($expressions)
                        );
                    }

                    return $resultExpression;
                }

                case "getdetected": {
                    $inversedArgument = new TES5ObjectCallArguments();
                    $inversedArgument->add($this->createCalledOnReferenceOfCalledFunction($set[0], $codeScope, $globalScope, $multipleScriptsScope));
                    $leftValue = $this->objectCallFactory->createObjectCall($this->referenceFactory->createReadReference($function->getArguments()->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $codeScope->getLocalScope()), "isDetectedBy",$multipleScriptsScope, $inversedArgument);
                    $rightValue = new TES5Integer(((int)$set[1]->getData() == 0) ? 0 : 1);
                    $expression = $this->expressionFactory->createArithmeticExpression(
                        $leftValue,
                        TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                        $rightValue
                    );

                    return $expression;
                }

                case "getdetectionlevel": {

                    if (!$set[1]->hasFixedValue()) {
                        throw new ConversionException("Cannot convert getDetectionLevel calls with dynamic comparision");
                    }

                    $boolean = new TES5Bool($set[1]->getData() == 3); //true only if the compared value was 3

                    $inversedArgument = new TES5ObjectCallArguments();
                    $inversedArgument->add($this->createCalledOnReferenceOfCalledFunction($set[0], $codeScope, $globalScope, $multipleScriptsScope));

                    $expression = $this->expressionFactory->createArithmeticExpression(
                        $this->objectCallFactory->createObjectCall($this->referenceFactory->createReadReference($function->getArguments()->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $codeScope->getLocalScope()), "isDetectedBy",$multipleScriptsScope, $inversedArgument),
                        TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                        $boolean
                    );

                    return $expression;
                }

                case "getcurrentaiprocedure": {

                    if (!$set[1]->hasFixedValue()) {
                        throw new ConversionException("Cannot convert getCurrentAIProcedure() calls with dynamic comparision");
                    }

                    switch ((int)$set[1]->getData()) {

                        case 4: {

                            //ref.getSleepState() == 3
                            $expression = $this->expressionFactory->createArithmeticExpression(
                                $this->objectCallFactory->createObjectCall($this->createCalledOnReferenceOfCalledFunction($set[0], $codeScope, $globalScope, $multipleScriptsScope), "IsInDialogueWithPlayer",$multipleScriptsScope),
                                TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                                new TES5Bool((bool)$expression->getOperator() == TES4ArithmeticExpressionOperator::OPERATOR_EQUAL()) //cast to true if the original operator was ==, false otherwise.
                            );

                            return $expression;
                        }


                        case 8: {

                            //ref.getSleepState() == 3
                            $expression = $this->expressionFactory->createArithmeticExpression(
                                $this->objectCallFactory->createObjectCall($this->createCalledOnReferenceOfCalledFunction($set[0], $codeScope, $globalScope, $multipleScriptsScope), "getSleepState",$multipleScriptsScope),
                                TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                                new TES5Integer(3) //SLEEPSTATE_SLEEP
                            );

                            return $expression;
                        }

                        case 13: {

                            //ref.getSleepState() == 3
                            $expression = $this->expressionFactory->createArithmeticExpression(
                                $this->objectCallFactory->createObjectCall($this->createCalledOnReferenceOfCalledFunction($set[0], $codeScope, $globalScope, $multipleScriptsScope), "IsInCombat",$multipleScriptsScope),
                                TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                                new TES5Bool((bool)$expression->getOperator() == TES4ArithmeticExpressionOperator::OPERATOR_EQUAL()) //cast to true if the original operator was ==, false otherwise.
                            );

                            return $expression;
                        }

                        case 0:
                        case 7:
                        case 15:
                        case 17: {
                            //@INCONSISTENCE Wander.. idk how to check it tbh. We return always true. Think about better representation
                            return new TES5Bool((bool)($expression->getOperator() == TES4ArithmeticExpressionOperator::OPERATOR_EQUAL()));
                        }

                        default: {
                            throw new ConversionException("Cannot convert GetCurrentAiProcedure - unknown TES4 procedure number arg ".(int)$set[1]->getData());
                        }

                    }

                    break;

                }

                case "isidleplaying":
                case "getknockedstate":
                case "gettalkedtopc": {
                    return new TES5Bool(true); //This is so unimportant that i think it's not worth to find a good alternative and waste time.
                }

                case "getsitting": {
                    //WARNING: Needs to implement Horse sittings, too.
                    //SEE: http://www.gameskyrim.com/papyrus-isridinghorse-function-t255012.html

                    switch ((int)$set[1]->getData()) {
                        case 0: {
                            $goTo = 0;
                            break;
                        }

                        case 1:
                        case 2:
                        case 11:
                        case 12: {
                            $goTo = 2;
                            break;
                        }

                        case 3:
                        case 13: {
                            $goTo = 3;
                            break;
                        }

                        case 4:
                        case 14: {
                            $goTo = 4;
                            break;
                        }

                        default: {
                            throw new ConversionException("GetSitting - unknown state found");
                        }
                    }

                    //ref.getSleepState() == 3
                    $expression = $this->expressionFactory->createArithmeticExpression(
                        $this->objectCallFactory->createObjectCall($this->createCalledOnReferenceOfCalledFunction($set[0], $codeScope, $globalScope, $multipleScriptsScope), "GetSitState",$multipleScriptsScope),
                        TES5ArithmeticExpressionOperator::memberByValue($expression->getOperator()->value()),
                        new TES5Integer($goTo));

                    return $expression;

                }


            }

        }

        $leftValue = $this->createValue($expression->getLeftValue(), $codeScope, $globalScope, $multipleScriptsScope);
        $rightValue = $this->createValue($expression->getRightValue(), $codeScope, $globalScope, $multipleScriptsScope);

        $tes5sets = [
            [$leftValue, $rightValue],
            [$rightValue, $leftValue]
        ];

        $objectReferenceType = TES5BasicType::T_FORM(); //used just to make sure.
        $operator = TES5ArithmeticExpressionOperator::memberByValueWithDefault($expression->getOperator()->value(), null);

        /**
         * @var TES5Value[] $tes5set
         * Scenario 2: Comparision of ObjectReferences to integers ( quick formid check )
         */
        foreach ($tes5sets as $tes5set) {

            if ($tes5set[0]->getType() == $objectReferenceType || TES5InheritanceGraphAnalyzer::isExtending($tes5set[0]->getType(), $objectReferenceType)) {

                if ($tes5set[1]->getType() == TES5BasicType::T_INT()) {

                    //Perhaps we should allow to try to cast upwards for primitives, ->asPrimitive() or similar
                    //In case we do know at compile time that we're comparing against zero, then we can assume
                    //we can compare against None, which allows us not call GetFormID() on most probably None object
					if($tes5set[1] instanceof TES5Primitive && $tes5set[1]->getValue() == 0) {

                        if($operator == TES5ArithmeticExpressionOperator::OPERATOR_EQUAL()) {
                            $targetOperator = $operator;
                        } else {
                            $targetOperator = TES5ArithmeticExpressionOperator::OPERATOR_NOT_EQUAL();
                        }

                        return $this->expressionFactory->createArithmeticExpression(
                            $tes5set[0],
                            $targetOperator,
                            new TES5None()
                        );


                    } else {
                        $tes5set[0] = $this->objectCallFactory->createObjectCall($tes5set[0], "GetFormID", $multipleScriptsScope);

                        return $this->expressionFactory->createArithmeticExpression(
                            $tes5set[0],
                            $operator,
                            $tes5set[1]
                        );
                    }

                }

            } else if($tes5set[0]->getType() == TES5TypeFactory::void()) {

                if ($tes5set[1] instanceof TES5Integer || $tes5set[1] instanceof TES5Float) {

                    if($tes5set[1]->getValue() == 0) {

                        return $this->expressionFactory->createArithmeticExpression(
                            $tes5set[0],
                            $operator,
                            new TES5None()
                        );

                    }
                }
            }

        }


        return $this->expressionFactory->createArithmeticExpression(
            $leftValue,
            $operator,
            $rightValue
        );

    }


    public function convertExpression(TES4Expression $expression, TES5CodeScope $codeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {

        $operator = TES5ArithmeticExpressionOperator::memberByValueWithDefault($expression->getOperator()->value(), null);

        if ($operator === null) {
            $operator = TES5LogicalExpressionOperator::memberByValueWithDefault($expression->getOperator()->value(), null);

            if ($operator === null) {

                $operator = TES5BinaryExpressionOperator::memberByValueWithDefault($expression->getOperator()->value(), null);

                if ($operator === null) {
                    throw new ConversionException("Unknown expression operator");
                }

                return $this->expressionFactory->createBinaryExpression(
                    $this->createValue($expression->getLeftValue(), $codeScope, $globalScope, $multipleScriptsScope),
                    $operator,
                    $this->createValue($expression->getRightValue(), $codeScope, $globalScope, $multipleScriptsScope)
                );
            }

            return $this->expressionFactory->createLogicalExpression(
                $this->createValue($expression->getLeftValue(), $codeScope, $globalScope, $multipleScriptsScope),
                $operator,
                $this->createValue($expression->getRightValue(), $codeScope, $globalScope, $multipleScriptsScope)
            );

        }

        return $this->convertArithmeticExpression($expression, $codeScope, $globalScope, $multipleScriptsScope);


    }

    /**
     * @param TES4Value $value
     * @param TES5CodeScope $codeScope
     * @param TES5GlobalScope $globalScope
     * @param TES5MultipleScriptsScope $multipleScriptsScope
     * @return TES5Value
     * @throws ConversionException
     */
    public function createValue(TES4Value $value, TES5CodeScope $codeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {

        if ($value instanceof TES4Primitive) {
            return $this->primitiveValueFactory->createValue($value);
        }

        if ($value instanceof TES4Reference) {
            return $this->referenceFactory->createReadReference($value->getData(), $globalScope, $multipleScriptsScope, $codeScope->getLocalScope());
        }

        if ($value instanceof TES4Callable) {
            $value = $this->createCodeChunk($value, $codeScope, $globalScope, $multipleScriptsScope);
            return $value->first();
        }

        if ($value instanceof TES4Expression) {
            return $this->convertExpression($value, $codeScope, $globalScope, $multipleScriptsScope);
        }

        throw new ConversionException("Unknown TES4Value: " . get_class($value));

    }


    public function createCodeChunk(TES4Callable $chunk, TES5CodeScope $codeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {

        $function = $chunk->getFunction();

        $calledOnReference = $this->createCalledOnReferenceOfCalledFunction($chunk, $codeScope, $globalScope, $multipleScriptsScope);
        $functionName = $function->getFunctionCall()->getFunctionName();
        $functionKey = strtolower($functionName);

        if(!isset($this->functionFactories[$functionKey])) {
            throw new ConversionException("Cannot convert function ".$functionName." as conversion handler is not defined.");
        }

        $codeChunk = $this->functionFactories[$functionKey]->convertFunction($calledOnReference, $function, $codeScope, $globalScope, $multipleScriptsScope);
        $codeChunks = new TES5CodeChunkCollection();
        $codeChunks->add($codeChunk);

        return $codeChunks;
    }

    /**
     * Returns a called-on reference for the called function.
     * @param TES4Callable $chunk
     * @param TES5CodeScope $codeScope
     * @param TES5GlobalScope $globalScope
     * @param TES5MultipleScriptsScope $multipleScriptsScope
     * @return \Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer
     * @throws \Ormin\OBSLexicalParser\TES5\Exception\ConversionException
     */
    private function createCalledOnReferenceOfCalledFunction(TES4Callable $chunk, TES5CodeScope $codeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {

        if (($calledOn = $chunk->getCalledOn()) !== null) {
            $reference = $this->referenceFactory->createReference($calledOn->getData(), $globalScope, $multipleScriptsScope, $codeScope->getLocalScope());
        } else {
            $reference = $this->referenceFactory->extractImplicitReference($globalScope, $multipleScriptsScope, $codeScope->getLocalScope());
        }

        return $reference;
    }

}
