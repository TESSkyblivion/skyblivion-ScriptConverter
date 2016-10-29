<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Code;


class TES4Return implements TES4CodeChunk {

    public function filter(\Closure $c) {
        return ($c($this)) ? [$this] : [];
    }

} 