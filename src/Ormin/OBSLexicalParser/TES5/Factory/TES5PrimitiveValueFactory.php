<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;

use Ormin\OBSLexicalParser\TES4\AST\Value\Primitive\TES4Primitive;
use Ormin\OBSLexicalParser\TES4\Types\TES4Type;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Integer;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5String;
use Ormin\OBSLexicalParser\TES5\AST\Value\Primitive\TES5Float;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5ConcatenatedValue;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;

class TES5PrimitiveValueFactory {

    /**
     * @param TES4Primitive $value
     * @return TES5Float|TES5Integer|TES5String
     * @throws \Ormin\OBSLexicalParser\TES5\Exception\ConversionException
     */
    public function createValue(TES4Primitive $value) {

        switch($value->getType()) {

            case TES4Type::T_INT(): {
                return new TES5Integer($value->getData());
            }

            case TES4Type::T_STRING(): {
                return new TES5String($value->getData());
            }

            case TES4Type::T_FLOAT(): {
                return new TES5Float($value->getData());
            }

        }

        throw new ConversionException("Unknown value type to be factored from ".get_class($value));
    }

    /**
     * @param TES5Value[] $concatenateValues
     * @return TES5ConcatenatedValue
     */
    public function createConcatenatedValue(array $concatenateValues) {

        return new TES5ConcatenatedValue($concatenateValues);
    }

} 