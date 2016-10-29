<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds;

use Ormin\OBSLexicalParser\TES4\AST\TES4Script;

interface ASTCommand {

    public function initialize();

    /**
     * @param $sourcePath
     * @return TES4Script
     */
    public function getAST($sourcePath);

}