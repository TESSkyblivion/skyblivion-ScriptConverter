<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Types;

/**
 * Class TES5CustomType
 * @package Ormin\OBSLexicalParser\TES5\Types
 * @method static TES5Type T_TES4CONVERTERHOOK()
 * @method static TES5Type T_TES4CONTAINER()
 * @method static TES5Type T_TES4TIMERHELPER()
 */
class TES5CustomType implements TES5Type {

    const T_TES4CONTAINER = 'TES4Container';
    const T_TES4TIMERHELPER = "TES4TimerHelper";

    /**
     * @var string
     */
    private $typeName;

    /**
     * @var TES5BasicType
     */
    private $nativeType;

    /**
     * @var string
     */
    private $prefix;

    public function __construct($typeName, $prefix, TES5BasicType $nativeType) {
        $this->typeName = $typeName;
        $this->prefix = $prefix;
        $qt = new \ReflectionClass(get_class($this));
        $this->constants = $qt->getConstants();
        $this->nativeType = $nativeType;
    }

    public function value() {
        return $this->typeName;
    }

    public function output() {
        if($this->typeName !== "TES4TimerHelper" && $this->typeName !== "TES4Container") {
            return $this->prefix.$this->value();
        } //no time to refactor now, later.

        return $this->value();
    }

    public function isPrimitive() {
        return $this->nativeType->isPrimitive();
    }

    public function isNativePapyrusType() {
        return false;
    }

    public function getNativeType() {
        return $this->nativeType;
    }

    public function setNativeType(TES5BasicType $basicType) {
        $this->nativeType = $basicType;
    }

} 