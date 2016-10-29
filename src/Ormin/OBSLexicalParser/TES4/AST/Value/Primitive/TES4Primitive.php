<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Value\Primitive;


use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value;
use Ormin\OBSLexicalParser\TES4\Types\TES4Type;
interface TES4Primitive extends TES4Value{

    /**
     * @return TES4Type
     */
    public function getType();

} 