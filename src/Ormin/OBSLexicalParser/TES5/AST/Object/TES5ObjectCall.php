<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\AST\Object;


use Ormin\OBSLexicalParser\TES5\AST\Code\TES5CodeChunk;
use Ormin\OBSLexicalParser\TES5\Types\TES5InheritanceGraphAnalyzer;

class TES5ObjectCall implements TES5Referencer, TES5ObjectAccess, TES5CodeChunk {

    /**
     * @var TES5Referencer
     */
    private $called;

    /**
     * @var string
     */
    private $functionName;

    /**
     * @var TES5ObjectCallArguments
     */
    private $arguments;

    public function __construct(TES5Referencer $called, $functionName, TES5ObjectCallArguments $arguments = null) {
        $this->called = $called;
        $this->functionName = $functionName;
        $this->arguments = $arguments;
    }

    public function output() {

        $argumentsCode = "";
        if($this->arguments !== null) {
            $arguments = $this->arguments->output();
            $argumentsCode = $arguments[0];
        }

        $called = $this->called->output();
        $called = $called[0];
        $codeLines = [$called.'.'.$this->functionName.'('.$argumentsCode.')'];
        return $codeLines;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCallArguments
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return \Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer
     */
    public function getAccessedObject()
    {
        return $this->called;
    }

    /**
     * @return string
     */
    public function getFunctionName()
    {
        return $this->functionName;
    }

    public function getReferencesTo() {
        return null;
    }

    public function getName() {
        return "ObjectCall";
    }


    public function getType() {
          return TES5InheritanceGraphAnalyzer::findReturnTypeForObjectCall($this->called->getType(),$this->functionName);
    }


} 