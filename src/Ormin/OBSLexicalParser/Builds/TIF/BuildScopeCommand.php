<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds\TIF;

use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\TES5ScriptHeader;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class BuildScopeCommand implements \Ormin\OBSLexicalParser\Builds\BuildScopeCommand {

    /**
     * @var TES5NameTransformer
     */
    private $nameTransformer;

    public function initialize()
    {
        $this->nameTransformer = new TES5NameTransformer();
    }

    public function buildScope($sourcePath)
    {
        $scriptName = pathinfo($sourcePath, PATHINFO_FILENAME);

        //Create the header.
        $scriptHeader = new TES5ScriptHeader($this->nameTransformer->transform($scriptName,''), $scriptName, TES5BasicType::T_TOPICINFO(), '', true);

        $globalScope = new TES5GlobalScope($scriptHeader);

        return $globalScope;
    }


}