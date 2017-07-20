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
use Skyblivion\ESReader\TES4\TES4FileLoadScheme;
use Skyblivion\ESReader\TES4\TES4GrupLoadScheme;
use Skyblivion\ESReader\TES4\TES4Record;
use Skyblivion\ESReader\TES4\TES4RecordLoadScheme;
use Skyblivion\ESReader\TES4\TES4RecordType;


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

            //NOTE - SCRI record load scheme is a copypasta, as in, i didnt check which records do actually might have SCRI
            //Doesnt really matter for other purposes than cleaniness
            $fileScheme = new TES4FileLoadScheme();
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::GMST(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::GMST(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::GLOB(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::GLOB(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::CLAS(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::CLAS(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::FACT(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::FACT(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::HAIR(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::HAIR(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::EYES(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::EYES(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::RACE(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::RACE(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::SOUN(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::SOUN(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::SKIL(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::SKIL(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::MGEF(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::MGEF(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::SCPT(), new TES4RecordLoadScheme(['EDID', 'SCRI', 'SCHR']));
            $fileScheme->add(TES4RecordType::SCPT(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::LTEX(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::LTEX(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::ENCH(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::ENCH(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::SPEL(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::SPEL(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::BSGN(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::BSGN(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::ACTI(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::ACTI(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::APPA(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::APPA(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::ARMO(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::ARMO(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::BOOK(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::BOOK(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::CLOT(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::CLOT(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::CONT(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::CONT(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::DOOR(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::DOOR(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::INGR(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::INGR(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::LIGH(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::LIGH(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::MISC(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::MISC(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::STAT(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::STAT(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::GRAS(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::GRAS(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::TREE(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::TREE(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::FLOR(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::FLOR(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::FURN(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::FURN(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::WEAP(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::WEAP(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::AMMO(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::AMMO(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::NPC_(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::NPC_(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::CREA(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::CREA(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::LVLC(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::LVLC(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::SLGM(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::SLGM(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::KEYM(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::KEYM(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::ALCH(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::ALCH(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::SBSP(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::SBSP(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::SGST(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::SGST(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::LVLI(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::LVLI(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::WTHR(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::WTHR(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::CLMT(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::CLMT(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::REGN(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::REGN(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::CELL(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $grupScheme->add(TES4RecordType::REFR(), new TES4RecordLoadScheme(['EDID', 'NAME']));
            $grupScheme->add(TES4RecordType::ACHR(), new TES4RecordLoadScheme(['EDID', 'NAME']));
            $grupScheme->add(TES4RecordType::ACRE(), new TES4RecordLoadScheme(['EDID', 'NAME']));
            $fileScheme->add(TES4RecordType::CELL(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::WRLD(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $grupScheme->add(TES4RecordType::CELL(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $grupScheme->add(TES4RecordType::REFR(), new TES4RecordLoadScheme(['EDID', 'NAME']));
            $grupScheme->add(TES4RecordType::ACHR(), new TES4RecordLoadScheme(['EDID', 'NAME']));
            $grupScheme->add(TES4RecordType::ACRE(), new TES4RecordLoadScheme(['EDID', 'NAME']));
            $fileScheme->add(TES4RecordType::WRLD(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::DIAL(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::DIAL(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::QUST(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::QUST(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::IDLE(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::IDLE(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::PACK(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::PACK(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::CSTY(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::CSTY(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::LSCR(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::LSCR(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::LVSP(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::LVSP(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::ANIO(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::ANIO(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::WATR(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::WATR(),$grupScheme);
            $grupScheme = new TES4GrupLoadScheme();
            $grupScheme->add(TES4RecordType::EFSH(), new TES4RecordLoadScheme(['EDID', 'SCRI']));
            $fileScheme->add(TES4RecordType::EFSH(),$grupScheme);

            $collection->load($fileScheme);
            self::$esm = $collection;
        }

        if ($this->scriptTypes === null) {
            $this->scriptTypes = [];
            $scpts = self::$esm->getGrup(TES4RecordType::SCPT());

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

                $this->scriptTypes[strtolower(trim($edid))] = $scriptType;

            }

        }

        if ($this->globals === null) {

            $globals = self::$esm->getGrup(TES4RecordType::GLOB());
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
                   $attachedNameRecordType == "ACHR")
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

    /**
     * Makes the adapter unusable by deallocating the esm object.
     * This really ought to be more clean, but until this class is used statically we have no other choice
     */
    public static function deallocate()
    {
        //Drop the ref
        self::$esm = null;
        //Force the GC
        gc_collect_cycles();
    }

}
