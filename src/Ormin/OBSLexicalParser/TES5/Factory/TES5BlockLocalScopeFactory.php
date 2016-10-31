<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;

use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5LocalScope;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5LocalVariable;
use Ormin\OBSLexicalParser\TES5\Context\TES5LocalVariableParameterMeaning;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5BlockLocalScopeFactory
{

    public function createFromBlockType($blockType)
    {
        $localScope = new TES5LocalScope();

        switch ($blockType) {

            case 'OnUpdate':
            {
                break;
            }

            case 'OnActivate':
            {

                $localScope->addVariable(
                    new TES5LocalVariable("akActivateRef", TES5BasicType::T_OBJECTREFERENCE(), [TES5LocalVariableParameterMeaning::ACTIVATOR()])
                );

                break;
            }

            case 'OnInit':
            {

                break;
            }

            case 'OnSell':
            {


                $localScope->addVariable(
                    new TES5LocalVariable("akSeller", TES5BasicType::T_ACTOR(), [TES5LocalVariableParameterMeaning::ACTIVATOR()]) //todo not sure about activator meaning
                );

                break;
            }

            case 'OnDeath':
            {


                $localScope->addVariable(
                    new TES5LocalVariable("akKiller", TES5BasicType::T_ACTOR(), [TES5LocalVariableParameterMeaning::ACTIVATOR()])
                );

                break;

            }

            case 'OnLoad':
            {
                break;
            }

            case 'OnObjectEquipped':
            {
                $localScope->addVariable(
                    new TES5LocalVariable("akBaseObject", TES5BasicType::T_FORM(), [TES5LocalVariableParameterMeaning::CONTAINER()])
                );

                $localScope->addVariable(
                    new TES5LocalVariable("akReference", TES5BasicType::T_OBJECTREFERENCE())
                );

                break;
            }

            case 'OnTriggerEnter':
            {
                $localScope->addVariable(
                    new TES5LocalVariable("akActivateRef", TES5BasicType::T_OBJECTREFERENCE(), [TES5LocalVariableParameterMeaning::ACTIVATOR(), TES5LocalVariableParameterMeaning::CONTAINER()])
                );
                break;
            }

            case 'OnEquipped':
            {
                $localScope->addVariable(
                    new TES5LocalVariable("akActor", TES5BasicType::T_ACTOR(), [TES5LocalVariableParameterMeaning::ACTIVATOR(), TES5LocalVariableParameterMeaning::CONTAINER()])
                );
                break;
            }

            case 'OnUnequipped':
            {
                $localScope->addVariable(
                    new TES5LocalVariable("akActor", TES5BasicType::T_ACTOR(), [TES5LocalVariableParameterMeaning::ACTIVATOR()])
                );
                break;
            }

            case 'OnContainerChanged':
            {
                $localScope->addVariable(
                    new TES5LocalVariable("akNewContainer", TES5BasicType::T_OBJECTREFERENCE())
                );

                $localScope->addVariable(
                    new TES5LocalVariable("akOldContainer", TES5BasicType::T_OBJECTREFERENCE())
                );
                break;
            }

            case 'OnTrigger':
            {
                $localScope->addVariable(
                    new TES5LocalVariable("akActivateRef", TES5BasicType::T_OBJECTREFERENCE(), [TES5LocalVariableParameterMeaning::ACTIVATOR()])
                );
                break;
            }

            case 'OnHit':
            {
                $localScope->addVariable(
                    new TES5LocalVariable("akAggressor", TES5BasicType::T_OBJECTREFERENCE(), [TES5LocalVariableParameterMeaning::ACTIVATOR()])
                );

                $localScope->addVariable(
                    new TES5LocalVariable("akSource", TES5BasicType::T_FORM())
                );

                $localScope->addVariable(
                    new TES5LocalVariable("akProjectile", TES5BasicType::T_PROJECTILE())
                );

                $localScope->addVariable(
                    new TES5LocalVariable("abPowerAttack", TES5BasicType::T_BOOL())
                );

                $localScope->addVariable(
                    new TES5LocalVariable("abSneakAttack", TES5BasicType::T_BOOL())
                );

                $localScope->addVariable(
                    new TES5LocalVariable("abBashAttack", TES5BasicType::T_BOOL())
                );

                $localScope->addVariable(
                    new TES5LocalVariable("abHitBlocked", TES5BasicType::T_BOOL())
                );

                break;
            }

            case 'OnCombatStateChanged':
            {

                $localScope->addVariable(
                    new TES5LocalVariable("akTarget", TES5BasicType::T_ACTOR(), [TES5LocalVariableParameterMeaning::ACTIVATOR()])
                );


                $localScope->addVariable(
                    new TES5LocalVariable("aeCombatState", TES5BasicType::T_INT())
                );

                break;
            }

            case 'OnPackageStart':
            {

                $localScope->addVariable(
                    new TES5LocalVariable("akNewPackage", TES5BasicType::T_PACKAGE())
                );
                break;
            }

            case 'OnPackageDone':
            {

                $localScope->addVariable(
                    new TES5LocalVariable("akDonePackage", TES5BasicType::T_PACKAGE())
                );
                break;
            }

            case 'OnPackageEnd':
            {

                $localScope->addVariable(
                    new TES5LocalVariable("akOldPackage", TES5BasicType::T_PACKAGE())
                );
                break;
            }

            case 'OnPackageChange':
            {

                $localScope->addVariable(
                    new TES5LocalVariable("akOldPackage", TES5BasicType::T_PACKAGE())
                );
                break;
            }

            case 'OnMagicEffectApply':
            {

                $localScope->addVariable(
                    new TES5LocalVariable("akCaster", TES5BasicType::T_OBJECTREFERENCE(), [TES5LocalVariableParameterMeaning::ACTIVATOR()])
                );


                $localScope->addVariable(
                    new TES5LocalVariable("akMagicEffect", TES5BasicType::T_MAGICEFFECT())
                );

                break;
            }

            case 'OnReset':
            {
                break;
            }

            case 'OnEffectStart':
            {

                $localScope->addVariable(
                    new TES5LocalVariable("akTarget", TES5BasicType::T_ACTOR())
                );

                $localScope->addVariable(
                    new TES5LocalVariable("akCaster", TES5BasicType::T_ACTOR(), [TES5LocalVariableParameterMeaning::ACTIVATOR()])
                );


                break;
            }

            case 'OnEffectFinish':
            {

                $localScope->addVariable(
                    new TES5LocalVariable("akTarget", TES5BasicType::T_ACTOR())
                );

                $localScope->addVariable(
                    new TES5LocalVariable("akCaster", TES5BasicType::T_ACTOR(), [TES5LocalVariableParameterMeaning::ACTIVATOR()])
                );

                break;
            }

            default:
                {
                throw new ConversionException("Cannot find new block type local scope variables out of " . $blockType);
                }


        }

        return $localScope;

    }

    public function createRecursiveScope(TES5LocalScope $parentScope) {
        return new TES5LocalScope($parentScope);
    }

} 