<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST\Block;


class TES4BlockParameter {

    private $blockParameter;

    public function __construct($blockParameter) {
        $this->blockParameter = $blockParameter;
    }

    /**
     * @return mixed
     */
    public function getBlockParameter()
    {
        return $this->blockParameter;
    }



} 