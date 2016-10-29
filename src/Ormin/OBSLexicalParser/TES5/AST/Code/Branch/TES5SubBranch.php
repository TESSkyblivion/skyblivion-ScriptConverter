<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Code\Branch;


use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope;
use Ormin\OBSLexicalParser\TES5\AST\Value\TES5Value;

class TES5SubBranch {

    private $expression;

    private $codeScope;

    public function __construct(TES5Value $expression, TES5CodeScope $codeScope) {
        $this->expression = $expression;
        $this->codeScope = $codeScope;

    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope
     */
    public function getCodeScope()
    {
        return $this->codeScope;
    }

    /**
     * @return TES5Value
     */
    public function getExpression()
    {
        return $this->expression;
    }



    /**
     * @param \Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeScope $codeScope
     */
    public function setCodeScope(TES5CodeScope $codeScope)
    {
        $this->codeScope = $codeScope;
    }

    /**
     * @param $expression
     */
    public function setExpression(TES5Value $expression)
    {
        $this->expression = $expression;
    }



} 