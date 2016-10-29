<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 23.12.15
 * Time: 21:11
 */

namespace Ormin\OBSLexicalParser\TES5\AST;


class TES5ScriptCollection implements \IteratorAggregate
{
    /**
     * @var TES5Target[]
     */
    private $collection = [];

    /**
     * @param TES5Script $script
     * @param string $outputPath
     */
    public function add(TES5Script $script, $outputPath) {
        $this->collection[] = new TES5Target($script, $outputPath);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->collection);
    }

}