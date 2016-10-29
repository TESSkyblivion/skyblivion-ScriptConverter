<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Object;

use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5PlayerReference implements TES5Referencer {

    public function output() {
        return ['Game.getPlayer()']; //pretty ugly to do it here.
    }

    public function getName() {
        return "player";
    }

    public function getReferencesTo() {
        return null;
    }

    public function getType() {
        return TES5BasicType::T_ACTOR();
    }

}