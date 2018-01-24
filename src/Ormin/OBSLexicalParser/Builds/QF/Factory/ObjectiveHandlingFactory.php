<?php
/**
 * Created by PhpStorm.
 * Date: 2/4/17
 * Time: 9:03 PM
 */

namespace Ormin\OBSLexicalParser\Builds\QF\Factory;


use Ormin\OBSLexicalParser\TES5\AST\Block\TES5CodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\Block\TES5FunctionCodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunk;
use Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5ArithmeticExpressionOperator;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCall;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCallArguments;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5LocalVariable;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Integer;
use Ormin\OBSLexicalParser\TES5\Factory\TES5BranchFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5CodeScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ExpressionFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5FragmentFunctionScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5LocalScopeFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5ReferenceFactory;
use Ormin\OBSLexicalParser\TES5\Factory\TES5VariableAssignationFactory;
use Ormin\OBSLexicalParser\TES5\Other\TES5FragmentType;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\Types\TES5VoidType;

class ObjectiveHandlingFactory
{

    /**
     * @var TES5FragmentFunctionScopeFactory
     */
    private $fragmentFunctionScopeFactory;

    /**
     * @var TES5CodeScopeFactory
     */
    private $codeScopeFactory;

    /**
     * @var TES5LocalScopeFactory
     */
    private $localScopeFactory;

    /**
     * @var TES5ReferenceFactory
     */
    private $referenceFactory;

    /**
     * @var TES5VariableAssignationFactory
     */
    private $variableAssignationFactory;

    /**
     * @var TES5BranchFactory
     */
    private $branchFactory;

    /**
     * @var TES5ExpressionFactory
     */
    private $expressionFactory;

    public function __construct(
        TES5FragmentFunctionScopeFactory $fragmentFunctionScopeFactory,
        TES5CodeScopeFactory $codeScopeFactory,
        TES5LocalScopeFactory $localScopeFactory,
        TES5BranchFactory $branchFactory,
        TES5VariableAssignationFactory $variableAssignationFactory,
        TES5ReferenceFactory $referenceFactory,
        TES5ExpressionFactory $expressionFactory
    )
    {
        $this->fragmentFunctionScopeFactory = $fragmentFunctionScopeFactory;
        $this->codeScopeFactory = $codeScopeFactory;
        $this->localScopeFactory = $localScopeFactory;
        $this->variableAssignationFactory = $variableAssignationFactory;
        $this->referenceFactory = $referenceFactory;
        $this->branchFactory = $branchFactory;
        $this->expressionFactory = $expressionFactory;
    }


    /**
     * @param TES5CodeBlock $codeBlock
     * @param TES5GlobalScope $globalScope
     * @param int $stageId The stage ID
     * @param int[] $stageMap List of integers describing targets being enabled or disabled for given stage
     * @return TES5FunctionCodeBlock
     */
    public function createEnclosedFragment(TES5GlobalScope $globalScope, $stageId, $stageMap)
    {
        $fragmentName = "Fragment_" . $stageId;
        $functionScope = $this->fragmentFunctionScopeFactory->createFromFragmentType($fragmentName, TES5FragmentType::T_QF());
        $codeScope = $this->codeScopeFactory->createCodeScope($this->localScopeFactory->createRootScope($functionScope));

        $codeBlock = new TES5FunctionCodeBlock(new TES5VoidType(),$functionScope, $codeScope);
        $chunks = $this->generateObjectiveHandling($codeBlock, $globalScope, $stageMap);

        foreach($chunks as $chunk) {
            $codeBlock->addChunk($chunk);
        }

        return $codeBlock;
    }

    /**
     * @param TES5CodeBlock $codeBlock
     * @param TES5GlobalScope $globalScope
     * @param $stageMap
     * @return TES5CodeChunk[]
     */
    public function generateObjectiveHandling(TES5CodeBlock $codeBlock, TES5GlobalScope $globalScope, $stageMap)
    {
        $result = [];

        $castedToQuest = new TES5LocalVariable("__temp",TES5BasicType::T_QUEST());
        $referenceToTemp = $this->referenceFactory->createReferenceToVariable($castedToQuest);
        $result[] = $this->variableAssignationFactory->createAssignation(
            $referenceToTemp,
            $this->referenceFactory->createReferenceToSelf($globalScope)
        );

        $localScope = $codeBlock->getCodeScope()->getLocalScope();
        $localScope->addVariable($castedToQuest);

        $i = 0;
        foreach($stageMap as $stageTargetState)
        {
            $targetIndex = new TES5Integer($i);
            if($stageTargetState) {
                //Should be visible
                $displayedArguments = new TES5ObjectCallArguments();
                $displayedArguments->add($targetIndex);
                $isObjectiveDisplayed = new TES5ObjectCall($referenceToTemp, "IsObjectiveDisplayed", $displayedArguments);

                $expression = $this->expressionFactory->createArithmeticExpression(
                    $isObjectiveDisplayed,
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    new TES5Integer(0)
                );

                $arguments = new TES5ObjectCallArguments();
                $arguments->add($targetIndex);
                $arguments->add(new TES5Integer(1));
                $showTheObjective = new TES5ObjectCall($referenceToTemp, "SetObjectiveDisplayed", $arguments);


                $branch = $this->branchFactory->createSimpleBranch($expression, $localScope);
                $branch->getMainBranch()->getCodeScope()->add($showTheObjective);
                $result[] = $branch;
            } else {

                $displayedArguments = new TES5ObjectCallArguments();
                $displayedArguments->add($targetIndex);
                $isObjectiveDisplayed = new TES5ObjectCall($referenceToTemp, "IsObjectiveDisplayed", $displayedArguments);

                $expression = $this->expressionFactory->createArithmeticExpression(
                    $isObjectiveDisplayed,
                    TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                    new TES5Integer(1)
                );


                $arguments = new TES5ObjectCallArguments();
                $arguments->add($targetIndex);
                $arguments->add(new TES5Integer(1));
                $completeTheObjective = new TES5ObjectCall($referenceToTemp, "SetObjectiveCompleted", $arguments);

                $branch = $this->branchFactory->createSimpleBranch($expression, $localScope);
                $branch->getMainBranch()->getCodeScope()->add($completeTheObjective);
                $result[] = $branch;

            }

            ++$i;
        }


        return $result;

    }

}