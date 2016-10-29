<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\Lexer;

use Dissect\Lexer\SimpleLexer;

class ArithLexer extends SimpleLexer
{
    public function __construct()
    {
        $this->regex('INT', '/^[1-9][0-9]*/');
        $this->token('(');
        $this->token(')');
        $this->token('+');
        $this->token('**');
        $this->token('*');
        $this->regex('WSP', "/^[ \r\n\t]+/");
        $this->skip('WSP');
    }
}
