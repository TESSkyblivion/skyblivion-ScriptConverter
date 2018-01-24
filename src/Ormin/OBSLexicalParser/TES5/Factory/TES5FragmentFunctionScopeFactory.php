<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5FunctionScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5LocalScope;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5LocalVariable;
use Ormin\OBSLexicalParser\TES5\Context\TES5LocalVariableParameterMeaning;
use Ormin\OBSLexicalParser\TES5\Other\TES5FragmentType;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5FragmentFunctionScopeFactory
{

    /**
     * @param $fragmentName
     * @param TES5FragmentType $fragmentType
     * @return TES5FunctionScope
     * @throws \Ormin\OBSLexicalParser\TES5\Exception\ConversionException
     */
    public function createFromFragmentType($fragmentName, TES5FragmentType $fragmentType)
    {
        $localScope = new TES5FunctionScope($fragmentName);

        switch ($fragmentType) {

            case TES5FragmentType::T_TIF():
            {
                $localScope->addVariable(
                    new TES5LocalVariable("akSpeakerRef", TES5BasicType::T_OBJECTREFERENCE(), [TES5LocalVariableParameterMeaning::ACTIVATOR()])
                );

                break;
            }

            case TES5FragmentType::T_PF():
            {

                $localScope->addVariable(
                    new TES5LocalVariable("akActor", TES5BasicType::T_ACTOR(), [TES5LocalVariableParameterMeaning::ACTIVATOR()])
                );

                break;
            }

            case TES5FragmentType::T_QF():
            {

                break;
            }
        }

        return $localScope;

    }
    
} 