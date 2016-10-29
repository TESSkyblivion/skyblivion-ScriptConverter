<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Object;


interface TES5ObjectAccess {

    /**
     * @return TES5Referencer
     */
    public function getAccessedObject();

} 