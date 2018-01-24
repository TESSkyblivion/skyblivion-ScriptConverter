<?php
/**
 * Created by PhpStorm.
 * Date: 2/5/17
 * Time: 3:12 PM
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Property\Collection;


use Ormin\OBSLexicalParser\TES5\AST\Property\TES5GlobalVariable;

class TES5GlobalVariables
{

    /**
     * @var TES5GlobalVariable[]
     */
    private $globalVariables;

    /**
     * TES5GlobalVariables constructor.
     * @param TES5GlobalVariable[] $globalVariables
     */
    public function __construct($globalVariables)
    {
        $globalVariablesIndex = [];
        foreach($globalVariables as $globalVariable)
        {
            $globalVariablesIndex[strtolower($globalVariable->getName())] = $globalVariable;
        }

        $this->globalVariables = $globalVariablesIndex;

    }


    public function hasGlobalVariable($globalVariableName) {
        return isset($this->globalVariables[strtolower($globalVariableName)]);
    }

}