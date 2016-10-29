<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES4\AST\VariableDeclaration\TES4VariableDeclarationList;
use Ormin\OBSLexicalParser\TES4\Types\TES4Type;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5LocalVariable;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5LocalVariableListFactory {

    public function createCodeChunk(TES4VariableDeclarationList $chunk, TES5CodeScope $codeScope) {

        foreach($chunk->getVariableList() as $variable) {
            switch($variable->getVariableType()) {

                case TES4Type::T_FLOAT(): {
                    $property = new TES5LocalVariable($variable->getVariableName(), TES5BasicType::T_FLOAT());
                    break;
                }

                case TES4Type::T_INT():
                case TES4Type::T_SHORT():
                case TES4Type::T_LONG(): {
                    $property = new TES5LocalVariable($variable->getVariableName(),TES5BasicType::T_INT());
                    break;
                }

                case TES4Type::T_REF(): {

                    //most basic one, if something from inherited class is used, we will set to the inheriting class
                    $property = new TES5LocalVariable($variable->getVariableName(),TES5BasicType::T_FORM());
                    break;
                }

                default: {
                    throw new ConversionException("Unknown local variable declaration type.");
                }

            }

            $codeScope->getLocalScope()->addVariable($property);
        }

        return null;


    }

} 