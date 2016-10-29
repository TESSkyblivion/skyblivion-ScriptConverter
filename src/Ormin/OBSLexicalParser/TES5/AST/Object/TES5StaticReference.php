<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Object;


use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Factory\TES5TypeFactory;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

class TES5StaticReference implements TES5Referencer {

    private $name;

    function __construct($name)
    {
        $this->name = $name;
    }

    public function output() {
        return [$this->name];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function getType() {
        return TES5TypeFactory::memberByValue($this->getName());
    }

    public function getReferencesTo() {
        return null;
    }

} 