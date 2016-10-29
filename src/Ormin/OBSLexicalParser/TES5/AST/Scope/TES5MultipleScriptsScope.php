<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 23.12.15
 * Time: 21:46
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Scope;


use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

class TES5MultipleScriptsScope
{

    /**
     * @var TES5GlobalScope[]
     */
    private $globalScopes;

    /**
     * @param TES5GlobalScope[] $globalScopes
     */
    public function __construct(array $globalScopes) {
        $globalScopesMapped = [];
        foreach ($globalScopes as $globalScope) {
            $globalScopesMapped[strtolower($globalScope->getScriptHeader()->getScriptName())] = $globalScope;
        }

        $this->globalScopes = $globalScopesMapped;

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



}