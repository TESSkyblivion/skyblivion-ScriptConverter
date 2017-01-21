<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Scope;


use Ormin\OBSLexicalParser\TES5\AST\Property\TES5LocalVariable;
use Ormin\OBSLexicalParser\TES5\AST\TES5Outputtable;
use Ormin\OBSLexicalParser\TES5\Context\TES5LocalVariableParameterMeaning;

/**
 * TES5LocalScope represents a local scope of variables - i.e. the variables which are known in a given scope.
 * Local scope can have a parent scope ( as in - you can travel local scopes as a linked list from the leafs up to
 * the root )
 * Class TES5LocalScope
 * @package Ormin\OBSLexicalParser\TES5\AST\Scope
 */
class TES5LocalScope implements TES5Outputtable {

    /**
     * @var TES5FunctionScope
     */
    private $functionScope;

    /**
     * @var TES5LocalScope
     */
    private $parentScope;

    /**
     * @var TES5LocalVariable[]
     */
    private $variables = [];

    /**
     * TES5LocalScope constructor.
     * @param TES5FunctionScope $functionScope
     * @param null $parentScope
     */
    public function __construct(TES5FunctionScope $functionScope, $parentScope = null) {
        $this->functionScope = $functionScope;
        $this->parentScope = $parentScope;
    }

    public function output() {
        $codeLines = [];
        foreach($this->variables as $variable) {
            $codeLines = array_merge($codeLines,$variable->output());
        }

        return $codeLines;
    }

    public function addVariable(TES5LocalVariable $localVariable) {
        $this->variables[] = $localVariable;
    }

    /**
     * @return null|TES5LocalScope
     */
    public function getParentScope()
    {
        return $this->parentScope;
    }

    /**
     * @return TES5FunctionScope
     */
    public function getFunctionScope()
    {
        return $this->functionScope;
    }

    /**
     * @param $parentScope
     */
    public function setParentScope($parentScope)
    {
        $this->parentScope = $parentScope;
    }



    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Property\TES5LocalVariable[]
     */
    public function getLocalVariables()
    {
        return $this->variables;
    }

    public function getVariableByName($name) {
        $variables = $this->getVariables();

        foreach($variables as $variable) {
            if($variable->getPropertyName() == $name) {
                return $variable;
            }
        }

        return null;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Property\TES5LocalVariable[]
     */
    public function getVariables()
    {
        $variables = [];
        $scope = $this;
        do {
            $variablePack = $scope->getLocalVariables();
            $variables = array_merge($variables,$variablePack);
            $scope = $scope->getParentScope();
        } while($scope !== null);

        $variables = array_merge($variables, $this->functionScope->getVariables());

        return $variables;
    }


    /**
     * @param TES5LocalVariableParameterMeaning $meaning
     * @return null|TES5LocalVariable
     */
    public function findVariableWithMeaning(TES5LocalVariableParameterMeaning $meaning) {
        return $this->functionScope->findVariableWithMeaning($meaning);
    }



}