<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\AST;


class TES4ScriptHeader {

    /**
     * @var string
     */
    private $scriptName;

    public function __construct($scriptName)
    {
        $this->scriptName = $scriptName;
    }


    /**
     * @return string
     */
    public function getScriptName()
    {
        return $this->scriptName;
    }



} 