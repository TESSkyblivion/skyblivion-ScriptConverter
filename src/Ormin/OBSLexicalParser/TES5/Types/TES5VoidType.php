<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Types;


use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

class TES5VoidType implements TES5Type {

    public function getNativeType() {
        throw new ConversionException("VOID TYPE get native type");
    }

    public function value() {
        return "";
    }

    public function output() {
        throw new ConversionException("VOID TYPE value output");
    }

    public function isPrimitive() {
        return false;
    }

    public function isNativePapyrusType() {
        return false;
    }

    public function setNativeType(TES5BasicType $basicType) {
        throw new ConversionException("Cannot set native type void type.");
    }

} 