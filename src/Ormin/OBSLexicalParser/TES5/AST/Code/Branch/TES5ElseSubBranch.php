<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Code\Branch;


use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;

class TES5ElseSubBranch {

    private $codeScope;

    public function __construct(TES5CodeScope $codeScope = null) {
        $this->codeScope = $codeScope;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope
     */
    public function getCodeScope()
    {
        return $this->codeScope;
    }



} 