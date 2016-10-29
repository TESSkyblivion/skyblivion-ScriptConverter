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
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5String;
use Ormin\OBSLexicalParser\TES5\Context\TES5LocalVariableParameterMeaning;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Service\MetadataLogService;
use Ormin\OBSLexicalParser\TES5\Service\TES5TypeInferencer;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;
use Ormin\OBSLexicalParser\TES5\Types\TES5InheritanceGraphAnalyzer;
use \Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectProperty;

class TES5ValueFactory
{

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

    public function __construct(TES5ReferenceFactory $referenceFactory, TES5ExpressionFactory $expressionFactory, TES5VariableAssignationFactory $assignationFactory, TES5ObjectPropertyFactory $objectPropertyFactory, ESMAnalyzer $analyzer, TES5PrimitiveValueFactory $primitiveValueFactory, TES5TypeInferencer $typeInferencer, MetadataLogService $metadataLogService)
    {
        $this->referenceFactory = $referenceFactory;
        $this->expressionFactory = $expressionFactory;
        $this->analyzer = $analyzer;
        $this->assignationFactory = $assignationFactory;
        $this->objectPropertyFactory = $objectPropertyFactory;
        $this->primitiveValueFactory = $primitiveValueFactory;
        $this->typeInferencer = $typeInferencer;
        $this->metadataLogService = $metadataLogService;
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
                    $leftValue = $this->createObjectCall($this->createObjectCall($calledOn, "GetEquippedWeapon", $multipleScriptsScope), "GetWeaponType",$multipleScriptsScope);

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
                    $leftValue = $this->createObjectCall($this->createReadReference($function->getArguments()->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $codeScope->getLocalScope()), "isDetectedBy",$multipleScriptsScope, $inversedArgument);
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
                        $this->createObjectCall($this->createReadReference($function->getArguments()->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $codeScope->getLocalScope()), "isDetectedBy",$multipleScriptsScope, $inversedArgument),
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
                                $this->createObjectCall($this->createCalledOnReferenceOfCalledFunction($set[0], $codeScope, $globalScope, $multipleScriptsScope), "IsInDialogueWithPlayer",$multipleScriptsScope),
                                TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                                new TES5Bool((bool)$expression->getOperator() == TES4ArithmeticExpressionOperator::OPERATOR_EQUAL()) //cast to true if the original operator was ==, false otherwise.
                            );

                            return $expression;
                        }


                        case 8: {

                            //ref.getSleepState() == 3
                            $expression = $this->expressionFactory->createArithmeticExpression(
                                $this->createObjectCall($this->createCalledOnReferenceOfCalledFunction($set[0], $codeScope, $globalScope, $multipleScriptsScope), "getSleepState",$multipleScriptsScope),
                                TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                                new TES5Integer(3) //SLEEPSTATE_SLEEP
                            );

                            return $expression;
                        }

                        case 13: {

                            //ref.getSleepState() == 3
                            $expression = $this->expressionFactory->createArithmeticExpression(
                                $this->createObjectCall($this->createCalledOnReferenceOfCalledFunction($set[0], $codeScope, $globalScope, $multipleScriptsScope), "IsInCombat",$multipleScriptsScope),
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
                            return new TES5Bool((bool)$expression->getOperator() == TES4ArithmeticExpressionOperator::OPERATOR_EQUAL());
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
                        $this->createObjectCall($this->createCalledOnReferenceOfCalledFunction($set[0], $codeScope, $globalScope, $multipleScriptsScope), "GetSitState",$multipleScriptsScope),
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
                    $tes5set[0] = $this->createObjectCall($tes5set[0], "GetFormID",$multipleScriptsScope);

                    return $this->expressionFactory->createArithmeticExpression(
                        $tes5set[0],
                        $operator,
                        $tes5set[1]
                    );

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
            return $this->createReadReference($value->getData(), $globalScope, $multipleScriptsScope, $codeScope->getLocalScope());
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

    private function createArgumentList(TES4FunctionArguments $arguments = null, TES5CodeScope $codeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {

        $list = new TES5ObjectCallArguments();

        if ($arguments === null) {
            return $list;
        }

        foreach ($arguments->getValues() as $argument) {
            $newValue = $this->createValue($argument, $codeScope, $globalScope, $multipleScriptsScope);
            $list->add($newValue);
        }

        return $list;

    }

    public function createCodeChunk(TES4Callable $chunk, TES5CodeScope $codeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope)
    {

        $localScope = $codeScope->getLocalScope();
        $function = $chunk->getFunction();

        $calledOnReference = $this->createCalledOnReferenceOfCalledFunction($chunk, $codeScope, $globalScope, $multipleScriptsScope);
        $codeChunk = $this->convertFunction($calledOnReference, $function, $codeScope, $globalScope, $multipleScriptsScope, $localScope);
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
            $reference = $this->extractImplicitReference($globalScope, $multipleScriptsScope, $codeScope->getLocalScope());
        }

        return $reference;
    }

    /**
     * @param TES5Referencer $calledOn
     * @param TES4Function $function
     * @param TES5CodeScope $codeScope
     * @param TES5GlobalScope $globalScope
     * @param TES5MultipleScriptsScope $multipleScriptsScope
     * @param TES5LocalScope $localScope
     * @return TES4Function|TES5Filler|\Ormin\OBSLexicalParser\TES5\AST\Expression\TES5ArithmeticExpression|\Ormin\OBSLexicalParser\TES5\AST\Expression\TES5LogicalExpression|\Ormin\OBSLexicalParser\TES5\AST\Expression\TES5TrueBooleanExpression|TES5ObjectCall|TES5ObjectProperty|TES5Reference|TES5SelfReference|TES5Bool
     * @throws \Ormin\OBSLexicalParser\TES5\Exception\ConversionException
     */
    public function convertFunction(TES5Referencer $calledOn, TES4Function $function, TES5CodeScope $codeScope, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope, TES5LocalScope $localScope)
    {

        $functionName = $function->getFunctionCall()->getFunctionName();
        $functionArguments = $function->getArguments();

        switch (strtolower($functionName)) {

            case "activate": {

                if (!$functionArguments || $functionArguments->count() == 0) {
                    $constantArgument = new TES5ObjectCallArguments();
                    $meaningVariable = $codeScope->findVariableWithMeaning(TES5LocalVariableParameterMeaning::ACTIVATOR());

                    if ($meaningVariable !== null) {
                        $constantArgument->add($this->referenceFactory->createReferenceToVariable($meaningVariable));
                    } else {
                        $constantArgument->add($this->referenceFactory->createReferenceToPlayer());
                    }

                    $constantArgument->add(new TES5Bool(true)); //Since default in oblivion is ,,skip the OnActivateBlock", this defaults to ,,abDefaultProcessingOnly = true" in Skyrim

                    return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $constantArgument);
                }

                $constantArgument = new TES5ObjectCallArguments();
                $constantArgument->add($this->createValue($functionArguments->getValue(0), $codeScope, $globalScope, $multipleScriptsScope));
                $blockOnActivate = $functionArguments->getValue(1);
                if($blockOnActivate != null) {
                    $blockOnActivateVal = !$blockOnActivate->getData();
                    $constantArgument->add(new TES5Bool($blockOnActivateVal));
                }


                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $constantArgument);
                break;
            }
            case "additem": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "addscriptpackage": {
                $md5 = 'sp_' . substr(md5($globalScope->getScriptHeader()->getScriptName() . $functionArguments->getValue(0)->getData()), 0, 16);

                $this->metadataLogService->add('ADDSCRIPTPACKAGE_SWITCH', [$globalScope->getScriptHeader()->getScriptName(), $functionArguments->getValue(0)->getData(), $md5]);
                $reference = $this->referenceFactory->createReference($md5, $globalScope, $multipleScriptsScope, $localScope);

                $funcArgs = new TES5ObjectCallArguments();
                $funcArgs->add(new TES5Integer(1));

                return $this->createObjectCall($reference, "SetValue",$multipleScriptsScope, $funcArgs);

                break;
            }
            case "addspell": {

                //TODO - This function has two usages in oblivion
                //1) Teach a spell ( addSpell(Spell) )
                //2) Apply a magic effect ( addSpell(MagicEffect) )

                // In second case, we will need to analyze the ESM, to extract the spell which can apply the magic effect

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "addtopic": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $functionName = 'add';
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "autosave": {
                $calledOn = new TES5StaticReference("Game");
                return $this->createObjectCall($calledOn, "RequestAutoSave",$multipleScriptsScope);
                break;
            }
            case "cast": {
                $value = $functionArguments->getValue(0);
                $toBeMethodArgument = $calledOn;
                $calledOn = $this->createReadReference($value->getData(), $globalScope, $multipleScriptsScope, $localScope);


                $methodArguments = new TES5ObjectCallArguments();
                $methodArguments->add($toBeMethodArgument);


                $target = $functionArguments->getValue(1);

                if ($target !== null) {
                    $methodArguments->add($this->createReadReference($target->getData(), $globalScope, $multipleScriptsScope, $localScope));
                }

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $methodArguments);
                break;
            }
            case "clearownership": {
                $functionName = "SetActorOwner";
                $methodArguments = new TES5ObjectCallArguments();
                $methodArguments->add(new TES5None());
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $methodArguments);
            }
            case "closecurrentobliviongate": {
                return new TES5Filler();
                break;
            }
            case "closeobliviongate": {
                return new TES5Filler();
                break;
            }
            case "completequest": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "createfullactorcopy": {
                //We move the called upon to function arg ( cloned object ) and we replace placed upon to player
                $newToken = new TES4ApiToken($calledOn->getName());
                $calledOn = $this->referenceFactory->createReferenceToPlayer();
                $functionName = 'placeAtMe';

                $arguments = new TES5ObjectCallArguments();
                $arguments->add($this->createValue($newToken, $codeScope, $globalScope, $multipleScriptsScope));
                $arguments->add(new TES5Bool(true));

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "deletefullactorcopy": {
                $functionName = 'Delete';
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "disable": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "disablelinkedpathpoints": {

                return new TES5Filler(); //Not used in skyrim

                break;
            }
            case "disableplayercontrols": {
                /* Emulating just the same disable player control as in Oblivion */
                $newArgs = new TES5ObjectCallArguments();
                $newArgs->add(new TES5Bool(true));
                $newArgs->add(new TES5Bool(true));
                $newArgs->add(new TES5Bool(false));
                $newArgs->add(new TES5Bool(false));
                $newArgs->add(new TES5Bool(false));
                $newArgs->add(new TES5Bool(true));
                $newArgs->add(new TES5Bool(true));
                $newArgs->add(new TES5Bool(true));


                $calledOn = new TES5StaticReference("Game");
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $newArgs);
                break;
            }
            case "dispel": {
                $functionName = "DispelSpell";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));

                break;
            }
            case "drop": {
                $functionName = "DropObject";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "dropme": {
                //TODO: Find a getContainer() implementation
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "duplicateallitems": {
                //TODO: Find an proper implementation.
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "enable": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "enablefasttravel": {
                //@INCONSISTENCE @TODO: Entering an interior Cell and then exiting to an exterior will reset Fast Travel to the enabled state.
                $calledOn = new TES5StaticReference("Game");
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "enablelinkedpathpoints": {
                return new TES5Filler(); //Not used in skyrim
                break;
            }
            case "enableplayercontrols": {
                $calledOn = new TES5StaticReference("Game");
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "equipitem": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "essentialdeathreload": {
                //@TODO: Check if strings are available too, since docs say MessageID ( objects ) are used.
                return new TES5Filler(); //todo - think how to solve the fact they can die in game.
                break;
            }
            case "evp":
            case "evaluatepackage": {
                $functionName = "EvaluatePackage";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }

            case "forceactorvalue":
            case "forceav": {
                $convertedArguments = new TES5ObjectCallArguments();

                $firstArg = $functionArguments->getValue(0);

                switch (strtolower($firstArg->getData())) {

                    case 'strength':
                    case 'intelligence':
                    case 'willpower':
                    case 'agility':
                    case 'endurance':
                    case 'personality':
                    case 'luck': {

                        if ($calledOn->getName() != "player") {
                            //We can't convert those.. and shouldn't be any, too.
                            throw new ConversionException("[ForceAV] Cannot set attributes on non-player");
                        }

                        $functionName = "SetValue";
                        $calledOn = $this->referenceFactory->createReference('TES4Attr' . ucwords(strtolower($firstArg->getData())),
                            $globalScope,
                            $multipleScriptsScope,
                            $localScope);

                        $secondArg = $functionArguments->getValue(1);

                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                        break;
                    }

                    case 'speed': {
                        $functionName = "ForceMovementSpeed";
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }


                    case 'fatigue': {
                        $functionName = "ForceActorValue";
                        $convertedArguments->add(new TES5String('Stamina'));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'armorer': {
                        $functionName = "ForceActorValue";

                        $convertedArguments->add(new TES5String("Smithing"));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'security': {
                        $functionName = "ForceActorValue";
                        $convertedArguments->add(new TES5String("Lockpicking"));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'mysticism': { //It doesn't exist in Skyrim - defaulting to Illusion..
                        $functionName = "ForceActorValue";
                        $convertedArguments->add(new TES5String("Illusion"));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'blade':
                    case 'blunt': {
                        $functionName = "ForceActorValue";

                        $convertedArguments->add(new TES5String("OneHanded"));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'aggression': {
                        $functionName = "ForceActorValue";

                        $secondArg = $functionArguments->getValue(1);
                        $secondArgData = $secondArg->getData();
                        if ($secondArgData == 0) {
                            $newValue = 0;
                        } else if ($secondArgData > 0 && $secondArgData < 50) {
                            $newValue = 1;
                        } else if ($secondArgData >= 50 and $secondArgData < 80) {
                            $newValue = 2;
                        } else {
                            $newValue = 3;
                        }

                        $convertedArguments->add(new TES5String($firstArg->getData()));
                        $convertedArguments->add(new TES5Float($newValue));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }


                    case 'confidence': {
                        $functionName = "ForceActorValue";

                        $secondArg = $functionArguments->getValue(1);
                        $secondArgData = $secondArg->getData();
                        if ($secondArgData == 0) {
                            $newValue = 0;
                        } else if ($secondArgData > 0 and $secondArgData < 30) {
                            $newValue = 1;
                        } else if ($secondArgData >= 30 and $secondArgData < 70) {
                            $newValue = 2;
                        } else if ($secondArgData >= 70 and $secondArgData < 99) {
                            $newValue = 3;
                        } else {
                            $newValue = 4;
                        }

                        $convertedArguments->add(new TES5String($firstArg->getData()));
                        $convertedArguments->add(new TES5Float($newValue));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }


                    default: {
                        $functionName = "ForceActorValue";
                        $convertedArguments->add(new TES5String($firstArg->getData()));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                }

                break;
            }
            case "forcecloseobliviongate": {
                return new TES5Filler();
                break;
            }
            case "forceweather":
            case "fw": {
                $calledOn = $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $arguments = new TES5ObjectCallArguments();

                if ($functionArguments->count() == 1) {
                    $force = new TES5Bool(false);
                } else {
                    $force = new TES5Bool((bool)$functionArguments->getValue(1)->getData());
                }

                $arguments->add($force);

                return $this->createObjectCall($calledOn, "ForceActive",$multipleScriptsScope, $arguments);
                break;
            }
            case "getactionref": {
                $activatorVariable = $codeScope->findVariableWithMeaning(TES5LocalVariableParameterMeaning::ACTIVATOR());

                if ($activatorVariable === null) {
                    throw new ConversionException("getActionRef in non-activator scope found. Cannot convert that one.");
                }

                return $this->referenceFactory->createReferenceToVariable($activatorVariable);
                break;
            }

            case "getav":
            case "getactorvalue": {
                //@TODO - This should be fixed on expression-parsing level, with agression and confidence checks adjusted accordingly. There are no retail uses, so im not doing this for now ;)

                $convertedArguments = new TES5ObjectCallArguments();
                $convertedArguments->add(new TES5String($functionArguments->getValue(0)->getData()));

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                break;
            }
            case "getamountsoldstolen": {
                //todo maybe recreate the behavior
                //for now similar thing only
                $argumentsList = new TES5ObjectCallArguments();
                $argumentsList->add(new TES5String("Items Stolen"));
                return $this->createObjectCall(
                    new TES5StaticReference("Game"), "QueryStat", $multipleScriptsScope, $argumentsList
                );

                break;
            }
            case "getangle": {

                switch (strtolower($functionArguments->getValue(0)->getData())) {

                    case 'x':
                    case 'y':
                    case 'z': {

                        $functionName = 'GetAngle' . ucwords($functionArguments->getValue(0)->getData());
                        $functionArguments->popValue(0);

                        break;
                    }
                    default: {
                        throw new ConversionException("getAngle can handle only X,Y,Z parameters.");
                    }

                }

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getarmorrating": {
                /**
                 * @INCONSISTENCE - Will always return 0
                 */
                return new TES5Integer(false);
                //throw new ConversionException("No implementation for GetArmorRating() for now.");
                break;
            }
            case "getattacked": {
                $functionName = 'IsInCombat'; //There is no getAttacked in skyrim.
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }

            case "getbaseactorvalue":
            case "getbaseav": {
                //@TODO - Implement differences in Skill List
                //        Not used for scripts I use, so I omit it for now.
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            //Not done since this point ,rest is like.. manual .

            case "getbuttonpressed": {
                return $this->createReadReference(TES5ReferenceFactory::MESSAGEBOX_VARIABLE_CONST, $globalScope, $multipleScriptsScope, $localScope);
                break;
            }
            case "getclothingvalue": {

                /**
                 * @INCONSISTENCE
                 * a) It returns the total value not the percentage "estimation"
                 * b) Its only for the armor worn, not all eq - can be implemented though..
                 */
                $getArmorWornArg = new TES5ObjectCallArguments();
                $getArmorWornArg->add(new TES5Integer(2));
                return $this->createObjectCall($this->createObjectCall($calledOn, "GetWornForm",$multipleScriptsScope, $getArmorWornArg), "GetGoldValue",$multipleScriptsScope);
                break;
            }
            case "getcombattarget": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getcontainer": {
                //TODO: This function is nonexistent in Papyrus and most likely should be deleted

                $containerVariable = $codeScope->findVariableWithMeaning(TES5LocalVariableParameterMeaning::CONTAINER());

                if ($containerVariable === null) {
                    throw new ConversionException("TES4::getContainer() - Cannot convert to Skyrim in other contextes than onEquip/onUnequip");
                }

                return $this->referenceFactory->createReferenceToVariable($containerVariable);

                break;
            }
            case "getcrime": {
                //TODO: This function is nonexistent in Papyrus and most likely should be deleted
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getcrimegold": {
                //This will sum the bounties from all the factions.
                $calledOn = $this->createReadReference("TES4CyrodiilCrimeFaction", $globalScope, $multipleScriptsScope, $localScope);
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getcrimeknown": {
                //TODO: This function is nonexistent in Papyrus and most likely should be deleted
                //@INCONSISTENCE - Will always return false
                return new TES5Bool(false);
                //return $this->createObjectCall($calledOn, $functionName, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getcurrentaipackage": {

                return $this->createObjectCall($calledOn, "GetCurrentPackage",$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getcurrentaiprocedure": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getcurrenttime": {
                return $this->createReadReference("GameHour", $globalScope, $multipleScriptsScope, $localScope);

            }
            case "getdayofweek": {
                $calledOn = $this->createReadReference("tTimer", $globalScope, $multipleScriptsScope, $localScope);
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            //Do since here
            case "getdead": {
                $functionName = "IsDead";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getdeadcount": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getdestroyed": {
                $functionName = "isActivationBlocked";
                #               throw new ConversionException("GetDestroyed() is not accessible via Papyrus.");
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getdetected": {
                $value = $functionArguments->getValue(0);
                $functionArguments->setValue(0, new TES4ApiToken($calledOn->getName()));
                $calledOn = $this->createReadReference($value->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $functionName = 'IsDetectedBy';
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }

            case "getdisabled": {
                $functionName = "IsDisabled";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getdisposition": {

                /**
                 * @TODO - Create a disposition system in Skyrim
                 * For now - hardcoded 100.
                 */
                return new TES5Integer(100);

//                throw new ConversionException("GetDisposition() is not accessible via Papyrus.");
                break;
            }
            case "getdistance": {

                //Sometimes, its referenced as a string in code, so we force cast it to a reference.
                $arguments = new TES5ObjectCallArguments();
                $arguments->add($this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $codeScope->getLocalScope()));

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $arguments);
                break;
            }
            case "getequipped": {
                $functionName = 'isEquipped';
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getfactionrank": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getforcesneak": {
                //@inconsistence - Stub
                return new TES5Bool(false);
                break;
            }
            case "getgamesetting":
            case "getgs": {
                $setting = $functionArguments->popValue(0)->getData();

                switch (strtolower($setting)) {

                    case 'icrimegoldattackmin':
                    case 'icrimegoldattack': {
                        return new TES5Integer(25);
                        break;
                    }

                    case 'icrimegoldjailbreak': {
                        return new TES5Integer(50);
                        break;
                    }

                    default: {
                        throw new ConversionException("GetGameSetting() - unknown setting.");
                    }


                }

                break;
            }
            case "getgold": {
                $functionName = "GetGoldAmount";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getheadingangle": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getincell": {

                $apiToken = $functionArguments->getValue(0);
                $length = strlen($apiToken->getData()); //Get the length of the match
                $argumentsList = new TES5ObjectCallArguments();
                $argumentsList->add(
                    $this->createObjectCall(
                        $calledOn, "GetParentCell",$multipleScriptsScope
                    )
                );

                $argumentsList->add(new TES5Integer(0));
                $argumentsList->add(new TES5Integer($length));

                $parentCellCheck =

                    $this->createObjectCall(
                        $this->referenceFactory->createReferenceToStaticClass("StringUtil"), "Substring", $multipleScriptsScope, $argumentsList
                    );


                $checkAgainst = new TES5String($apiToken->getData());
                return $this->expressionFactory->createArithmeticExpression($parentCellCheck, TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(), $checkAgainst);


            }
            case "getinfaction": {
                //todo - in magic effect script blocks, default called on is the actual caster.
                $functionName = "IsInFaction";

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getinsamecell": {

                $functionThis = $this->createObjectCall(
                    $calledOn, "GetParentCell",$multipleScriptsScope
                );

                $functionArgument = $this->createObjectCall(
                    $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope), "GetParentCell",$multipleScriptsScope
                );

                $expression = $this->expressionFactory->createArithmeticExpression(
                    $functionThis,
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    $functionArgument
                );

                return $expression;
                break;
            }
            case "getinworldspace": {
                $functionThis = $this->createObjectCall(
                    $calledOn, "GetWorldSpace",$multipleScriptsScope
                );

                $argument = $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $expression = $this->expressionFactory->createArithmeticExpression(
                    $functionThis,
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    $argument
                );

                return $expression;
            }
            case "getisalerted": {
                $functionName = "IsAlerted";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getiscurrentpackage": {
                $functionThis = $this->createObjectCall(
                    $calledOn, "GetCurrentPackage",$multipleScriptsScope
                );

                $argument = $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $expression = $this->expressionFactory->createArithmeticExpression(
                    $functionThis,
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    $argument
                );

                return $expression;
            }
            case "getiscurrentweather": {
                //Made in post-analysis
                $functionThis = $this->createObjectCall(
                    new TES5StaticReference("Weather"), "GetCurrentWeather",$multipleScriptsScope
                );

                $argument = $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $expression = $this->expressionFactory->createArithmeticExpression(
                    $functionThis,
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    $argument
                );

                return $expression;
            }
            case "getisid": {
                //Made in post-analysis
                $functionThis = $this->createObjectCall(
                    $calledOn, "GetBaseObject",$multipleScriptsScope
                );

                $argument = $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $expression = $this->expressionFactory->createArithmeticExpression(
                    $functionThis,
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    $argument
                );

                return $expression;
            }
            case "getisplayablerace": {

                throw new ConversionException("Not supported");
            }
            case "getisplayerbirthsign": {
                throw new ConversionException("Birthsigns are not used in Skyrim.");
                break;
            }
            case "getisrace": {
                //Made in post-analysis
                $functionThis = $this->createObjectCall(
                    $calledOn, "GetRace",$multipleScriptsScope
                );

                $argument = $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $expression = $this->expressionFactory->createArithmeticExpression(
                    $functionThis,
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    $argument
                );

                return $expression;
                break;
            }
            case "getisreference": {

                $argument = $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $expression = $this->expressionFactory->createArithmeticExpression(
                    $calledOn,
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    $argument
                );

                return $expression;

            }
            case "getissex": {

                $functionThis = $this->createObjectCall(
                    $this->createObjectCall(
                        $calledOn, "GetBaseObject",$multipleScriptsScope
                    ), "GetSex",$multipleScriptsScope
                );

                switch (strtolower($functionArguments->getValue(0)->getData())) {

                    case "male": {
                        $operand = 0;
                        break;
                    }

                    case "female": {
                        $operand = 1;
                        break;
                    }

                    default: {

                        throw new ConversionException("GetIsSex used with unknown gender.");
                    }

                }

                $expression = $this->expressionFactory->createArithmeticExpression(
                    $functionThis,
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    new TES5Integer($operand)
                );


                return $expression;
                break;
            }
            case "getitemcount": {
                $arguments = new TES5ObjectCallArguments();
                $arguments->add($this->createReadReference($functionArguments->getValue(0)->getData(),$globalScope,$multipleScriptsScope,$localScope));
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $arguments);
                break;
            }
            case "getknockedstate": {
                throw new ConversionException("GetKnockedState() is not available via Papyrus.");
                break;
            }
            case "getlevel": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getlocked": {
                $functionName = "isLocked";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getlos": {
                $functionName = "HasLOS";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getopenstate": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getparentref": {
                $functionName = 'GetEnableParent'; //SKSE
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
            }
            case "getpcexpelled": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $functionName = 'isPlayerExpelled';
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }

            case "getpcfactionmurder":
            case "getpcfactionattack": {

                //WARNING: This is not an exact implementation
                //According to cs.elderscrolls.com, its about being in the faction AND having an attack on them ( violent crime )
                //It's similar but groups all violent wrongdoings ( including assaults, murders etc ).

                $factionReference = $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $arguments = new TES5ObjectCallArguments();
                $arguments->add($factionReference);

                $function = $this->createObjectCall(
                    $this->referenceFactory->createReferenceToPlayer(), "IsInFaction", $multipleScriptsScope, $arguments
                );


                $left_expression = $this->expressionFactory->createTrueBooleanExpression($function);
                $function = $this->createObjectCall(
                    $factionReference, "GetCrimeGoldViolent",$multipleScriptsScope
                );

                $right_expression = $this->expressionFactory->createArithmeticExpression(
                    $function,
                    TES5ArithmeticExpressionOperator::OPERATOR_GREATER(),
                    new TES5Integer(0)
                );


                $logicalExpression = $this->expressionFactory->createLogicalExpression(

                    $left_expression,
                    TES5LogicalExpressionOperator::OPERATOR_AND(),
                    $right_expression

                );

                return $logicalExpression;

            }


            case "getpcfactionsteal": {

                //WARNING: This is not an exact implementation
                //According to cs.elderscrolls.com, its about being in the faction AND having an attack on them ( violent crime )
                //It's similar but groups all violent wrongdoings ( including assaults, murders etc ).

                $factionReference = $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $arguments = new TES5ObjectCallArguments();
                $arguments->add($factionReference);

                $function = $this->createObjectCall(
                    $this->referenceFactory->createReferenceToPlayer(), "IsInFaction", $multipleScriptsScope, $arguments
                );


                $left_expression = $this->expressionFactory->createTrueBooleanExpression($function);
                $function = $this->createObjectCall(
                    $factionReference, "GetCrimeGoldNonViolent",$multipleScriptsScope
                );

                $right_expression = $this->expressionFactory->createArithmeticExpression(
                    $function,
                    TES5ArithmeticExpressionOperator::OPERATOR_GREATER(),
                    new TES5Integer(0)
                );


                $logicalExpression = $this->expressionFactory->createLogicalExpression(

                    $left_expression,
                    TES5LogicalExpressionOperator::OPERATOR_AND(),
                    $right_expression

                );

                return $logicalExpression;
            }
            case "getpcfame": {
                //                return TES4Factories::createReference('Fame',$this);
                return $this->createReadReference("Fame", $globalScope, $multipleScriptsScope, $localScope);
            }
            case "getpcinfamy": {
                return $this->createReadReference("PCInfamy", $globalScope, $multipleScriptsScope, $localScope);
            }
            case "getpcisrace": {
                //Made in post-analysis

                $race = $this->createObjectCall(
                    $this->referenceFactory->createReferenceToPlayer(), "GetRace",$multipleScriptsScope
                );

                $checkAgainst = $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);


                $expression = $this->expressionFactory->createArithmeticExpression(
                    $race,
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    $checkAgainst
                );

                return $expression;
                break;
            }
            case "getpcissex": {

                $functionThis = $this->createObjectCall(
                    $this->createObjectCall(
                        $this->referenceFactory->createReferenceToPlayer(), "GetBaseObject",$multipleScriptsScope
                    ), "GetSex",$multipleScriptsScope
                );

                switch (strtolower($functionArguments->getValue(0)->getData())) {

                    case "male": {
                        $operand = 0;
                        break;
                    }

                    case "female": {
                        $operand = 1;
                        break;
                    }

                    default: {

                        throw new ConversionException("GetIsSex used with unknown gender.");
                    }

                }

                $expression = $this->expressionFactory->createArithmeticExpression(
                    $functionThis,
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    new TES5Integer($operand)
                );


                return $expression;
            }
            case "getpcmiscstat": {
                throw new ConversionException("GetPCMiscStat() isn't available from Papyrus.");
                break;
            }
            case "getplayercontrolsdisabled": {
                return new TES5Bool(false);
                //throw new ConversionException("GetPlayerControlsDisabled() isn't available from Papyrus");
                break;
            }
            case "getpos": {

                switch (strtolower($functionArguments->getValue(0)->getData())) {

                    case 'x':
                    case 'y':
                    case 'z': {

                        $functionName = 'GetPosition' . ucwords($functionArguments->getValue(0)->getData());
                        $functionArguments->popValue(0);
                        break;
                    }
                    default: {
                        throw new ConversionException("getPos can handle only X,Y,Z parameters.");
                    }

                }

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getquestrunning": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $functionName = "IsRunning";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getrandompercent": {
                $functionName = "RandomInt";
                $calledOn = new TES5StaticReference("Utility");

                $methodArguments = new TES5ObjectCallArguments();
                $methodArguments->add(new TES5Integer(0));
                $methodArguments->add(new TES5Integer(99));

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $methodArguments);
                break;
            }
            case "getrestrained": {
                return $this->createObjectCall($calledOn, "GetRestrained",$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getsecondspassed":
            case "scripteffectelapsedseconds": {
                $timerReference = $this->createReadReference("tTimer", $globalScope, $multipleScriptsScope, $localScope);
                $localTimeReference = $this->createReadReference('tGSPLocalTimer', $globalScope, $multipleScriptsScope, $localScope);
                $methodArguments = new TES5ObjectCallArguments();
                $methodArguments->add($localTimeReference);
                return $this->createObjectCall($timerReference, "getSecondsPassed",$multipleScriptsScope, $methodArguments);
            }
            case "this":
            case "getself": {

                //Todo - change the naming as this is the true ,,getSelf"
                //Thing is that in non-reference contexts when using this method it will return the appropiate target reference as it would be targeted in Oblivion.
                return $this->extractImplicitReference($globalScope, $multipleScriptsScope, $localScope);
                break;
            }
            case "getshouldattack": {
                //@inconsistence - stub
                return new TES5Bool(false);
                break;
            }
            //Done as of 28.6.2013 1:45
            case "getsleeping": {
                $functionName = "GetSleepState";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getstage": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getstagedone": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getstartingangle": {
                //Will be reanalyzed in post-analysis, those are usually used for reference resettings.
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getstartingpos": {
                //Will be reanalyzed in post-analysis, those are usually used for reference resettings.
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "getweaponanimtype": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "hasmagiceffect": {

                $newArgs = new TES5ObjectCallArguments();
                $newArgs->add($this->referenceFactory->createReference("Effect" . $functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope));
                /*
                                switch ($functionArguments->getValue(0)->getData()) {

                                    case "INVI": {
                                        $newArgs->add($this->referenceFactory->createReference("InvisibillityFFSelf", $globalScope, $multipleScriptsScope, $localScope));
                                        break;
                                    }

                                    case 'REFA': {
                                        $newArgs->add($this->referenceFactory->createReference("PerkRestoreStaminaFFSelf", $globalScope, $multipleScriptsScope, $localScope));
                                        break;
                                    }

                                    default: {
                                        $newArgs->add($this->referenceFactory->createReference("Effect".$functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope));
                                        break;
                                    }

                                }
                */
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $newArgs);
                break;
            }
            case "hasvampirefed": {
                //@inconsistence - stub
                return new TES5Bool(false);
                break;
            }
            case "isactionref": {
                $activatorVariable = $codeScope->findVariableWithMeaning(TES5LocalVariableParameterMeaning::ACTIVATOR());

                if ($activatorVariable === null) {
                    throw new ConversionException("isActionRef called in a context where action ref should not be present");
                }

                $expression = $this->expressionFactory->createArithmeticExpression(
                    $this->referenceFactory->createReferenceToVariable($activatorVariable),
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope)

                );

                return $expression;
                //  return TES4Factories::createFunctionFromRegex($data,$this,"bool");
                break;
            }
            case "isactor":
            case "getiscreature": // Not really accurate.
            {
                //@INCONSISTENCE - if used in a context, which will be inferenced to actor but is not yet while this is executed, it will return a bad value.
                $boolean = new TES5Bool(!($calledOn->getType() == TES5BasicType::T_ACTOR() || TES5InheritanceGraphAnalyzer::isExtending($calledOn->getType(), TES5BasicType::T_ACTOR())));

                return $boolean; //return $const;
            }
            case "isactorusingatorch": {
                return $this->createObjectCall($calledOn, "IsTorchOut",$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "isanimplaying": {
                return $this->createObjectCall($calledOn, "IsAnimPlaying",$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "isessential": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "isguard": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }

            case "isactordetected": //NOT ACCURATE , but there's no better way to do it in skyrim
            case "isincombat": {
                return $this->createObjectCall($calledOn, "IsInCombat",$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "isindangerouswater": {
                //@INCONSISTENCE - What is that function, rofl. Not supported by skyrim obviously
                return new TES5Bool(false);
                break;
            }
            case "isininterior": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "isowner": {
                $targetReference = $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $datatype = ESMAnalyzer::instance()->getFormTypeByEDID($functionArguments->getValue(0)->getData());
                if($datatype == TES5BasicType::T_FACTION()) {
                    $owner = $this->createObjectCall(
                        $calledOn, "GetFactionOwner",$multipleScriptsScope
                    );
                    $base = $targetReference;

                } else {

                    $owner = $this->createObjectCall(
                        $calledOn, "GetActorOwner",$multipleScriptsScope
                    );

                    $base = $this->createObjectCall(
                        $targetReference, "GetActorBase",$multipleScriptsScope
                    );

                }

                $expression = $this->expressionFactory->createArithmeticExpression(
                    $owner,
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    $base

                );

                return $expression;
            }
            case "ispcamurderer": {

                //Using Legacy TES4 Connector Plugin
                return $this->objectPropertyFactory->createObjectProperty($multipleScriptsScope, $this->createReadReference("tContainer", $globalScope, $multipleScriptsScope, $localScope), "isMurderer");
                break;
            }
            case "ispcsleeping": {

                return $this->expressionFactory->createArithmeticExpression(

                    $this->createObjectCall(
                        $this->referenceFactory->createReferenceToPlayer(), "getSleepState",$multipleScriptsScope
                    ),
                    TES5ArithmeticExpressionOperator::OPERATOR_GREATER(),
                    new TES5Integer(2)

                );

            }
            case "isplayerinjail": {

                //Using Legacy TES4 Connector Plugin
                return $this->objectPropertyFactory->createObjectProperty($multipleScriptsScope, $this->createReadReference("tContainer", $globalScope, $multipleScriptsScope, $localScope), "isInJail");
            }
            case "israining": {

                return $this->expressionFactory->createArithmeticExpression(

                    $this->createObjectCall(

                        $this->createObjectCall(
                            new TES5StaticReference("Weather"), "GetCurrentWeather",$multipleScriptsScope
                        ), "GetClassification",$multipleScriptsScope

                    ),
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    new TES5Integer(2)

                );

            }
            case "isridinghorse": {
                $functionName = "IsOnMount";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "issneaking": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "isspelltarget": {

                //@INCONSISTENCE - Will only check for scripted effects
                //In oblivion, this is checking for a spell which targeted a given actor
                //In Skyrim you can check for effects only.
                $newArgs = new TES5ObjectCallArguments();
                $newArgs->add($this->referenceFactory->createReference("EffectSEFF", $globalScope, $multipleScriptsScope, $localScope));


                return $this->createObjectCall($calledOn, "HasMagicEffect",$multipleScriptsScope, $newArgs);
                break;
            }
            case "isswimming": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "istalking": {

                $functionName = "IsInDialogueWithPlayer";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;


            }
            case "istimepassing": {
                return new TES5Bool(true);
            }
            case "isweaponout": {
                $functionName = "IsWeaponDrawn";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "isxbox": {
                //Really..
                return new TES5Bool(false);
                break;
            }
            case "kill": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "lock": {
                $methodArguments = new TES5ObjectCallArguments();
                $methodArguments->add(new TES5Bool(true)); //override different behaviour

                $lockAsOwner = $functionArguments->getValue(1);
                $lock = false;
                if ($lockAsOwner !== null) {
                    if ((bool)$lockAsOwner->getData()) {
                        $lock = true;
                    }
                }

                $methodArguments->add(new TES5Bool($lock));

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $methodArguments);
                break;
            }
            case "look": {
                $functionName = "SetLookAt";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }

            case "menumode": {
                return new TES5Integer(0); //Always not in menu mode ;)
            }

            case "message": {

                $messageString = $functionArguments->getValue(0)->getData();
                preg_match_all("#%([ +-0]*[1-9]*\.[0-9]+[ef]|g)#", $messageString, $messageMatches, PREG_OFFSET_CAPTURE);

                $floatPointArgumentsCount = count($messageMatches[0]);
                if ($floatPointArgumentsCount > 0) {
                    if (!$functionArguments->getValue(0) instanceof TES4String) { //jesus its hacky
                        throw new ConversionException("Cannot transform printf like syntax to concat on string loaded dynamically");
                    }

                    $i = 0;
                    $caret = 0;

                    $functionArguments->popValue(0); //We don't need first value.
                    //Example call: You have %.2f apples and %g boxes in your inventory, applesCount, boxesCount
                    $variablesStack = [];
                    foreach ($functionArguments->getValues() as $variable) {
                        $variablesStack[] = $this->createValue($variable, $codeScope, $globalScope, $multipleScriptsScope);
                    }

                    $stringsStack = []; //Target: "You have ", " apples and ", " boxes in your inventory"

                    $startWithVariable = false; //Pretty ugly. Basically, if we start with a vairable, it should be pushed first from the variable stack and then string comes, instead of string , variable , and so on [...]

                    while ($caret < strlen($messageString)) {

                        $stringBeforeStart = $caret; //Set the start on the caret.

                        if (isset($messageMatches[0][$i])) {

                            $stringBeforeEnd = $messageMatches[$i][0][1];
                            $length = $stringBeforeEnd - $stringBeforeStart;

                            if ($caret == 0 && $length == 0) {
                                $startWithVariable = true;
                            }

                            if ($length > 0) {
                                $stringsStack[] = new TES5String(substr($messageString, $stringBeforeStart, $length));
                                $caret += $length;
                            }

                            $caret += strlen($messageMatches[$i][0][0]);
                        } else {

                            $stringsStack[] = new TES5String(substr($messageString, $stringBeforeStart));
                            $caret = strlen($messageString);
                        }

                        ++$i;
                    }

                    $combinedValues = [];
                    $stringsStack = array_reverse($stringsStack);
                    $variablesStack = array_reverse($variablesStack);

                    if ($startWithVariable) {
                        if ($variableToGo = array_pop($variablesStack)) {
                            $combinedValues[] = $variableToGo;
                        }
                    }

                    while ($string = array_pop($stringsStack)) {
                        $combinedValues[] = $string;
                        if ($variableToGo = array_pop($variablesStack)) {
                            $combinedValues[] = $variableToGo;
                        }
                    }

                    $calledOn = new TES5StaticReference("Debug");
                    $arguments = new TES5ObjectCallArguments();
                    $arguments->add($this->primitiveValueFactory->createConcatenatedValue($combinedValues));
                    return $this->createObjectCall($calledOn, "Notification",$multipleScriptsScope, $arguments);

                } else {
                    $calledOn = new TES5StaticReference("Debug");
                    $arguments = new TES5ObjectCallArguments();
                    $arguments->add($this->createValue($functionArguments->getValue(0), $codeScope, $globalScope, $multipleScriptsScope));
                    return $this->createObjectCall($calledOn, "Notification",$multipleScriptsScope, $arguments);
                }
            }

            case "messagebox": {

                //todo Refactor - add floating point vars .
                if ($functionArguments->count() == 1) {
                    $calledOn = new TES5StaticReference("Debug");
                    return $this->createObjectCall($calledOn, "MessageBox",$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                } else {

                    $messageArguments = [];

                    $edid = "TES4MessageBox" . md5(serialize($functionArguments->getValues()));

                    $messageArguments[] = $edid;

                    for ($i = 0; $i < $functionArguments->count(); ++$i) {
                        $messageArguments[] = $functionArguments->getValue($i)->getData();
                    }

                    $this->metadataLogService->add('ADD_MESSAGE', $messageArguments);

                    $messageBoxResult = $this->createReadReference(TES5ReferenceFactory::MESSAGEBOX_VARIABLE_CONST, $globalScope, $multipleScriptsScope, $localScope);

                    $reference = $this->createReadReference($edid, $globalScope, $multipleScriptsScope, $localScope);

                    return $this->assignationFactory->createAssignation($messageBoxResult, $this->createObjectCall($reference, "show",$multipleScriptsScope));

                }

                break;
            }

            case "modpcskill":
            case "modactorvalue":
            case "modav": {

                $convertedArguments = new TES5ObjectCallArguments();

                $firstArg = $functionArguments->getValue(0);

                switch (strtolower($firstArg->getData())) {

                    case 'strength':
                    case 'intelligence':
                    case 'willpower':
                    case 'agility':
                    case 'speed':
                    case 'endurance':
                    case 'personality':
                    case 'luck': {

                        if ($calledOn->getName() != "player") {
                            //We can't convert those.. and shouldn't be any, too.
                            throw new ConversionException("[ModAV] Cannot set attributes on non-player");
                        }

                        $functionName = "SetValue";
                        $calledOn = $this->referenceFactory->createReference('TES4Attr' . ucwords(strtolower($firstArg->getData())),
                            $globalScope,
                            $multipleScriptsScope,
                            $localScope);

                        $secondArg = $functionArguments->getValue(1);

                        $addedValue = $this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope);

                        $convertedArguments->add($this->expressionFactory->createBinaryExpression($addedValue, TES5BinaryExpressionOperator::OPERATOR_ADD(), $this->createReadReference('TES4Attr' . ucwords(strtolower($firstArg->getData())),
                            $globalScope,
                            $multipleScriptsScope,
                            $localScope)));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                        break;
                    }

                    case 'fatigue': {
                        $functionName = "ModActorValue";
                        $convertedArguments->add(new TES5String('Stamina'));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'armorer': {
                        $functionName = "ModActorValue";
                        $convertedArguments->add(new TES5String("Smithing"));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'security': {
                        $functionName = "ModActorValue";
                        $convertedArguments->add(new TES5String("Lockpicking"));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'mysticism': { //It doesn't exist in Skyrim - defaulting to Illusion..
                        $functionName = "ModActorValue";
                        $convertedArguments->add(new TES5String("Illusion"));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'blade':
                    case 'blunt': {
                        $functionName = "ModActorValue";
                        $convertedArguments->add(new TES5String("OneHanded"));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'aggression': {
                        $functionName = "ModActorValue";

                        $secondArg = $functionArguments->getValue(1);
                        $secondArgData = $secondArg->getData();

                        if ($secondArgData < -80) {
                            $newValue = -3;
                        } else if ($secondArgData >= -80 && $secondArgData < -50) {
                            $newValue = -2;
                        } else if ($secondArgData >= -50 and $secondArgData < 0) {
                            $newValue = -1;
                        } else if ($secondArgData == 0) {
                            $newValue = 0;
                        } else if ($secondArgData > 0 && $secondArgData < 50) {
                            $newValue = 1;
                        } else if ($secondArgData >= 50 and $secondArgData < 80) {
                            $newValue = 2;
                        } else {
                            $newValue = 3;
                        }

                        $convertedArguments->add(new TES5String($firstArg->getData()));
                        $convertedArguments->add(new TES5Float($newValue));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }


                    case 'confidence': {
                        $functionName = "ModActorValue";

                        $secondArg = $functionArguments->getValue(1);
                        $secondArgData = $secondArg->getData();

                        if ($secondArgData == -100) {
                            $newValue = -4;
                        } else if ($secondArgData <= -70 and $secondArgData > -100) {
                            $newValue = -3;
                        } else if ($secondArgData <= -30 and $secondArgData > -70) {
                            $newValue = -2;
                        } else if ($secondArgData < 0 and $secondArgData > -30) {
                            $newValue = -1;
                        } else if ($secondArgData == 0) {
                            $newValue = 0;
                        } else if ($secondArgData > 0 and $secondArgData < 30) {
                            $newValue = 1;
                        } else if ($secondArgData >= 30 and $secondArgData < 70) {
                            $newValue = 2;
                        } else if ($secondArgData >= 70 and $secondArgData < 99) {
                            $newValue = 3;
                        } else {
                            $newValue = 4;
                        }

                        $convertedArguments->add(new TES5String($firstArg->getData()));
                        $convertedArguments->add(new TES5Float($newValue));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    default: {
                        $functionName = "ModActorValue";
                        $convertedArguments->add(new TES5String($firstArg->getData()));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                }

                break;
            }
            case "modcrimegold": {
                $calledOn = $this->createReadReference("TES4CyrodiilCrimeFaction", $globalScope, $multipleScriptsScope, $localScope);
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "moddisposition": {
                //Faction Reactions are Per-Faction not per-Actor, so we just simulate what would potentially happen in Skyrim

                switch ($functionArguments->getValue(1)->getData()) {

                    case "-100": {

                        $functionName = 'StartCombat';
                        $functionArguments->popValue(1);
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));

                    }
                    default: {
                        return new TES5Filler();
                    }

                }

                break;
            }
            case "modfactionreaction": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $functionName = "modReaction";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "modpcfame": {

                $fameReference = $this->createReadReference('Fame', $globalScope, $multipleScriptsScope, $localScope);

                $fameArguments = new TES5ObjectCallArguments();

                $binaryExpression = $this->expressionFactory->createBinaryExpression(
                    $fameReference,
                    TES5BinaryExpressionOperator::OPERATOR_ADD(),
                    new TES5Integer($functionArguments->getValue(0)->getData())
                );

                $fameArguments->add(
                    $binaryExpression
                );

                $function = $this->createObjectCall(

                    $this->referenceFactory->createReference('Fame', $globalScope, $multipleScriptsScope, $localScope), "SetValue", $multipleScriptsScope, $fameArguments

                );

                return $function;
            }
            case "modpcinfamy": {

                $fameReference = $this->createReadReference('Infamy', $globalScope, $multipleScriptsScope, $localScope);

                $fameArguments = new TES5ObjectCallArguments();

                $binaryExpression = $this->expressionFactory->createBinaryExpression(
                    $fameReference,
                    TES5BinaryExpressionOperator::OPERATOR_ADD(),
                    new TES5Integer($functionArguments->getValue(0)->getData())
                );

                $fameArguments->add(
                    $binaryExpression
                );

                $function = $this->createObjectCall(

                    $this->referenceFactory->createReference('Infamy', $globalScope, $multipleScriptsScope, $localScope), "SetValue", $multipleScriptsScope, $fameArguments

                );

                return $function;
            }


            case "modpcmiscstat": {
                return new TES5Filler();
                //    return $this->createObjectCall($calledOn,$functionName,$this->createArgumentList($functionArguments,$propertyList));
                break;
            }
            case "moveto": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "movetomarker": {
                $functionName = 'MoveTo';
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "payfine": {
                $player = $this->referenceFactory->createReferenceToPlayer();
                $faction = $this->createReadReference("TES4CyrodiilCrimeFaction", $globalScope, $multipleScriptsScope, $localScope);
                $functionName = "PayCrimeGold";
                $argumentList = new TES5ObjectCallArguments();
                $argumentList->add(new TES5Integer(1));
                $argumentList->add(new TES5Integer(1));
                $argumentList->add($faction);

                return $this->createObjectCall($player, $functionName,$multipleScriptsScope, $argumentList);

            }
            case "pickidle": {
                //TODO: Check if automatically picks idle
                return new TES5Filler();

                break;
            }
            case "placeatme": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "playgroup": {
                $functionName = "playGamebryoAnimation";

                //this function does not use strings for the names, so i cant really understand what is it.
                //todo refactor

                $convertedArguments = new TES5ObjectCallArguments();

                $firstArg = $functionArguments->getValue(0);
                $convertedArguments->add(new TES5String($firstArg->getData()));

                /*
                $secondArg = $functionArguments->getValue(1);

                if ($secondArg && $secondArg->getData() != 0) {
                    $convertedArguments->add(new TES5Integer(1));
                }*/
                $convertedArguments->add(new TES5Bool(true));

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                break;
            }
            case "playmagiceffectvisuals":
            case "pme": {
                //@TODO
                return new TES5Filler();
                # return $this->createObjectCall($calledOn,$functionName,$this->createArgumentList($functionArguments,$propertyList));
                break;
            }
            case "playmagicshadervisuals":
            case "pms": {
                //@TODO
                return new TES5Filler();
                # return $this->createObjectCall($calledOn,$functionName,$this->createArgumentList($functionArguments,$propertyList));
                break;
            }
            case "playsound": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $functionName = "play";
                $args = new TES5ObjectCallArguments();
                $args->add($this->referenceFactory->createReferenceToPlayer());
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $args);
                break;
            }
            case "playsound3d": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $functionName = "play";
                $args = new TES5ObjectCallArguments();
                $args->add($this->referenceFactory->createReferenceToSelf($globalScope));
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $args);
                break;
            }

            case "purgecellbuffers":
            case "pcb": {
                //@INCONSISTENT - is not here in Skyrim. Maybe readd it via SKSE plugin?
                return new TES5Filler();
                break;
            }

            /**
             * @TODO ( not used in original scripts )
             */
            case "pushactoraway": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "refreshtopiclist": {
                return new TES5Filler();
                break;
            }
            case "releaseweatheroverride": {
                return $this->createObjectCall($this->referenceFactory->createReferenceToStaticClass("Weather"), "ReleaseOverride",$multipleScriptsScope, new TES5ObjectCallArguments());
                break;
            }
            /**
             * END TODO
             */

            case "removeallitems": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "removeitem": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "removeme": {
                $calledOn = $this->referenceFactory->createReferenceToSelf($globalScope);
                $functionName = 'Delete';

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "removescriptpackage": {
                return new TES5Filler(); //todo - think how this could work, i think its not necessary

            }
            case "removespell": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }

            case "resetinterior": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $functionName = "Reset";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }


            case "reset3dstate": {
                $functionName = "Reset";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "resetfalldamagetimer": {
                //@INCONSISTENCE - Well, see those faces on TG11 Heist when jumping with the boost will make them fall to death..
                return new TES5Filler();
                break;
            }
            case "resethealth": {
                //Healing to full hp?
                $functionName = "RestoreActorValue";
                $convertedArguments = new TES5ObjectCallArguments();
                $convertedArguments->add(new TES5String("Health"));
                $convertedArguments->add(new TES5Integer(9999));

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                break;
            }
            case "resurrect": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, new TES5ObjectCallArguments());
                break;
            }
            case "rotate": {
                $x = 0;
                $y = 0;
                $z = 0;
                switch (strtolower($functionArguments->getValue(0)->getData())) {

                    case "x": {
                        $x = $functionArguments->getValue(1)->getData();
                        break;
                    }

                    case "y": {
                        $y = $functionArguments->getValue(1)->getData();
                        break;
                    }


                    case "z": {
                        $z = $functionArguments->getValue(1)->getData();
                        break;
                    }

                }

                $rotateArguments = new TES5ObjectCallArguments();
                $rotateArguments->add($calledOn);
                $rotateArguments->add(new TES5Integer($x));
                $rotateArguments->add(new TES5Integer($y));
                $rotateArguments->add(new TES5Integer($z));

                $function = $this->createObjectCall(
                    $this->createReadReference("tTimer", $globalScope, $multipleScriptsScope, $localScope), "Rotate", $multipleScriptsScope, $rotateArguments
                );

                return $function;
            }
            case "say": {

                $arguments = new TES5ObjectCallArguments();
                $arguments->add($calledOn);
                $arguments->add($this->createValue($functionArguments->getValue(0), $codeScope, $globalScope, $multipleScriptsScope));


                /*
                 * Deprecated - causes problems.
                $optionalFlag = $functionArguments->getValue(2);
                if ($optionalFlag !== null) {
                    $arguments->add($this->createValue($functionArguments->getValue(2), $codeScope, $globalScope, $multipleScriptsScope));
                } else {
                    $arguments->add(new TES5None());
                }
                */
                $arguments->add(new TES5None());
                $arguments->add(new TES5Bool(true));

                $timerReference = $this->createReadReference("tTimer", $globalScope, $multipleScriptsScope, $localScope);

                return $this->createObjectCall($timerReference, "LegacySay",$multipleScriptsScope, $arguments);
                break;
            }
            case "sayto": {
                //Simple implementation without looking.
                $arguments = new TES5ObjectCallArguments();
                $arguments->add($calledOn);
                $arguments->add($this->createValue($functionArguments->getValue(1), $codeScope, $globalScope, $multipleScriptsScope));
                $arguments->add(new TES5None());
                $arguments->add(new TES5Bool(true));

                $timerReference = $this->createReadReference("tTimer", $globalScope, $multipleScriptsScope, $localScope);

                return $this->createObjectCall($timerReference, "LegacySay",$multipleScriptsScope, $arguments);
                break;
            }
            case "sendtrespassalarm": {
                //@INCONSISTENCE - not implemented.
                return new TES5Filler();
                //throw new ConversionException("SendTrespassAlarm() not implemented.");
                break;
            }
            case "setactoralpha":
            case "saa": {
                $functionName = "SetAlpha";
                $functionArguments->setValue(1, new TES4Integer(1));

                $functionArgs = new TES5ObjectCallArguments();
                $functionArgs->add($this->createValue($functionArguments->getValue(0), $codeScope, $globalScope, $multipleScriptsScope));
                $functionArgs->add(new TES5Bool(true));

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $functionArgs);
                break;
            }
            case "setactorfullname": {
                //@INCONSISTENCE - Can be done, by porting the SetActorFullName <MESG> signature from Obscript into SKSE
                return new TES5Filler();
//                return $this->createObjectCall($calledOn, $functionName, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setactorrefraction": {
                //Isn't that same as alpha??
                return new TES5Filler();
                break;
            }
            case "setactorsai": {
                return $this->createObjectCall($calledOn, "EnableAI",$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setactorvalue":
            case "setav": {

                $convertedArguments = new TES5ObjectCallArguments();

                $firstArg = $functionArguments->getValue(0);

                switch (strtolower($firstArg->getData())) {

                    case 'strength':
                    case 'intelligence':
                    case 'willpower':
                    case 'agility':
                    case 'endurance':
                    case 'personality':
                    case 'luck': {

                        if ($calledOn->getName() != "player") {
                            //We can't convert those.. and shouldn't be any, too.
                            throw new ConversionException("[SetAV] Cannot set attributes on non-player");
                        }

                        $functionName = "SetValue";
                        $calledOn = $this->referenceFactory->createReference('TES4Attr' . ucwords(strtolower($firstArg->getData())),
                            $globalScope,
                            $multipleScriptsScope,
                            $localScope);

                        $secondArg = $functionArguments->getValue(1);

                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                        break;
                    }

                    case 'speed': {
                        $functionName = "ForceMovementSpeed";
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'fatigue': {
                        $functionName = "SetActorValue";
                        $convertedArguments->add(new TES5String('Stamina'));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'armorer': {
                        $functionName = "SetActorValue";

                        $convertedArguments->add(new TES5String("Smithing"));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'security': {
                        $functionName = "SetActorValue";
                        $convertedArguments->add(new TES5String("Lockpicking"));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'mysticism': { //It doesn't exist in Skyrim - defaulting to Illusion..
                        $functionName = "SetActorValue";
                        $convertedArguments->add(new TES5String("Illusion"));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'blade':
                    case 'blunt': {
                        $functionName = "SetActorValue";

                        $convertedArguments->add(new TES5String("OneHanded"));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                    case 'aggression': {
                        $functionName = "SetActorValue";

                        $secondArg = $functionArguments->getValue(1);
                        $secondArgData = $secondArg->getData();
                        if ($secondArgData == 0) {
                            $newValue = 0;
                        } else if ($secondArgData > 0 && $secondArgData < 50) {
                            $newValue = 1;
                        } else if ($secondArgData >= 50 and $secondArgData < 80) {
                            $newValue = 2;
                        } else {
                            $newValue = 3;
                        }

                        $convertedArguments->add(new TES5String($firstArg->getData()));
                        $convertedArguments->add(new TES5Float($newValue));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }


                    case 'confidence': {
                        $functionName = "SetActorValue";

                        $secondArg = $functionArguments->getValue(1);
                        $secondArgData = $secondArg->getData();
                        if ($secondArgData == 0) {
                            $newValue = 0;
                        } else if ($secondArgData > 0 and $secondArgData < 30) {
                            $newValue = 1;
                        } else if ($secondArgData >= 30 and $secondArgData < 70) {
                            $newValue = 2;
                        } else if ($secondArgData >= 70 and $secondArgData < 99) {
                            $newValue = 3;
                        } else {
                            $newValue = 4;
                        }

                        $convertedArguments->add(new TES5String($firstArg->getData()));
                        $convertedArguments->add(new TES5Float($newValue));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }


                    default: {
                        $functionName = "SetActorValue";
                        $convertedArguments->add(new TES5String($firstArg->getData()));
                        $secondArg = $functionArguments->getValue(1);
                        $convertedArguments->add($this->createValue($secondArg, $codeScope, $globalScope, $multipleScriptsScope));
                        return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
                    }

                }

                break;
            }
            case "setalert": {

                switch ($functionArguments->getValue(0)->getData()) {

                    case 0: {
                        $functionName = "SheatheWeapon";
                        break;
                    }

                    case 1: {

                        $functionName = "DrawWeapon";
                        break;
                    }

                }
                $functionArguments->popValue(0);
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setallreachable": {
                return new TES5Filler();
                //                return $this->createObjectCall($calledOn,$functionName,$this->createArgumentList($functionArguments,$propertyList));
                break;
            }
            case "setallvisible": {
                return new TES5Filler();
                break;
            }
            case "setangle": {
                $x = 0;
                $y = 0;
                $z = 0;
                switch (strtolower($functionArguments->getValue(0)->getData())) {

                    case "x": {
                        $x = $functionArguments->getValue(1)->getData();
                        break;
                    }

                    case "y": {
                        $y = $functionArguments->getValue(1)->getData();
                        break;
                    }


                    case "z": {
                        $z = $functionArguments->getValue(1)->getData();
                        break;
                    }

                }

                $functionArguments->setValue(0, new TES4Integer($x));
                $functionArguments->setValue(1, new TES4Integer($y));
                $functionArguments->setValue(2, new TES4Integer($z));
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setcellpublicflag": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $functionName = "SetPublic";

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setclass": {

                // no classes in skyrim ;)
                return new TES5Filler();
                break;
            }

            case "setinvestmentgold":
            case "addachievement":
            case "stopwaiting":
            case "setcombatstyle": {
                return new TES5Filler();
                break;
            }

            case "gotojail": {
                $calledOn = $this->createReadReference("TES4CyrodiilCrimeFaction", $globalScope, $multipleScriptsScope, $localScope);

                return $this->createObjectCall($calledOn, "SendPlayerToJail",$multipleScriptsScope, new TES5ObjectCallArguments());
                break;
            }

            case "setcrimegold": {
                //TODO
                $calledOn = $this->createReadReference("TES4CyrodiilCrimeFaction", $globalScope, $multipleScriptsScope, $localScope);

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setdestroyed": {
                $functionName = "blockActivation";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setdoordefaultopen": {
                //Not necessary
                return new TES5Filler();
                #return $this->createObjectCall($calledOn,$functionName,$this->createArgumentList($functionArguments,$propertyList));
                break;
            }
            case "setessential": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setfactionrank": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setfactionreaction": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                return $this->createObjectCall($calledOn, "SetReaction",$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setforcerun": {
                $functionName = "ForceMovementSpeed";
                $convertedArguments = new TES5ObjectCallArguments();
                $convertedArguments->add(new TES5Float(2));
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $convertedArguments);
            }
            case "setforcesneak": {
                if((int)$functionArguments->getValue(0)->getData() == 0) {
                    //@INCONSISTENCE - Cannot unsneak a character.
                    return new TES5Filler();
                }

                return $this->createObjectCall($calledOn, "StartSneaking",$multipleScriptsScope);
                break;
            }
            case "setghost": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setignorefriendlyhits": {
                //there surely is a way to do this.. TODO
                return new TES5Filler();
                //return $this->createObjectCall($calledOn,$functionName,$this->createArgumentList($functionArguments,$propertyList));
                break;
            }
            case "setitemvalue": {
                return new TES5Filler();
                break;
            }
            case "setnoavoidance": {
                return new TES5Filler();
                # return $this->createObjectCall($calledOn,$functionName,$this->createArgumentList($functionArguments,$propertyList));
                break;
            }
            case "setnorumors": {
                #Can be done by aliases but its not worth it.
                return new TES5Filler();
                # return $this->createObjectCall($calledOn,$functionName,$this->createArgumentList($functionArguments,$propertyList));
                break;
            }
            case "setopenstate": {

                $constantArgument = new TES5ObjectCallArguments();
                $constantArgument->add(new TES5Bool($functionArguments->getValue(0)->getData()));
                $function = $this->createObjectCall(
                    $calledOn, "SetOpen", $multipleScriptsScope, $constantArgument
                );
                return $function;

            }
            case "setownership": {


                if ($functionArguments->count() > 0) {
                    $args = $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope);
                    $datatype = ESMAnalyzer::instance()->getFormTypeByEDID($functionArguments->getValue(0)->getData());

                    if ($datatype == TES5BasicType::T_ACTOR()) {
                        $functionName = "SetActorOwner";
                    } else if ($datatype == TES5BasicType::T_FACTION()) {
                        $functionName = "SetFactionOwner";
                    } else {
                        throw new ConversionException("Unknown setOwnership() param");
                    }

                } else {
                    $functionName = "SetActorOwner";
                    $args = new TES5ObjectCallArguments();
                    $args->add($this->createObjectCall($this->referenceFactory->createReferenceToPlayer(), "GetActorBase",$multipleScriptsScope));
                }


                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $args);
                break;
            }
            case "setpackduration": {
                //not necessarly to be here ( ... ) ?
                return new TES5Filler();
                #                return $this->createObjectCall($calledOn,$functionName,$this->createArgumentList($functionArguments,$propertyList));
                break;
            }
            case "setpcexpelled": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $functionName = "SetPlayerExpelled";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setpcfactionattack":
            case "setpcfactionmurder": {

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
                $faction = $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $function = $this->createObjectCall(
                    $faction, "SetCrimeGoldViolent", $multipleScriptsScope, $constantArgument
                );
                return $function;

            }


            case "setpcfactionsteal": {

                switch ($functionArguments->getValue(1)->getData()) {

                    case 0: {
                        $arg = 0;
                        break;
                    }

                    case 1: {
                        $arg = 100;
                        break;
                    }

                    default: {
                        throw new ConversionException("SetPCFactionSteal argument unknown");
                    }

                }


                $constantArgument = new TES5ObjectCallArguments();
                $constantArgument->add(new TES5Integer($arg));
                $faction = $this->createReadReference($functionArguments->getValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $function = $this->createObjectCall(
                    $faction, "SetCrimeGold", $multipleScriptsScope, $constantArgument
                );
                return $function;
            }
            case "setpcfame": {
                //This has to be a write-action reference, not a read reference!
                $fameReference = $this->referenceFactory->createReference('Fame', $globalScope, $multipleScriptsScope, $localScope);

                $fameArguments = new TES5ObjectCallArguments();

                $fameArguments->add(
                    $this->createValue($functionArguments->getValue(0), $codeScope, $globalScope, $multipleScriptsScope)
                );

                $function = $this->createObjectCall(

                    $fameReference, "SetValue", $multipleScriptsScope, $fameArguments

                );

                return $function;
            }
            case "setpcinfamy": {
                $fameArguments = new TES5ObjectCallArguments();

                $fameArguments->add(
                    new TES5Integer($functionArguments->getValue(0)->getData())
                );

                $function = $this->createObjectCall(

                    $this->referenceFactory->createReference('Infamy', $globalScope, $multipleScriptsScope, $localScope), "SetValue", $multipleScriptsScope, $fameArguments

                );

                return $function;
            }

            case "setplayerinseworld": {
                return new TES5Filler(); //not needed.
            }

            case "setpos": {

                $callArguments = new TES5ObjectCallArguments();

                $dummyX = $this->createObjectCall($calledOn, "GetPositionX",$multipleScriptsScope);
                $dummyY = $this->createObjectCall($calledOn, "GetPositionY",$multipleScriptsScope);
                $dummyZ = $this->createObjectCall($calledOn, "GetPositionZ",$multipleScriptsScope);

                $argList = [];
                switch (strtolower($functionArguments->getValue(0)->getData())) {

                    case 'x': {
                        $argList[] = $this->createValue($functionArguments->getValue(1), $codeScope, $globalScope, $multipleScriptsScope);
                        $argList[] = $dummyY;
                        $argList[] = $dummyZ;
                        break;
                    }
                    case 'y': {
                        $argList[] = $dummyX;
                        $argList[] = $this->createValue($functionArguments->getValue(1), $codeScope, $globalScope, $multipleScriptsScope);
                        $argList[] = $dummyZ;
                        break;
                    }
                    case 'z': {
                        $argList[] = $dummyX;
                        $argList[] = $dummyY;
                        $argList[] = $this->createValue($functionArguments->getValue(1), $codeScope, $globalScope, $multipleScriptsScope);
                        break;
                    }
                    default: {
                        throw new ConversionException("setPos can handle only X,Y,Z parameters.");
                    }


                }

                foreach ($argList as $argListC) {
                    $callArguments->add($argListC);
                }

                return $this->createObjectCall(

                    $calledOn, "SetPosition", $multipleScriptsScope , $callArguments


                );
            }
            case "setquestobject": {
                //No replacement
                return new TES5Filler();
                break;
            }
            case "setrestrained": {
                return new TES5Filler();
                break;
            }
            case "setrigidbodymass": {
                //No replacement
                return new TES5Filler();
                break;
            }
            case "setscale": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setsceneiscomplex": {
                //No replacement
                return new TES5Filler();
                break;
            }
            case "setshowquestitems": {
                //@INCONSISTENCE - No replacement
                return new TES5Filler();
                break;
            }
            case "setstage": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setunconscious": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "setweather":
            case "sw": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $functionName = "SetActive";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "showbirthsignmenu": {
                return new TES5Filler();
                break;
            }
            case "showclassmenu": {
                return new TES5Filler();
                break;
            }
            case "showdialogsubtitles": {
                return new TES5Filler();
            }
            case "showenchantment": {
                return new TES5Filler();
            }
            case "showmap": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $functionName = "AddToMap";

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "showracemenu": {
                //@inconsistence - stub
                return new TES5Filler();
                break;
            }
            case "showspellmaking": {
                //@inconsistence - stub
                return new TES5Filler();
                break;
            }
            case "startcombat": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "startconversation": {
                //todo
                return new TES5Filler();
                break;
            }
            case "startquest": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $functionName = "Start";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "stopcombat": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, new TES5ObjectCallArguments());
                break;
            }
            case "stopcombatalarmonactor":
            case "scaonactor": {
                return $this->createObjectCall($calledOn, "StopCombatAlarm",$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "stoplook": {
                $functionName = "ClearLookAt";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, new TES5ObjectCallArguments());
                break;
            }
            case "stopmagiceffectvisuals":
            case "sme": {
                return new TES5Filler();
                break;
            }
            case "stopmagicshadervisuals":
            case "sms": {
                return new TES5Filler();
                break;
            }
            case "stopquest": {
                $calledOn = $this->createReadReference($functionArguments->popValue(0)->getData(), $globalScope, $multipleScriptsScope, $localScope);
                $functionName = "Stop";
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }

            case "trapupdate": {
                return new TES5Filler();
                #               return $this->createObjectCall($calledOn, $functionName, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "triggerhitshader": {
                return new TES5Filler();
                //return $this->createObjectCall($calledOn,$functionName,$this->createArgumentList($functionArguments,$propertyList));
                break;
            }
            case "unequipitem": {

                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            case "unlock": {
                $methodArguments = new TES5ObjectCallArguments();
                $methodArguments->add(new TES5Bool(false)); //override different behaviour

                $lockAsOwner = $functionArguments->getValue(1);
                $lock = false;
                if ($lockAsOwner !== null) {
                    if ((bool)$lockAsOwner->getData()) {
                        $lock = true;
                    }
                }

                $methodArguments->add(new TES5Bool($lock));

                return $this->createObjectCall($calledOn, "Lock",$multipleScriptsScope, $methodArguments);
                break;
            }
            case "wait": {
                //    No replacement ( ?)
                return new TES5Filler();
                break;
            }
            case "wakeuppc": {
                throw new ConversionException("WakeUpPC() not available in Papyrus.");
                break;
            }
            case "yield": {
                return $this->createObjectCall($calledOn, $functionName,$multipleScriptsScope, $this->createArgumentList($functionArguments, $codeScope, $globalScope, $multipleScriptsScope));
                break;
            }
            default: {
                throw new ConversionException("TES5ValueFactory::convertFunction() - Unknown function: " . $functionName);
            }


        }


    }

    public function createObjectCall(TES5Referencer $callable, $functionName, TES5MultipleScriptsScope $multipleScriptsScope, TES5ObjectCallArguments $arguments = null, $inference = true)
    {
        $objectCall = new TES5ObjectCall($callable, $functionName, $arguments);

        if ($inference)
            $this->typeInferencer->inferenceObjectByMethodCall($objectCall, $multipleScriptsScope);

        return $objectCall;
    }

    /**
     * Create the ,,read reference".
     * Read reference is used ( as you might think ) in read contexts.
     * @param $referenceName
     * @param TES5GlobalScope $globalScope
     * @param TES5MultipleScriptsScope $multipleScriptsScope
     * @param TES5LocalScope $localScope
     * @return TES5Referencer
     */
    public function createReadReference($referenceName, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope, TES5LocalScope $localScope)
    {

        $rawReference = $this->referenceFactory->createReference($referenceName, $globalScope, $multipleScriptsScope, $localScope);

        if ($rawReference->getType() == TES5BasicType::T_GLOBALVARIABLE()) {
            //Changed to int implementation.
            return $this->createObjectCall($rawReference, "GetValueInt",$multipleScriptsScope);
        } else {
            return $rawReference;
        }

    }

    /**
     * Extracts implicit reference from calls.
     * Returns a reference from calls like:
     * Enable
     * Disable
     * Activate
     * GetInFaction whatsoever
     * @param TES5GlobalScope $globalScope
     * @param TES5MultipleScriptsScope $multipleScriptsScope
     * @param TES5LocalScope $localScope
     * @return TES5Referencer
     * @throws \Ormin\OBSLexicalParser\TES5\Exception\ConversionException
     */
    private function extractImplicitReference(TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope, TES5LocalScope $localScope)
    {
        switch ($globalScope->getScriptHeader()->getBasicScriptType()) {
            case TES5BasicType::T_OBJECTREFERENCE(): {
                return $this->referenceFactory->createReferenceToSelf($globalScope);
            }

            case TES5BasicType::T_ACTIVEMAGICEFFECT(): {
                $self = $this->referenceFactory->createReferenceToSelf($globalScope);
                return $this->createObjectCall($self, "GetTargetActor",$multipleScriptsScope);
            }

            case TES5BasicType::T_QUEST(): {
                //todo - this should not be done like this
                //we should actually not try to extract the implicit reference on the non-reference oblivion functions like "stopQuest"
                //think of this line as a hacky way to just get code forward.
                return $this->referenceFactory->createReferenceToSelf($globalScope);
            }


            /**
             * TIF Fragments
             */
            case TES5BasicType::T_TOPICINFO(): {
                return $this->createReadReference('akSpeakerRef', $globalScope, $multipleScriptsScope, $localScope);
            }

            default: {
                throw new ConversionException("Cannot extract implicit reference - unknown basic script type.");
            }

        }
    }

}