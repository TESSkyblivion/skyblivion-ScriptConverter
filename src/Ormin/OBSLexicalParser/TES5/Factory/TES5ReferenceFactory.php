<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;

use Ormin\OBSLexicalParser\TES5\AST\Object\TES5PlayerReference;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Reference;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5SelfReference;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5StaticReference;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5ScriptAsVariable;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5LocalScope;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5Property;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5Variable;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5ReferenceFactory
{

    const MESSAGEBOX_VARIABLE_CONST = "TES4_MESSAGEBOX_RESULT";

    /**
     * @var TES5ObjectPropertyFactory
     */
    private $objectPropertyFactory;

    /**
     * @var array
     */
    private $special_conversions;

    public function __construct(TES5ObjectPropertyFactory $objectPropertyFactory) {
        $this->objectPropertyFactory = $objectPropertyFactory;

        //Those are used to hook in the internal Skyblivion systems.
        $special_conversions = [
            'TES4AttrStrength' => TES5BasicType::T_GLOBALVARIABLE(),
            'TES4AttrIntelligence' => TES5BasicType::T_GLOBALVARIABLE(),
            'TES4AttrWillpower' => TES5BasicType::T_GLOBALVARIABLE(),
            'TES4AttrAgility' => TES5BasicType::T_GLOBALVARIABLE(),
            'TES4AttrSpeed' => TES5BasicType::T_GLOBALVARIABLE(),
            'TES4AttrEndurance' => TES5BasicType::T_GLOBALVARIABLE(),
            'TES4AttrPersonality' => TES5BasicType::T_GLOBALVARIABLE(),
            'TES4AttrLuck' => TES5BasicType::T_GLOBALVARIABLE(),
            'tContainer' => TES5TypeFactory::memberByValue("TES4Container", TES5BasicType::T_QUEST()), //Data container
            'tTimer' => TES5TypeFactory::memberByValue("TES4TimerHelper", TES5BasicType::T_QUEST()), //Timer functions
            'tGSPLocalTimer' => TES5BasicType::T_FLOAT(), //used for get seconds passed logical conversion
            'TES4CyrodiilCrimeFaction' => TES5BasicType::T_FACTION(), //global cyrodiil faction, WE HAVE BETTER CRIME SYSTEM IN CYRODIIL DAWG
            self::MESSAGEBOX_VARIABLE_CONST => TES5BasicType::T_INT() // set by script instead of original messageBox
        ];

        $this->special_conversions = $special_conversions;

    }

    public function createReferenceToStaticClass($name) {
        return new TES5StaticReference($name);
    }

    public function createReferenceToSelf(TES5GlobalScope $globalScope) {
        //todo perhaps move tes5scriptAsVariable to a new factory
        return new TES5SelfReference(new TES5ScriptAsVariable($globalScope->getScriptHeader()));
    }

    public function createReferenceToVariable(TES5Variable $variable) {
        return new TES5Reference($variable);
    }

    public function createReferenceToPlayer()
    {
        return new TES5PlayerReference();
    }

    /**
     * Create a generic-purpose reference.
     * @param $referenceName
     * @param TES5GlobalScope $globalScope
     * @param TES5MultipleScriptsScope $multipleScriptsScope
     * @param TES5LocalScope $localScope
     * @return \Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectProperty|TES5PlayerReference|TES5Reference
     */
    public function createReference($referenceName, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $multipleScriptsScope, TES5LocalScope $localScope)
    {


        //Papyrus compiler somehow treats properties with ,,temp" in them in a special way, so we change them to tmp to accomodate that.
        if(preg_match('#temp#',$referenceName)) {
            $referenceName = preg_replace("#temp#i","tmp",$referenceName);
        }

        if(strtolower($referenceName) == "player") {
            return $this->createReferenceToPlayer();
        }

        if(preg_match("#([0-9a-zA-Z]+)\.([0-9a-zA-Z]+)#i",$referenceName,$matches)) {
            $mainReference = $this->createReference($matches[1],$globalScope, $multipleScriptsScope, $localScope);
            $propertyReference = $this->objectPropertyFactory->createObjectProperty($multipleScriptsScope, $mainReference,$matches[2]); //Todo rethink the prefix adding
            return $propertyReference;
        }

        $property = $localScope->getVariableByName($referenceName);

        if ($property === null) {

            $property = $globalScope->getPropertyByName($referenceName); //todo rethink how to unify the prefix searching

            if ($property === null) {

                if(isset($this->special_conversions[$referenceName])) {
                        $property = new TES5Property($referenceName, $this->special_conversions[$referenceName], $referenceName);
                }

                if ($property === null) {

                    if (!$multipleScriptsScope->hasGlobalVariable($referenceName)) {
                        $property = new TES5Property($referenceName, TES5BasicType::T_FORM(), $referenceName);
                    } else {
                        $property = new TES5Property($referenceName, TES5BasicType::T_GLOBALVARIABLE(), $referenceName);
                    }
                }

                $globalScope->add($property);
            }

        }

        return new TES5Reference($property);

    }

} 