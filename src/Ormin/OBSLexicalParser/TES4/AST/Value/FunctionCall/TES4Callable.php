<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall;

interface TES4Callable {

    /**
     * @return TES4Function
     */
    public function getFunction();

    /**
     * @return \Ormin\OBSLexicalParser\TES4\AST\Value\TES4ApiToken|null
     */
    public function getCalledOn();

} 