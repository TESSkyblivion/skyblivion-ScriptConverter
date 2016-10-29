<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 23.12.15
 * Time: 21:11
 */

namespace Ormin\OBSLexicalParser\TES4\AST;


class TES4ScriptCollection implements \IteratorAggregate
{
    /**
     * @var TES4Target[]
     */
    private $collection = [];

    /**
     * @param TES4Script $script
     * @param string $outputPath
     */
    public function add(TES4Script $script, $outputPath) {
        $this->collection[] = new TES4Target($script, $outputPath);
    }

    public function getIterator() {
        return new \ArrayIterator($this->collection);
    }

}