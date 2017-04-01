<?php

namespace Ormin\OBSLexicalParser\TES5\Factory;

use Ormin\OBSLexicalParser\TES5\AST\Block\TES5EventCodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5Return;
use Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5ArithmeticExpressionOperator;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCallArguments;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Bool;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5InitialBlockCodeFactory
{

    /**
     * @var TES5BranchFactory
     */
    private $branchFactory;

    /**
     * @var TES5ExpressionFactory
     */
    private $expressionFactory;

    /**
     * @var TES5ReferenceFactory
     */
    private $referenceFactory;

    /**
     * @var TES5ObjectCallFactory
     */
    private $objectCallFactory;

    public function __construct(TES5BranchFactory $branchFactory,
                                TES5ExpressionFactory $expressionFactory,
                                TES5ReferenceFactory $referenceFactory,
                                TES5ObjectCallFactory $objectCallFactory)
    {

        $this->branchFactory = $branchFactory;
        $this->expressionFactory = $expressionFactory;
        $this->referenceFactory = $referenceFactory;
        $this->objectCallFactory = $objectCallFactory;
    }

    public function addInitialCode(TES5MultipleScriptsScope $multipleScriptsScope, TES5GlobalScope $globalScope, TES5EventCodeBlock $eventCodeBlock)
    {
        switch($eventCodeBlock->getBlockType())
        {
            case "OnUpdate":
            {
                if($globalScope->getScriptHeader()->getBasicScriptType() == TES5BasicType::T_QUEST())
                {
                    $branch = $this->branchFactory->createSimpleBranch(
                        $this->expressionFactory->createArithmeticExpression(
                            $this->objectCallFactory->createObjectCall($this->referenceFactory->createReferenceToSelf($globalScope),"IsRunning",$multipleScriptsScope, new TES5ObjectCallArguments()),
                            TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                            new TES5Bool(false)
                        ),
                        $eventCodeBlock->getCodeScope()->getLocalScope()
                    );
                    $branch->getMainBranch()->getCodeScope()->add(new TES5Return());
                    $eventCodeBlock->addChunk($branch);
                }
            }
        }
    }



}