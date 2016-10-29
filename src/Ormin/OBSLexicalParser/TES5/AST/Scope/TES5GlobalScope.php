<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Scope;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5Property;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5Variable;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5GlobalVariable;
use Ormin\OBSLexicalParser\TES5\AST\TES5Outputtable;
use Ormin\OBSLexicalParser\TES5\AST\TES5ScriptHeader;

class TES5GlobalScope implements TES5Outputtable {


    /**
     * @var TES5ScriptHeader
     */
    private $scriptHeader;

    /**
     * @var TES5Property[]
     */
    private $properties = [];

    /**
     * @var TES5GlobalVariable[]
     */
    private $globalVariables = [];

    public function __construct(TES5ScriptHeader $scriptHeader) {
        $this->scriptHeader = $scriptHeader;
    }

    public function getScriptHeader() {
        return $this->scriptHeader;
    }

    public function add(TES5Property $declaration) {
        $this->properties[] = $declaration;
    }


    public function output() {

        $codeLines = [];
        foreach($this->properties as $property) {
            $codeLines = array_merge($codeLines,$property->output());
        }

        return $codeLines;
    }

    public function addGlobalVariable(TES5GlobalVariable $globalVariable) {
        $this->globalVariables[] = $globalVariable;
    }

    public function getPropertyByName($propertyName) {
        foreach($this->properties as $property) {
            if(strtolower($propertyName)."_p" == strtolower($property->getPropertyName())) {
                //Token found.
                return $property;
            }
        }

        return null;
    }

    public function getGlobalVariableByName($globalVariableName) {
        foreach($this->globalVariables as $globalVariable) {
            if(strtolower($globalVariableName) == strtolower($globalVariable->getName())) {
                //Token found.
                return $globalVariable;
            }
        }

        return null;
    }

    public function hasGlobalVariable($globalVariableName) {
        return $this->getGlobalVariableByName($globalVariableName) !== null;
    }

} 