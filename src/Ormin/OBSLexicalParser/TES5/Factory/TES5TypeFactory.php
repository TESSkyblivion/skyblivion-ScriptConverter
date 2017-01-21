<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\Types\TES5CustomType;
use Ormin\OBSLexicalParser\TES5\Types\TES5VoidType;

class TES5TypeFactory {

    private static $scriptsPrefix = "TES4";

    public static function void() {
        return self::memberByValue('void');
    }

    /**
     * @param $memberByValue string Type to be created.
     * @param $basicType TES5BasicType You might override the basic type for this custom type created.
     * @return \Eloquent\Enumeration\ValueMultitonInterface|TES5CustomType|TES5VoidType
     */
    public static function memberByValue($memberByValue, TES5BasicType $basicType = null) {

        if($memberByValue == "void") {
            return new TES5VoidType();
        }

        try {
            return TES5BasicType::memberByValue(ucwords($memberByValue));
        } catch( \Exception $e) {
            //Ugly - todo: REFACTOR THIS TO NON-STATIC CLASS AND MOVE THIS TO DI
            if($basicType === null) {
                $analyzer = ESMAnalyzer::instance();
                $basicType = $analyzer->getScriptType($memberByValue);
            }


            return new TES5CustomType(
                TES5NameTransformer::transform($memberByValue,self::$scriptsPrefix),
                self::$scriptsPrefix,
                $memberByValue,
                $basicType);
        }


    }

} 