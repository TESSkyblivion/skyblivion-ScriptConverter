<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Value\Primitive;


use Ormin\OBSLexicalParser\TES5\Factory\TES5TypeFactory;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

class TES5Bool implements TES5Primitive {

    private $bool;

    function __construct($bool)
    {
        $this->bool = $bool;
    }

    public function output() {
        return [($this->bool) ? 'True' : 'False'];
    }

    public function getType() {
        return TES5BasicType::T_BOOL();
    }

    public function getValue() {
        return $this->bool;
    }


} 