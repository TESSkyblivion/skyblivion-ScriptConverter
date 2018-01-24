<?php

namespace Ormin\OBSLexicalParser\TES5\Factory;

use Ormin\OBSLexicalParser\TES5\AST\Block\TES5EventCodeBlock;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5Return;
use Ormin\OBSLexicalParser\TES5\AST\Expression\Operators\TES5ArithmeticExpressionOperator;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCallArguments;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Bool;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Float;
use Ormin\OBSLexicalParser\TES5\Converter\TES5AdditionalBlockChangesPass;
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

    /**
     * Add initial code to the blocks and return the scope in which conversion should occur
     * Sometimes, we want to add a bit of code before the converted code, or want to encapsulate whole converted code
     * with a branch or so - this is a place to do it.
     * @param TES5MultipleScriptsScope $multipleScriptsScope
     * @param TES5GlobalScope $globalScope
     * @param TES5EventCodeBlock $eventCodeBlock
     * @return TES5CodeScope Scope in which we want for conversion to happen
     */
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
                    //Even though we'd like this script to not do anything at this time, it seems like sometimes condition races, so we're putting it into a loop anyways but with early return bailout
                    $args = new TES5ObjectCallArguments();
                    $args->add(new TES5Float(TES5AdditionalBlockChangesPass::ON_UPDATE_TICK));
                    $branch->getMainBranch()->getCodeScope()->add(
                        $this->objectCallFactory->createObjectCall($this->referenceFactory->createReferenceToSelf($globalScope), "RegisterForSingleUpdate", $multipleScriptsScope, $args)
                    );
                    $branch->getMainBranch()->getCodeScope()->add(new TES5Return());
                    $eventCodeBlock->addChunk($branch);
                    return $eventCodeBlock->getCodeScope();
                }  elseif($globalScope->getScriptHeader()->getBasicScriptType() == TES5BasicType::T_OBJECTREFERENCE()) {
                    $branch = $this->branchFactory->createSimpleBranch(
                        $this->expressionFactory->createArithmeticExpression(
                            $this->objectCallFactory->createObjectCall($this->referenceFactory->createReferenceToSelf($globalScope),"GetParentCell",$multipleScriptsScope, new TES5ObjectCallArguments()),
                            TES5ArithmeticExpressionOperator::OPERATOR_EQUAL(),
                            $this->objectCallFactory->createObjectCall($this->referenceFactory->createReferenceToPlayer(),"GetParentCell",$multipleScriptsScope, new TES5ObjectCallArguments())
                        ),
                        $eventCodeBlock->getCodeScope()->getLocalScope()
                    );

                    $eventCodeBlock->addChunk($branch);
                    return $branch->getMainBranch()->getCodeScope();
                } else {
                    return $eventCodeBlock->getCodeScope();
                }
            }

            default:
            {
                return $eventCodeBlock->getCodeScope();
            }
        }
    }



}