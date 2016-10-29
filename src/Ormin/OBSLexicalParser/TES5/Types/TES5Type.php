<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Types;


use Ormin\OBSLexicalParser\TES5\AST\TES5Outputtable;

interface TES5Type extends TES5Outputtable
{
    //Get native type on which this type is based
    //If this type is native, it will return itself.

    /**
     * @return TES5BasicType
     */
    public function getNativeType();

    public function setNativeType(TES5BasicType $basicType);

    public function value();

    public function isPrimitive();

    /**
     * Is this type a native papyrus type ( the one defined by skyrim itself ) or a custom script?
     * @return mixed
     */
    public function isNativePapyrusType();


}