<?php
/**
 * Created by PhpStorm.
 * Date: 11/20/16
 * Time: 5:18 PM
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Scope;

use Ormin\OBSLexicalParser\TES5\AST\Property\TES5LocalVariable;
use Ormin\OBSLexicalParser\TES5\Context\TES5LocalVariableParameterMeaning;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

/**
 * Class TES5FunctionScope
 * Represents variable scope at function level.
 * @package Ormin\OBSLexicalParser\TES5\AST\Scope
 */
class TES5FunctionScope
{

    /**
     * @var string Block name
     */
    private $blockName;

    /**
     * @var TES5LocalVariable[]
     */
    private $variables = [];

    /**
     * A hashmap to speedup the search
     * @var TES5LocalVariable[]
     */
    private $variablesByMeanings = [];

    /**
     * TES5FunctionScope constructor.
     * @param $blockName
     */
    public function __construct($blockName)
    {
        $this->blockName = $blockName;
    }

    public function addVariable(TES5LocalVariable $localVariable) {
        $this->variables[$localVariable->getPropertyName()] = $localVariable;
        
        foreach($localVariable->getMeanings() as $meaning)
        {
            if(isset($this->variablesByMeanings[$meaning->value()])) {
                throw new ConversionException("Cannot register variable ".$localVariable->getPropertyName()." - it has a
                meaning ".$meaning->value()." that was already registered before.");
            }

            $this->variablesByMeanings[$meaning->value()] = $localVariable;
        }
    }

    public function renameTo($newName)
    {
        $this->blockName = $newName;
    }

    public function getVariableByName($name) {

        return (
            isset($this->variables[$name])
        ) ? $this->variables[$name] : null;

    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Property\TES5LocalVariable[]
     */
    public function getVariables()
    {
        return $this->variables;
    }


    /**
     * @param TES5LocalVariableParameterMeaning $meaning
     * @return null|TES5LocalVariable
     */
    public function findVariableWithMeaning(TES5LocalVariableParameterMeaning $meaning) {

        return (
        isset($this->variablesByMeanings[$meaning->value()])
        ) ? $this->variablesByMeanings[$meaning->value()] : null;

    }

    /**
     * Get the block name. This might be the Event's name, or Function's name.
     */
    public function getBlockName()
    {
        return $this->blockName;
    }


}