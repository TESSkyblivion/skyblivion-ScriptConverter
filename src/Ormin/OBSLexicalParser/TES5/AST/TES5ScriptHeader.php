<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST;


use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Factory\TES5TypeFactory;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\Types\TES5InheritanceGraphAnalyzer;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

class TES5ScriptHeader implements TES5Outputtable  {

    /**
     * @var string
     */
    private $scriptName;

    /**
     * @var TES5Type
     */
    private $scriptType;

    /**
     * The basic script type this script header was constructed
     * Used for resolving implicit references.
     * @var TES5Type
     */
    private $basicScriptType;

    private $inheritanceAnalyzer;

    private $scriptNamePrefix;

    private $isHidden;

    /**
     * @var string
     */
    private $edid;

    public function __construct($scriptName, $edid, TES5Type $scriptType, $scriptNamePrefix, $isHidden = false) {
        $this->scriptName = $scriptName;
        $this->edid = $edid;
        $this->scriptNamePrefix = $scriptNamePrefix;
        $this->scriptType = TES5TypeFactory::memberByValue($scriptName,$scriptType);
        $this->basicScriptType = $scriptType;
        $this->isHidden = $isHidden;
        $this->inheritanceAnalyzer = new TES5InheritanceGraphAnalyzer();
    }

    /**
     * @return string
     */
    public function getScriptName()
    {
        return $this->scriptName;
    }

    /**
     * Gets the EDID of this script as it was in oblivion.
     * Script name may be obfuscated with md5 if the name is too long
     */
    public function getEdid() {
        return $this->edid;
    }

    public function output() {

        if($this->isHidden) {
            return ['ScriptName '.$this->scriptNamePrefix.$this->scriptName.' extends '.$this->scriptType->getNativeType()->output().' Hidden'];
        } else {
            return ['ScriptName '.$this->scriptNamePrefix.$this->scriptName.' extends '.$this->scriptType->getNativeType()->output().' Conditional'];
        }
    }

    /**
     * @param TES5BasicType $scriptType
     * @throws ConversionException
     */
    public function setNativeType(TES5BasicType $scriptType)
    {
        if(!$this->inheritanceAnalyzer->isExtending($scriptType,$this->scriptType->getNativeType())) {
            throw new ConversionException("Cannot set script type to non-extending type - current native type ".$this->scriptType->getNativeType()->value().", new type ".$scriptType->value());
        }

        $this->scriptType->setNativeType($scriptType->getNativeType());
    }

    /**
     * @return TES5Type
     */
    public function getScriptType()
    {
        return $this->scriptType;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\Types\TES5Type
     */
    public function getBasicScriptType()
    {
        return $this->basicScriptType;
    }




} 