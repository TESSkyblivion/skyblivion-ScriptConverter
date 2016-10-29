<?php
namespace Ormin\OBSLexicalParser\TES5\Service;


use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCall;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Reference;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5Variable;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Factory\TES5TypeFactory;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\Types\TES5CustomType;
use Ormin\OBSLexicalParser\TES5\Types\TES5InheritanceGraphAnalyzer;

class TES5TypeInferencer {

    private $otherScriptsFolder;

    private $otherScripts;

    private $esmAnalyzer;

    public function __construct(ESMAnalyzer $ESMAnalyzer, $otherScriptsFolder) {
        $this->esmAnalyzer = $ESMAnalyzer;
        $this->otherScriptsFolder = $otherScriptsFolder;
        $otherScripts = scandir($this->otherScriptsFolder);
        unset($otherScripts[0]);
        unset($otherScripts[1]);

        foreach($otherScripts as $k=> $otherScript) {
            $otherScripts[$k] = strtolower(substr($otherScript,0,-4));
        }

        $this->otherScripts = $otherScripts;
    }

    /**
     * Inference the type by analyzing the object call.
     * Please note: It is not able to analyze calls to another scripts, but those weren't used in oblivion anyways
     * @param TES5ObjectCall $objectCall
     * @param TES5MultipleScriptsScope $multipleScriptsScope
     * @throws \Ormin\OBSLexicalParser\TES5\Exception\ConversionException
     */
    public function inferenceObjectByMethodCall(TES5ObjectCall $objectCall, TES5MultipleScriptsScope $multipleScriptsScope) {

        $this->inferenceTypeOfCalledObject($objectCall, $multipleScriptsScope);

        if($objectCall->getArguments() !== null) {
            $this->inferenceTypeOfMethodArguments($objectCall, $multipleScriptsScope);
        }
    }

    private function inferenceTypeOfMethodArguments(TES5ObjectCall $objectCall, TES5MultipleScriptsScope $multipleScriptsScope) {

        /**
         * Inference the arguments
         */
        $arguments = $objectCall->getArguments();
        $argumentNumber = 0;

        $calledOnType = $objectCall->getAccessedObject()->getType()->getNativeType();



        foreach($arguments->getArguments() as $argument) {

            /**
             * Get the argument type according to TES5Inheritance graph.
             */
            $argumentTargetType = TES5InheritanceGraphAnalyzer::findTypeByMethodParameter($calledOnType,$objectCall->getFunctionName(),$argumentNumber);

            if($argument->getType() == $argumentTargetType) {
                ++$argumentNumber;
                continue; //Same type matched. We do not need to do anything :)
            }

            /**
             * todo - maybe we should move getReferencesTo() to TES5Value and make all of the rest TES5Values just have null references as they do not reference anything? :)
             */
            if(TES5InheritanceGraphAnalyzer::isExtending($argumentTargetType,$argument->getType()->getNativeType()) && $argument instanceof TES5Referencer) { //HACKY!
                $this->inferenceType($argument->getReferencesTo(),$argumentTargetType, $multipleScriptsScope);
            } else {

                //So there's one , one special case where we actually have to cast a var from one to another even though they are not ,,inheriting" from themselves, because they are primitives.
                //Scenario: there's an T_INT argument, and we feed it with a T_FLOAT variable reference. It won't work :(
                //We need to cast it on call level ( NOT inference it ) to make it work and not break other possible scenarios ( more specifically, when a float would be inferenced to int and there's a
                //float assigment somewhere in the code )

                if($argumentTargetType == TES5BasicType::T_INT() && $argument->getType() == TES5BasicType::T_FLOAT()) {

                    if($argument instanceof TES5Reference) { //HACKY! When we'll clean up this interface, it will dissapear :)
                        $argument->setManualCastTo(TES5BasicType::T_INT());
                    }

                }

            }

            ++$argumentNumber;
        }

    }

    private function inferenceTypeOfCalledObject(TES5ObjectCall $objectCall, TES5MultipleScriptsScope $multipleScriptsScope) {

        $inferencableType = $objectCall->getAccessedObject()->getType()->getNativeType();

        /**
         * Check if we have something to inference inside the code, not some static class or method call return
         */
        if($objectCall->getAccessedObject()->getReferencesTo() !== null) {

            //this is not "exactly" nice solution, but its enough. For now.
            $inferenceType = TES5InheritanceGraphAnalyzer::findTypeByMethod($objectCall);

            if($inferencableType === null) {
                throw new ConversionException("Cannot inference a null type");
            }

            if($inferencableType == $inferenceType) {
                    return; //We already have the good type.
            }

            if($this->inferenceType($objectCall->getAccessedObject()->getReferencesTo(),$inferenceType,$multipleScriptsScope)) {
                return;
            }

        }
    }

    private function inferenceWithCustomType(TES5Variable $variable, TES5CustomType $type, TES5MultipleScriptsScope $multipleScriptsScope)
    {

        /**
         * We're referencing another script - find the script and make it a variable that property will track remotely
         */
        $scriptHeader = $multipleScriptsScope->getScriptHeaderOfScript($type->value());
        $variable->trackRemoteScript($scriptHeader);
        return true;
    }

        /**
     * Try to inference $variable's type with $type.
     * @param TES5Variable $variable
     * @param TES5BasicType $type
     * @param TES5MultipleScriptsScope $multipleScriptsScope Needed for proxifying the properties to other scripts
     * @return bool - Will return true if inferencing succeeded, false otherwise.
     * @throws ConversionException
     */
    private function inferenceType(TES5Variable $variable, TES5BasicType $type, TES5MultipleScriptsScope $multipleScriptsScope) {

        if(!TES5InheritanceGraphAnalyzer::isExtending($type,$variable->getPropertyType()->getNativeType())) {
            return false;
        }

        $variable->setPropertyType($type);
        return true;
    }

    /**
     * @param TES5Variable $variable
     * @return \Ormin\OBSLexicalParser\TES5\Types\TES5CustomType
     * @throws ConversionException
     */
    public function resolveInferenceTypeByReferenceEdid(TES5Variable $variable) {

        $base = $variable->getReferenceEdid();
        $tryAs = [$base, $base.'Script'];

        if(strtolower(substr($base,-3,3)) == "ref") {
            $tryAsRef = substr($base,0,-3);

            $tryAs[] = $tryAsRef;
            $tryAs[] = $tryAsRef.'Script';

        }

        $tryAs = array_unique($tryAs);

        foreach($tryAs as $try) {
            if(in_array(strtolower($try),$this->otherScripts)) {
                return TES5TypeFactory::memberByValue($try);
            }
        }

        //If it's not found, we're forced to scan the ESM to see, how to resolve the ref name to script type
        return $this->esmAnalyzer->resolveScriptTypeByItsAttachedName($variable->getReferenceEdid());
    }

    /**
     * Inference the variable by its reference EDID
     * @param TES5Variable $variable
     * @param TES5MultipleScriptsScope $multipleScriptsScope
     * @throws ConversionException
     */
    public function inferenceVariableByReferenceEdid(TES5Variable $variable, TES5MultipleScriptsScope $multipleScriptsScope) {


        //Check if it was inferenced to custom type already
        if(!$variable->getPropertyType()->isNativePapyrusType()) {
            return; //Do not even try to inference a type which is already non-native.
        }

        $this->inferenceWithCustomType($variable, $this->resolveInferenceTypeByReferenceEdid($variable), $multipleScriptsScope);
    }

    public function inferenceObjectByAssignation(TES5Referencer $reference, TES5Value $value, TES5MultipleScriptsScope $multipleScriptsScope) {

        if($reference->getReferencesTo() !== null && !$reference->getType()->isPrimitive()) {
            $this->inferenceType($reference->getReferencesTo(), $value->getType()->getNativeType(), $multipleScriptsScope);
        }

    }

} 