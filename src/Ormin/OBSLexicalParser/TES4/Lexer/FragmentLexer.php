<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\Lexer;

use Dissect\Lexer\StatefulLexer;

class FragmentLexer extends OBScriptLexer
{

    public function __construct()
    {
        $this->buildObscriptLexer();
        $this->start('BlockScope');
    }

}