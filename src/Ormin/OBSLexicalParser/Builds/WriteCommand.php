<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds;


use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\AST\TES5Target;

interface WriteCommand {

    /**
     * @param BuildTarget $target
     * @param BuildTracker $buildTracker
     */
    public function write(BuildTarget $target, BuildTracker $buildTracker);

} 