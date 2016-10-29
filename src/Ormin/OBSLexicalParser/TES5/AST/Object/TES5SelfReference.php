<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Object;


use Ormin\OBSLexicalParser\TES5\AST\Property\TES5ScriptAsVariable;

class TES5SelfReference implements TES5Referencer {

    private $scriptAsVariable;

    public function __construct(TES5ScriptAsVariable $scriptAsVariable) {
        $this->scriptAsVariable = $scriptAsVariable;
    }

    public function output() {
        return ['self'];
    }

    public function getName() {
        return "self";
    }

    public function getReferencesTo() {
        return $this->scriptAsVariable;
    }

    public function getType() {
        return $this->scriptAsVariable->getPropertyType();
    }

} 