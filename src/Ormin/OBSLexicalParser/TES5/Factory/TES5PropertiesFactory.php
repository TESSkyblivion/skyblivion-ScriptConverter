<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES4\AST\VariableDeclaration\TES4VariableDeclaration;
use Ormin\OBSLexicalParser\TES4\AST\VariableDeclaration\TES4VariableDeclarationList;
use Ormin\OBSLexicalParser\TES4\Types\TES4Type;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5Property;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5Variable;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5PropertiesFactory {


    /**
     * Create an pre-defined property from a ref VariableDeclaration
     * @param TES4VariableDeclaration $declaration
     * @return TES5Variable
     */
    private function createPropertyFromReference(TES4VariableDeclaration $declaration)
    {
        return new TES5Property($declaration->getVariableName(), TES5BasicType::T_FORM(), $declaration->getVariableName());
    }

    public function createProperties(TES4VariableDeclarationList $variableList, TES5GlobalScope $globalScope) {


        /**
         * @var TES4VariableDeclaration[] $alreadyDefinedVariables
         */
        $alreadyDefinedVariables = [];

        foreach ($variableList->getVariableList() as $variable) {

            $theVariableName = strtolower($variable->getVariableName());

            if(isset($alreadyDefinedVariables[$theVariableName])) {

                if($variable->getVariableType() == $alreadyDefinedVariables[$theVariableName]->getVariableType()) {
                    continue; //Same variable defined twice, smack the original script developer and fallthrough silently.
                }

                throw new ConversionException("Double definition of variable named ".$variable->getVariableName()." with different types ( ".$alreadyDefinedVariables[$theVariableName]->getVariableType()->value()." and ".$variable->getVariableType()->value()." )");
            }

            switch ($variable->getVariableType()) {

                case TES4Type::T_FLOAT():
                {
                    $property = new TES5Property($variable->getVariableName(), TES5BasicType::T_FLOAT(), null);
                    break;
                }

                case TES4Type::T_INT():
                case TES4Type::T_SHORT():
                case TES4Type::T_LONG():
                {
                    $property = new TES5Property($variable->getVariableName(), TES5BasicType::T_INT(), null);
                    break;
                }

                case TES4Type::T_REF():
                {
                    $property = $this->createPropertyFromReference($variable);
                    break;
                }

                default:
                    {
                    throw new ConversionException("Unknown variable declaration type.");
                    }

            }

            $globalScope->add($property);
            $alreadyDefinedVariables[$theVariableName] = $variable;
        }

    }

} 