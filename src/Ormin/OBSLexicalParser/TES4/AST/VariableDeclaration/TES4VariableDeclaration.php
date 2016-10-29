<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */
namespace Ormin\OBSLexicalParser\TES4\AST\VariableDeclaration;


use Ormin\OBSLexicalParser\TES4\Types\TES4Type;

class TES4VariableDeclaration  {

    /**
     * @var \Ormin\OBSLexicalParser\TES4\Types\TES4Type
     */
    private $variableType;

    /**
     * @var string
     */
    private $variableName;

    function __construct($variableName, TES4Type $variableType)
    {
        $this->variableName = $variableName;
        $this->variableType = $variableType;
    }

    /**
     * @return string
     */
    public function getVariableName()
    {
        //Papyrus compiler somehow treats properties with ,,temp" in them in a special way, so we change them to tmp to accomodate that.
        if(preg_match('#temp#i',$this->variableName)) {
            $this->variableName = preg_replace("#temp#i","tmp",$this->variableName);
        }

        return $this->variableName;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES4\Types\TES4Type
     */
    public function getVariableType()
    {
        return $this->variableType;
    }




}