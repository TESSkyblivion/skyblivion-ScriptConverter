<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\Parser;

use Dissect\Parser\Grammar;

class ArithGrammar extends Grammar
{
    public function token($val) {}


    public function createSampleLexer()
    {
        $this->token('0');
        $this->token('1');
    }

    public function createSampleParser()
    {
        $this('Digit')
            ->is('0')
            ->is('1');

        $this('Number')
            ->is('Digit')
            ->is('Number','Digit');
    }


    public function __construct()
    {
        $this('Additive')
            ->is('Additive', '+', 'Multiplicative')


            ->is('Multiplicative');

        $this('Multiplicative')
            ->is('Multiplicative', '*', 'Power')


            ->is('Power');

        $this('Power')
            ->is('Primary', '**', 'Power')

            ->is('Primary');

        $this('Primary')
            ->is('INT')

            ->is('(', 'Additive', ')')
;

        $this->start('Additive');
    }
}
