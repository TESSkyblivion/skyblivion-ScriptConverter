<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Object;

use Ormin\OBSLexicalParser\TES5\AST\TES5Outputtable;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;

class TES5ObjectCallArguments implements TES5Outputtable {

    /**
     * @var TES5Value[]
     */
    private $arguments = [];

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value[]
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    public function output() {
        $outputs = [];
        foreach($this->arguments as $argument) {
            $subOutput = $argument->output();;
            $subOutput = $subOutput[0];
            $outputs[] = $subOutput;
        }

        return [implode(', ',$outputs)];
    }

    public function add(TES5Value $value) {
        $this->arguments[] = $value;
    }

} 