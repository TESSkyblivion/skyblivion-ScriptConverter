<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES4\AST\Code\TES4VariableAssignation;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunkCollection;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5ArithmeticExpressionOperator;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCallArguments;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Float;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Integer;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5None;
use Ormin\OBSLexicalParser\TES5\Service\TES5TypeInferencer;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5VariableAssignationConversionFactory {

    /**
     * @var TES5ObjectCallFactory
     */
    private $objectCallFactory;

    /**
     * @var TES5ReferenceFactory
     */
    private $referenceFactory;

    /**
     * @var TES5ValueFactory
     */
    private $valueFactory;

    /**
     * @var TES5VariableAssignationFactory
     */
    private $assignationFactory;

    /**
     * @var TES5BranchFactory
     */
    private $branchFactory;

    /**
     * @var TES5ExpressionFactory
     */
    private $expressionFactory;

    /**
     * @var \Ormin\OBSLexicalParser\TES5\Service\TES5TypeInferencer
     */
    private $typeInferencer;

    public function __construct(TES5ObjectCallFactory $objectCallFactory, TES5ReferenceFactory $referenceFactory, TES5ValueFactory $valueFactory, TES5VariableAssignationFactory $assignationFactory, TES5BranchFactory $branchFactory, TES5ExpressionFactory $expressionFactory, TES5TypeInferencer $typeInferencer) {
        $this->objectCallFactory = $objectCallFactory;
        $this->referenceFactory = $referenceFactory;
        $this->valueFactory = $valueFactory;
        $this->assignationFactory = $assignationFactory;
        $this->branchFactory = $branchFactory;
        $this->expressionFactory = $expressionFactory;
        $this->typeInferencer = $typeInferencer;
    }

    public function createCodeChunk(TES4VariableAssignation $chunk, TES5CodeScope $codeScope, \Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope) {

        $codeChunkCollection = new TES5CodeChunkCollection();
        $referenceName = $chunk->getReference()->getData();

        $reference = $this->referenceFactory->createReference($referenceName, $globalScope, $multipleScriptsScope, $codeScope->getLocalScope());
        $value = $this->valueFactory->createValue($chunk->getValue(),$codeScope, $globalScope, $multipleScriptsScope);


        if($reference->getType() == TES5BasicType::T_GLOBALVARIABLE()) { //if the reference is in reality a global variable, we will need to convert it by creating a Reference.SetValue(value); call
            //Object call creation
            $objectCallArguments = new TES5ObjectCallArguments();
            $objectCallArguments->add($value);
            $objectCall = $this->objectCallFactory->createObjectCall($reference, "SetValue", $multipleScriptsScope, $objectCallArguments);
            $codeChunkCollection->add($objectCall);

        } else {


            if(!$reference->getReferencesTo()->getPropertyType()->isPrimitive() && $value->getType()->isPrimitive()) {

                if($value instanceof TES5Integer || $value instanceof TES5Float) { //Hacky!

                    if($value->getValue() == 0) {
                        $value = new TES5None();
                    }
                }

            }

            $assignation = $this->assignationFactory->createAssignation($reference, $value);
            $this->typeInferencer->inferenceObjectByAssignation($reference, $value, $multipleScriptsScope);
            $codeChunkCollection->add($assignation);
            //post analysis.
            //Todo - rethink the prefix here
            if($value instanceof TES5Referencer && $value->getName() == TES5ReferenceFactory::MESSAGEBOX_VARIABLE_CONST."_p") {
                /**
                 * Create block:
                 * variable = this.TES4_MESSAGEBOX_RESULT; ; $assignation
                 * if(variable != -1) ; $branch, $expression
                 *   this.TES4_MESSAGEBOX_RESULT = -1; ; $reassignation
                 * endIf
                 */

                $minusOne = new TES5Integer(-1);
                $expression = $this->expressionFactory->createArithmeticExpression($reference,TES5ArithmeticExpressionOperator::OPERATOR_NOT_EQUAL(),$minusOne);
                $reassignation = $this->assignationFactory->createAssignation($value, $minusOne);

                $branch = $this->branchFactory->createSimpleBranch($expression, $codeScope->getLocalScope());
                $branch->getMainBranch()->getCodeScope()->add($reassignation);
                $codeChunkCollection->add($branch);
            }

        }

        return $codeChunkCollection;

    }


} 