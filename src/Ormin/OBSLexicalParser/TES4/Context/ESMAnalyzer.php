<?php

namespace Ormin\OBSLexicalParser\TES4\Context;

use Ormin\OBSLexicalParser\TES5\AST\Property\Collection\TES5GlobalVariables;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5GlobalVariable;
use Ormin\OBSLexicalParser\TES5\Context\TypeMapper;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Factory\TES5TypeFactory;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;
use Skyblivion\ESReader\Exception\RecordNotFoundException;
use Skyblivion\ESReader\TES4\TES4Collection;
use Skyblivion\ESReader\TES4\TES4Record;


/**
 * Class ESMAnalyzer
 * @package Ormin\OBSLexicalParser\TES4\Context
 *
 * Answers the questions regarding the context within the binary data file
 * Acts as a legacy adapter interface between ScriptConverter and ESReader
 *
 */
class ESMAnalyzer
{
    /**
     * @var TES5Type[]
     */
    private $scriptTypes;

    /**
     * @var TES5GlobalVariables
     */
    private $globals;

    /**
     * @var TypeMapper
     */
    private $typeMapper;

    /**
     * @var array
     */
    private $attachedNameCache = [];

    /**
     * @var TES4Collection
     */
    private static $esm;

    /**
     * @var ESMAnalyzer
     */
    private static $instance;

    /**
     * @param TypeMapper $typeMapper
     * @param string $dataFile
     */
    public function __construct(TypeMapper $typeMapper, $dataFile = "Oblivion.esm")
    {
        $this->typeMapper = $typeMapper;

        if (self::$esm === null) {
            $collection = new TES4Collection("./");
            $collection->add($dataFile);
            $collection->load();
            self::$esm = $collection;
        }

        if ($this->scriptTypes === null) {

            $scpts = self::$esm->getGrup('SCPT');

            /**
             * @var TES4Record $scpt
             */
            foreach($scpts as $scpt)
            {
                $schr = $scpt->getSubrecord('SCHR');
                $edid = $scpt->getSubrecord('EDID');
                if(!$schr || !$edid) {
                    continue;
                }

                $is_q = (bool)ord(substr($schr,16,1));
                $is_m = (bool)ord(substr($schr,17,1));

                if ($is_q) {
                    $scriptType = TES5BasicType::T_QUEST();
                } else if ($is_m) {
                    $scriptType = TES5BasicType::T_ACTIVEMAGICEFFECT();
                } else {
                    $scriptType = TES5BasicType::T_OBJECTREFERENCE();
                }

                $this->scriptTypes[trim($edid)] = $scriptType;

            }

        }

        if ($this->globals === null) {

            $globals = self::$esm->getGrup('GLOB');
            $globalArray = [];

            /**
             * @var TES4Record $global
             */
            foreach ($globals as $global) {
                $edid = $global->getSubrecord('EDID');
                if(!$edid) {
                    continue;
                }

                $globalArray[] = new TES5GlobalVariable(trim($edid));
            }

            /**
             * Hacky - add infamy into the globals array
             * Probably we should extract this from this class and put this into other place
             */
            $globalArray[] = new TES5GlobalVariable('Infamy');

            $this->globals = new TES5GlobalVariables($globalArray);

        }

        if (self::$instance === null) {
            self::$instance = $this;
        }

    }

    /**
     * @return ESMAnalyzer
     */
    public static function instance()
    {

        if (self::$instance === null) {
            $analyzer = new ESMAnalyzer(new TypeMapper());
            return $analyzer;
        }

        return self::$instance;

    }

    /**
     * @return TES5GlobalVariables
     */
    public function getGlobalVariables()
    {
        return $this->globals;
    }

    /**
     * @param string $EDID
     * @return TES5Type
     * @throws ConversionException
     */
    public function getFormTypeByEDID($EDID)
    {

        try {
            $record = self::$esm->findByEDID($EDID);
            return TypeMapper::map($record->getType());
        } catch(RecordNotFoundException $e) {
            throw new ConversionException("Cannot find type for EDID " . $EDID);
        }
    }

    /**
     * @param string $scriptName
     * @return TES5Type
     * @throws ConversionException
     */
    public function getScriptType($scriptName)
    {
        $scriptName = strtolower($scriptName);
        if (!isset($this->scriptTypes[$scriptName])) {

            $tryAgainst = preg_replace("#_#", "", $scriptName);

            if (!isset($this->scriptTypes[$tryAgainst])) {
                throw new ConversionException("Script " . $scriptName . " not found in ESM - cannot find its script type.");
            }

            return $this->scriptTypes[$tryAgainst];
        }

        return $this->scriptTypes[$scriptName];

    }

    /**
     * @param $attachedName
     * @return \Eloquent\Enumeration\ValueMultitonInterface|\Ormin\OBSLexicalParser\TES5\Types\TES5CustomType|\Ormin\OBSLexicalParser\TES5\Types\TES5VoidType
     * @todo REFACTOR, it's really ugly!
     * @throws ConversionException
     */
    public function resolveScriptTypeByItsAttachedName($attachedName)
    {

        if(!isset($this->attachedNameCache[strtolower($attachedName)])) {

            try
            {
                $attachedNameRecord = self::$esm->findByEDID($attachedName);
                $attachedNameRecordType = $attachedNameRecord->getType();

                if($attachedNameRecordType == "REFR" ||
                   $attachedNameRecordType == "ACRE" ||
                   $attachedNameRecordType == "CREA")
                {
                    //Resolve the reference
                    $baseFormid = $attachedNameRecord->getSubrecordAsFormid('NAME');
                    $attachedNameRecord = self::$esm->findByFormid($baseFormid);
                }

                $scriptFormid = $attachedNameRecord->getSubrecordAsFormid('SCRI');
                if($scriptFormid === null)
                {
                    throw new ConversionException("Cannot resolve script type for ".$attachedName." - Asked base record has no script bound.");
                }
                $scriptRecord = self::$esm->findByFormid($scriptFormid);
                $customType = TES5TypeFactory::memberByValue(trim($scriptRecord->getSubrecord('EDID')));
                $this->attachedNameCache[strtolower($attachedName)] = $customType;


            }
            catch(RecordNotFoundException $e)
            {
                throw new ConversionException("Cannot resolve script type by searching its base form edid - no record found, ".$attachedName);
            }

        }

        return $this->attachedNameCache[strtolower($attachedName)];

    }

}
