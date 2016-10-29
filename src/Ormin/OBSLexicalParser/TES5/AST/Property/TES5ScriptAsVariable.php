<?php

namespace Ormin\OBSLexicalParser\TES5\AST\Property;

use Ormin\OBSLexicalParser\TES5\AST\TES5ScriptHeader;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5ScriptAsVariable implements TES5Variable
{

    private $scriptHeader;

    public function __construct(TES5ScriptHeader $scriptHeader)
    {
        $this->scriptHeader = $scriptHeader;
    }

    public function getPropertyName()
    {
        return 'self';
    }

    public function output()
    {
        return ['self'];
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\Types\TES5Type
     */
    public function getPropertyType()
    {
        return $this->scriptHeader->getScriptType();
    }

    /**
     * @param TES5BasicType $type
     * @return void
     */
    public function setPropertyType(TES5BasicType $type)
    {
        $this->scriptHeader->setNativeType($type);
    }

    public function getReferenceEdid()
    {
        return $this->scriptHeader->getEdid();
    }

    public function trackRemoteScript(TES5ScriptHeader $scriptHeader) {
        throw new ConversionException("Cannot track TES5ScriptAsVariable as it tracks already.");
    }
}