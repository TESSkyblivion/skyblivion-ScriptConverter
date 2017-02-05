<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\Context;

use Ormin\OBSLexicalParser\TES5\AST\Property\Collection\TES5GlobalVariables;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5GlobalVariable;
use Ormin\OBSLexicalParser\TES5\Context\TypeMapper;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Factory\TES5TypeFactory;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;


/**
 * Class ESMAnalyzer
 * @package Ormin\OBSLexicalParser\TES4\Context
 *
 * Answers the questions regarding the context within the binary data file
 *
 */
class ESMAnalyzer
{

    /**
     * @var
     */
    private $npcLoaded;

    /**
     * @var array
     */
    private $npcs = [];

    /**
     * @var ESMAnalyzer
     */
    private static $instance;

    /**
     * @var string
     */
    private static $esm;

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
     * @param TypeMapper $typeMapper
     * @param string $dataFile
     */
    public function __construct(TypeMapper $typeMapper, $dataFile = "Oblivion.esm")
    {
        $this->typeMapper = $typeMapper;

        if (self::$esm === null) {
            self::$esm = file_get_contents($dataFile);
        }

        if ($this->scriptTypes === null) {

            preg_match_all("#SCPT................EDID..([a-zA-Z0-9_-]+)\x{00}SCHR..................(..)#si", self::$esm, $scripts);
            foreach ($scripts[2] as $i => $type) {
                $is_q = (bool)ord($type[0]);
                $is_m = (bool)ord($type[1]);

                if ($is_q) {
                    $scriptType = TES5BasicType::T_QUEST();
                } else if ($is_m) {
                    $scriptType = TES5BasicType::T_ACTIVEMAGICEFFECT();
                } else {
                    $scriptType = TES5BasicType::T_OBJECTREFERENCE();
                }

                $this->scriptTypes[strtolower($scripts[1][$i])] = $scriptType;

            }

        }

        if ($this->globals === null) {

            preg_match_all("#GLOB................EDID..([a-zA-Z0-9_-]+)\x{00}#si", self::$esm, $globals);
            $globalArray = [];
            foreach ($globals[1] as $global) {
                $globalArray[] = new TES5GlobalVariable($global);
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
            $analyzer = new ESMAnalyzer();
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

        $len = strlen($EDID);
        $len = $len + 1;
        $hex = dechex($len);
        if (strlen($hex) == 1)
            $hex = '0' . $hex;

        preg_match("#EDID\x{" . $hex . "}." . $EDID . "\x{00}#i", self::$esm, $matches, PREG_OFFSET_CAPTURE);

        if (isset($matches[0])) {
            $offset = $matches[0][1] - 20;
            $type = substr(self::$esm, $offset, 4);

            return TypeMapper::map($type);
        } else {

            $npc = $this->getNpcByEDID($EDID);

            if($npc === null) {
                throw new ConversionException("Cannot find type for EDID " . $EDID);
            }

            return TES5BasicType::T_ACTOR();

        }
    }

    /**
     * @param $EDID
     * @return bool|null
     */
    private function getNpcByEDID($EDID) {

        if(!$this->npcLoaded) {

            preg_match("#GRUP(....)NPC_#si",self::$esm,$matches,PREG_OFFSET_CAPTURE);

            $size = 0;

            for($i = 0; $i <= 3; ++$i) {
                $size += ord($matches[1][0][$i]) * pow(256,$i);
            }

            $npcData = substr(self::$esm, $matches[0][1], $size);
            $pointer = 20; //First GRUP record is at 20th byte.

            while($pointer < $size) {

                $baseDataOffset = $pointer + 24;
                $dataLength = 0;
                for ($i = 0; $i < 4; ++$i) {
                    $dataLength += ord($npcData[$pointer+4+$i]) * pow(256, $i);
                }

                $dataLength -= 4;

                $gzippedData = substr($npcData, $baseDataOffset, $dataLength);
                $ungzippedData = gzuncompress($gzippedData);

                $edidLengthBytes = substr($ungzippedData,4,2);
                $edidLength = ord($edidLengthBytes[0]) + ord($edidLengthBytes[1]) * 256;

                $targetEdid = substr($ungzippedData,6,$edidLength-1);
                $this->npcs[] = strtolower($targetEdid);

                $pointer += 24;
                $pointer += $dataLength;

            }


            $this->npcLoaded = true;
        }

        if(in_array(strtolower($EDID),$this->npcs)) {
            return true;
        }

        return null;

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


            if ( //three preg matches are for performance reasons - much better than grouping
                preg_match("#REFR................EDID..(?i)" . $attachedName . "(?-i)\x{00}NAME..(....)#s", self::$esm, $refrFormidMatches) ||
                preg_match("#ACRE................EDID..(?i)" . $attachedName . "(?-i)\x{00}NAME..(....)#s", self::$esm, $acreFormidMatches) ||
                preg_match("#ACHR................EDID..(?i)" . $attachedName . "(?-i)\x{00}NAME..(....)#s", self::$esm, $achrFormidMatches)
            ) {

                if(!empty($refrFormidMatches)) {
                    $formidMatches = $refrFormidMatches;
                    $searchedFormType = "(?!SCPT)[A-Z]{4}"; //TODO - this can be a specific list of objects, perhaps do a search of this list?
                } else {

                    if(!empty($acreFormidMatches)) {
                        $formidMatches = $acreFormidMatches;
                        $searchedFormType = "CREA";
                    } else {
                        $formidMatches = $achrFormidMatches;
                        $searchedFormType = "NPC_";
                    }

                }

                //We have a REFR, we have to unpack it and match the formid
                $targetFormid = $formidMatches[1];
                $targetFormidString = "";
                for ($i = 0; $i < 4; ++$i) {
                    $hexCharacter = dechex(ord(substr($targetFormid, $i, 1)));
                    if (strlen($hexCharacter) == 1) {
                        $hexCharacter = '0' . $hexCharacter;
                    }
                    $targetFormidString .= "\x{" . $hexCharacter . "}";
                }

                if($searchedFormType != "NPC_") {
                    preg_match("#".$searchedFormType."........" . $targetFormidString . ".*?SCRI..(....)#s", self::$esm, $matches);
                } else {
                    if (preg_match("#NPC_(....)...." . $targetFormidString . "#s", self::$esm, $failoverMatches, PREG_OFFSET_CAPTURE)) {
                        $baseDataOffset = $failoverMatches[0][1] + 24;
                        $dataLengthMatch = $failoverMatches[1][0];
                        $dataLength = 0;
                        for ($i = 0; $i < 4; ++$i) {
                            $dataLength += ord($dataLengthMatch[$i]) * pow(256, $i);
                        }

                        $dataLength -= 4;

                        $gzippedData = substr(self::$esm, $baseDataOffset, $dataLength);
                        $ungzippedData = gzuncompress($gzippedData);
                        preg_match("#SCRI..(....)#si", $ungzippedData, $matches);
                    }
                }

            } else {
                //Just go with usual matching via EDID
                preg_match("#EDID..(?i)" . $attachedName . "(?-i)\x{00}.*?SCRI..(....)#s", self::$esm, $matches);

            }

            if (empty($matches)) {
                throw new ConversionException("Cannot resolve script type by searching its base form edid " . $attachedName);
            }

            $hex = $matches[1];
            $hexString = "";
            $hexFormid = "";
            for ($i = 0; $i < 4; ++$i) {
                $hexCharacter = dechex(ord(substr($hex, $i, 1)));
                if (strlen($hexCharacter) == 1) {
                    $hexCharacter = '0' . $hexCharacter;
                }
                $hexString .= "\x{" . $hexCharacter . "}";
                $hexFormid .= $hexCharacter;
            }


            preg_match("#SCPT........" . $hexString . "....EDID..([a-zA-Z0-9]+)#si", self::$esm, $dataMatches);

            if (empty($dataMatches)) {
                throw new ConversionException("For EDID " . $attachedName . " and script formid " . $hexFormid . " we couldn't find any scripts in ESM.");
            }

            $customType = TES5TypeFactory::memberByValue($dataMatches[1]);
            $this->attachedNameCache[strtolower($attachedName)] = $customType;
        }

        return $this->attachedNameCache[strtolower($attachedName)];

    }

}
