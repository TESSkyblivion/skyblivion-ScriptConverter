<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Code;


interface TES4CodeChunk {

    public function filter(\Closure $c);

} 