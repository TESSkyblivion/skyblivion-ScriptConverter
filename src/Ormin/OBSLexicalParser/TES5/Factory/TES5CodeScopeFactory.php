<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5LocalScope;

class TES5CodeScopeFactory {

    public function createCodeScope(TES5LocalScope $variableScope) {
        return new TES5CodeScope($variableScope);
    }

} 