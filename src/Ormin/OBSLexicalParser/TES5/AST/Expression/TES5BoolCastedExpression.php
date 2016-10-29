<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Expression;


use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;

class TES5BoolCastedExpression implements TES5Expression {

    /**
     * @var \Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value
     */
    private $value;

    public function __construct(TES5Value $value) {
        $this->value = $value;
    }

    public function getType() {
        return $this->value->getType();
    }

    public function output() {
        return [$this->value->output()];
    }

} 