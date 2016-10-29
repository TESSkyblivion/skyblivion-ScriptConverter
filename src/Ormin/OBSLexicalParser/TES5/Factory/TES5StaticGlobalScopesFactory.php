<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 27.12.15
 * Time: 21:14
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES5\AST\Property\TES5Property;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\TES5ScriptHeader;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class TES5StaticGlobalScopesFactory
{

    public function createGlobalScopes() {

        $globalScopes = [];
        $globalScope = new TES5GlobalScope(new TES5ScriptHeader("TES4TimerHelper", "TES4TimerHelper", TES5BasicType::T_QUEST(), ""));
        $globalScopes[] = $globalScope;
        $globalScope = new TES5GlobalScope(new TES5ScriptHeader("TES4Container", "TES4Container", TES5BasicType::T_QUEST(), ""));
        $globalScope->add(
            new TES5Property("isInJail", TES5BasicType::T_BOOL(), "isInJail")
        );
        $globalScope->add(
            new TES5Property("isMurderer", TES5BasicType::T_BOOL(), "isMurderer")
        );
        $globalScopes[] = $globalScope;


        return $globalScopes;

    }

}