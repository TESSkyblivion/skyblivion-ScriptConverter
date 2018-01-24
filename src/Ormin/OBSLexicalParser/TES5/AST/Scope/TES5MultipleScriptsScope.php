<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 23.12.15
 * Time: 21:46
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Scope;


use Ormin\OBSLexicalParser\TES5\AST\Property\Collection\TES5GlobalVariables;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5GlobalVariable;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

/**
 * Defines a scope of multiple scripts
 * Under this scope, things can interact together , send type-related information between scripts etc.
 * Also holds global variables list which are registered under these scripts
 * Class TES5MultipleScriptsScope
 * @package Ormin\OBSLexicalParser\TES5\AST\Scope
 */
class TES5MultipleScriptsScope
{

    /**
     * @var TES5GlobalScope[]
     */
    private $globalScopes;

    /**
     * @var TES5GlobalVariable[]
     */
    private $globalVariables;

    /**
     * @param TES5GlobalScope[] $globalScopes
     * @param TES5GlobalVariables $globalVariables
     */
    public function __construct(array $globalScopes, TES5GlobalVariables $globalVariables) {
        $globalScopesMapped = [];
        foreach ($globalScopes as $globalScope) {
            $globalScopesMapped[strtolower($globalScope->getScriptHeader()->getScriptName())] = $globalScope;
        }

        $this->globalScopes = $globalScopesMapped;
        $this->globalVariables = $globalVariables;

    }

    public function getScriptHeaderOfScript($scriptName) {

        if(!isset($this->globalScopes[strtolower($scriptName)])) {
            throw new ConversionException("TES5MultipleScriptsScope::getPropertyFromScript() - Cannot find a global scope for script ".$scriptName." - make sure that the multiple scripts scope is built correctly.");
        }

        return $this->globalScopes[strtolower($scriptName)]->getScriptHeader();
    }


    public function getPropertyFromScript($scriptName, $propertyName) {

        if(!isset($this->globalScopes[strtolower($scriptName)])) {
            throw new ConversionException("TES5MultipleScriptsScope::getPropertyFromScript() - Cannot find a global scope for script ".$scriptName." - make sure that the multiple scripts scope is built correctly.");
        }

        $property = $this->globalScopes[strtolower($scriptName)]->getPropertyByName($propertyName);

        if($property === null) {
            throw new ConversionException("TES5MultipleScriptsScope::getPropertyFromScript() - Cannot find a property ".$propertyName." in script name ".$scriptName);
        }

        return $property;

    }

    public function hasGlobalVariable($globalVariableName) {
        return $this->globalVariables->hasGlobalVariable($globalVariableName);
    }

}