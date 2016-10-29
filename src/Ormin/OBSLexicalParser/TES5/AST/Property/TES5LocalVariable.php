<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Property;


use Ormin\OBSLexicalParser\TES5\AST\TES5ScriptHeader;
use Ormin\OBSLexicalParser\TES5\Context\TES5LocalVariableParameterMeaning;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5LocalVariable implements TES5Variable {

    /**
     * @var
     */
    private $variableName;

    /**
     * @var \Ormin\OBSLexicalParser\TES5\Types\TES5Type
     */
    private $type;

    /**
     * @var array|\Ormin\OBSLexicalParser\TES5\Context\TES5LocalVariableParameterMeaning[]
     */
    private $meanings;

    /**
     * @param $variableName
     * @param TES5BasicType $type
     * @param TES5LocalVariableParameterMeaning[] $meanings
     */
    public function __construct($variableName, TES5BasicType $type, array $meanings = null) {
        $this->variableName = $variableName;
        $this->type = $type;
        $this->meanings = $meanings;
    }

    public function output() {
        return [$this->type->value().' '.$this->variableName];
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\Context\TES5LocalVariableParameterMeaning
     */
    public function getMeanings()
    {
        return $this->meanings;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\Types\TES5BasicType
     */
    public function getPropertyType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getPropertyName()
    {
        return $this->variableName;
    }

    public function setPropertyType(TES5BasicType $type) {
        $this->type = $type;
    }

    /**
     * Todo - following two methods should not be in this interface but TES5Property interface
     * @throws ConversionException
     */
    public function getReferenceEdid() {
        throw new ConversionException("Local variables have no EDID references.");
    }

    public function trackRemoteScript(TES5ScriptHeader $scriptHeader)
    {
        throw new ConversionException("Local variables cannot track remote scripts.");
    }


} 