<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Object;


use Ormin\OBSLexicalParser\TES5\AST\Property\TES5Variable;
use Ormin\OBSLexicalParser\TES5\AST\TES5Outputtable;
use Ormin\OBSLexicalParser\TES5\Types\TES5InheritanceGraphAnalyzer;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

class TES5Reference implements TES5Referencer {

    /**
     * @var \Ormin\OBSLexicalParser\TES5\AST\Property\TES5Variable
     */
    private $referencesTo;

    /**
     * Used only for Float -> int cast
     * Hacky. Should be removed at some point.
     * @var TES5Type|null
     */
    private $manualCastTo = null;

    public function __construct(TES5Variable $referencesTo) {
        $this->referencesTo = $referencesTo;
    }

    public function output() {

        if($this->manualCastTo !== null) {
          return [$this->referencesTo->getPropertyName().' as '.$this->manualCastTo->value()];
        }

        return [$this->referencesTo->getPropertyName()];
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Property\TES5Variable
     */
    public function getReferencesTo()
    {
        return $this->referencesTo;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->referencesTo->getPropertyName();
    }

    public function getType() {
        return $this->referencesTo->getPropertyType();
    }

    public function setManualCastTo(TES5Type $type) {
        $this->manualCastTo = $type;
    }


} 