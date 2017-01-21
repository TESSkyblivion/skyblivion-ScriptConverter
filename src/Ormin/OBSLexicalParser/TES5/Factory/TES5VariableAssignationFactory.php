<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunk;
use Ormin\OBSLexicalParser\TES4\AST\Code\TES4VariableAssignation;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CastAssignation;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Code\TES5VariableAssignation;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Reference;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5Variable;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;

class TES5VariableAssignationFactory  {

    private $referenceFactory;

    public function __construct(TES5ReferenceFactory $referenceFactory) {
        $this->referenceFactory = $referenceFactory;
    }

    public function createAssignation(TES5Referencer $target, TES5Value $value) {
        return new TES5VariableAssignation($target, $value);
    }


} 