<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Code;


class TES5Return implements TES5CodeChunk {

    public function output() {
        return ['Return'];
    }

} 