<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Input;


use Ormin\OBSLexicalParser\TES4\AST\VariableDeclaration\TES4VariableDeclaration;
use Ormin\OBSLexicalParser\TES4\AST\VariableDeclaration\TES4VariableDeclarationList;
use Ormin\OBSLexicalParser\TES4\Types\TES4Type;

class FragmentsReferencesBuilder {

    public function buildVariableDeclarationList($path) {

        $list = new TES4VariableDeclarationList();

        if(!file_exists($path)) {
            return $list;
        }

        $references = file($path);
        foreach($references as $reference) {

            $reference = trim($reference);

            if(empty($reference)) {
                continue;
            }

            $list->add(
                new TES4VariableDeclaration(trim($reference), TES4Type::T_REF())
            );
        }

        return $list;


    }

} 