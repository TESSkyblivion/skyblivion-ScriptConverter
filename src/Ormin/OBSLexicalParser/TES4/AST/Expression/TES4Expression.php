<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Expression;


use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value;
use Eloquent\Enumeration\AbstractEnumeration;

interface TES4Expression extends TES4Value
{


    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value
     */
    public function getLeftValue();

    /**
     * @return AbstractEnumeration
     */
    public function getOperator();

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value
     */
    public function getRightValue();
}