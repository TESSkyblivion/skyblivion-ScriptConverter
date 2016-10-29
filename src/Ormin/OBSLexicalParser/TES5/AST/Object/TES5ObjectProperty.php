<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Object;


use Ormin\OBSLexicalParser\TES5\AST\Property\TES5Property;

class TES5ObjectProperty implements TES5Referencer, TES5ObjectAccess {

    /**
     * @var TES5Referencer
     */
    private $objectReference;

    /**
     * @var TES5Property
     */
    private $property;


    public function __construct(TES5Referencer $objectReference, TES5Property $property) {
        $this->objectReference = $objectReference;
        $this->property = $property;
    }

    public function output() {
        $referenceOutput = $this->objectReference->output();
        $referenceOutput = $referenceOutput[0];
        return [$referenceOutput.'.'.$this->property->getPropertyName()];
    }

    public function getType() {
        return $this->property->getPropertyType();
    }

    public function getAccessedObject() {
        return $this->objectReference;
    }

    public function getReferencesTo() {
        return $this->property;
    }

    public function getName() {
        return $this->property->getPropertyName();
    }


} 