<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Value\ObjectAccess;

use Ormin\OBSLexicalParser\TES4\AST\Value\TES4ApiToken;
use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Reference;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

class TES4ObjectProperty implements TES4ObjectAccess, TES4Reference {

    /**
     * @var TES4ApiToken
     */
    private $parentReference;

    /**
     * @var TES4ApiToken
     */
    private $accessField;

    public function __construct(TES4ApiToken $parentReference, TES4ApiToken $accessField) {
        $this->parentReference = $parentReference;
        $this->accessField = $accessField;
    }

    public function getData() {
        return $this->parentReference->getData().'.'.$this->accessField->getData();
    }

    public function hasFixedValue() {
        return false;
    }


    public function filter(\Closure $c)
    {
        return $c($this) ? [$this] : [];
    }
} 