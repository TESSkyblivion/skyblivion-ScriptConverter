<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Block;


use Ormin\OBSLexicalParser\TES5\AST\TES5Outputtable;

interface TES5CodeBlock extends TES5Outputtable{

    public function getCodeScope();

} 