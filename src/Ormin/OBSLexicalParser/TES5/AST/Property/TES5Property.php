<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Property;


use Ormin\OBSLexicalParser\TES5\AST\TES5ScriptHeader;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\Types\TES5InheritanceGraphAnalyzer;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

class TES5Property implements TES5Variable
{

    const PROPERTY_SUFFIX = "_p";

    /**
     * The property's name as seen in script
     * @var string
     */
    private $propertyName;

    /**
     * Property's type
     * @var TES5Type
     */
    private $propertyType;

    /**
     * Each property may be referencing to a specific EDID ( either it's a converted property and its name minus prefix should match it, or it's a new property created and then it ,,inherits" :)
     * @var string
     */
    private $referenceEdid;

    /**
     * Tracked remote script, if any
     * @var TES5ScriptHeader
     */
    private $trackedScript;


    function __construct($propertyName, TES5Type $propertyType, $referenceEdid)
    {
        $this->propertyName = $propertyName . self::PROPERTY_SUFFIX; //we're adding _p prefix because papyrus compiler complains about property names named after other scripts, _p makes sure we won't conflict.
        $this->propertyType = $propertyType; //If we're tracking a script, this won't be used anymore
        $this->referenceEdid = $referenceEdid;
        $this->trackedScript = null;
    }

    public function output()
    {
        $propertyType = $this->getPropertyType()->output();
        //Todo - Actually differentiate between properties which need and do not need to be conditional
        return [$propertyType . ' Property ' . $this->propertyName . ' Auto Conditional'];
    }

    /**
     * @return mixed
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    public function renameTo($newName)
    {
        $this->propertyName = $newName . self::PROPERTY_SUFFIX;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\Types\TES5Type
     */
    public function getPropertyType()
    {
        return ($this->trackedScript !== null) ? $this->trackedScript->getScriptType() : $this->propertyType;
    }

    public function setPropertyType(TES5BasicType $type)
    {
        if($this->trackedScript !== null) {
            $this->trackedScript->setNativeType($type);
        } else {
            $this->propertyType = $type;
        }
    }

    /**
     * @return string
     */
    public function getReferenceEdid()
    {
        return $this->referenceEdid;
    }

    public function trackRemoteScript(TES5ScriptHeader $scriptHeader)
    {

        $this->trackedScript = $scriptHeader;
        $ourNativeType = $this->propertyType->getNativeType();
        $remoteNativeType = $this->trackedScript->getScriptType()->getNativeType();

        /**
         * Scenario 1 - types are equal or the remote type is higher than ours in which case we do nothing as they have the good type anyway
         */
        if ($ourNativeType == $remoteNativeType || TES5InheritanceGraphAnalyzer::isExtending($remoteNativeType,$ourNativeType)) {
            return;
        }
        /**
         * Scenario 2 - Our current native type is extending remote script's extended type - we need to set it properly
         */
        elseif (TES5InheritanceGraphAnalyzer::isExtending($ourNativeType, $remoteNativeType)) {
            $this->trackedScript->setNativeType($ourNativeType);
        } else {
            throw new ConversionException("TES5Property::trackRemoteScript() - The definitions of local property type and remote property type have diverged
            ( ours: " . $ourNativeType->value() . ", remote: " . $remoteNativeType->value());
        }


    }


} 