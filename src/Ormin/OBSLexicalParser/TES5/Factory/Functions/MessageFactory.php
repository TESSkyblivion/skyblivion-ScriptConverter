<?php

namespace Ormin\OBSLexicalParser\TES5\Factory\Functions;

use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4Function;
use Ormin\OBSLexicalParser\TES4\AST\Value\Primitive\TES4String;
use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCallArguments;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5StaticReference;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5String;
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

class MessageFactory implements FunctionFactory
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
        $functionArguments = $function->getArguments();

        $messageString = $functionArguments->getValue(0)->getData();
        preg_match_all("#%([ +-0]*[1-9]*\.[0-9]+[ef]|g)#", $messageString, $messageMatches, PREG_OFFSET_CAPTURE);

        $floatPointArgumentsCount = count($messageMatches[0]);
        if ($floatPointArgumentsCount > 0) {
            //Pack the printf syntax
            //TODO - Perhaps we can use sprintf?
            if (!$functionArguments->getValue(0) instanceof TES4String) { //jesus its hacky
                throw new ConversionException("Cannot transform printf like syntax to concat on string loaded dynamically");
            }

            $i = 0;
            $caret = 0;

            $functionArguments->popValue(0); //We don't need first value.
            //Example call: You have %.2f apples and %g boxes in your inventory, applesCount, boxesCount
            $variablesStack = [];
            foreach ($functionArguments->getValues() as $variable) {
                $variablesStack[] = $this->valueFactory->createValue($variable, $codeScope, $globalScope, $multipleScriptsScope);
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
            return $this->objectCallFactory->createObjectCall($calledOn, "Notification", $multipleScriptsScope, $arguments);

        } else {
            $calledOn = new TES5StaticReference("Debug");
            $arguments = new TES5ObjectCallArguments();
            $arguments->add($this->valueFactory->createValue($functionArguments->getValue(0), $codeScope, $globalScope, $multipleScriptsScope));
            return $this->objectCallFactory->createObjectCall($calledOn, "Notification", $multipleScriptsScope, $arguments);
        }
    }
}