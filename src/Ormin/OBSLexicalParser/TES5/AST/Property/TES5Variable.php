<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Property;
use Ormin\OBSLexicalParser\TES5\AST\TES5Outputtable;
use Ormin\OBSLexicalParser\TES5\AST\TES5ScriptHeader;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;


/**
 * Interface TES5Variable
 * Implementers declare they are a variable to be put in a scope.
 * @package Ormin\OBSLexicalParser\TES5\AST\Property
 */
interface TES5Variable extends TES5Outputtable {

    /**
 * @return mixed
 */
    public function getPropertyName();

    /**
     * @return \Ormin\OBSLexicalParser\TES5\Types\TES5Type
     */
    public function getPropertyType();

    /**
     * @param TES5Type $type
     * @return void
     */
    public function setPropertyType(TES5BasicType $type);

    /**
     * Get the reference EDID
     * @return string
     */
    public function getReferenceEdid();

    /**
     * Marks this variable to track a remote script - to be able to exchange inferencing information between multiple
     * scripts
     * @param TES5ScriptHeader $scriptHeader
     * @return mixed
     */
    public function trackRemoteScript(TES5ScriptHeader $scriptHeader);
}