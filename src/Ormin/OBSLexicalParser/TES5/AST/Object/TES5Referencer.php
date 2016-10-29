<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Object;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5Variable;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;

/**
 * Interface TES5Referencer
 * @package Ormin\OBSLexicalParser\TES5\AST\Object
 *
 * Implementers declare that you can reference it.
 */
interface TES5Referencer extends TES5Value {


    /**
     * Returns the thing this references to LOCALLY. REMOTE references are considered null.
     * @return TES5Variable|null
     */
    public function getReferencesTo();

    /**
     * @return string
     */
    public function getName();


} 