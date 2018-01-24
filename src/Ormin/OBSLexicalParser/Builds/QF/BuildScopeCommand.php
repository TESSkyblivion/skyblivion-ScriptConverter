<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds\QF;

use Ormin\OBSLexicalParser\Input\FragmentsReferencesBuilder;
use Ormin\OBSLexicalParser\TES5\AST\Property\Collection\TES5GlobalVariables;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\TES5ScriptHeader;
use Ormin\OBSLexicalParser\TES5\Factory\TES5PropertiesFactory;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;

class BuildScopeCommand implements \Ormin\OBSLexicalParser\Builds\BuildScopeCommand {

    /**
     * @var TES5NameTransformer
     */
    private $nameTransformer;

    /**
     * @var FragmentsReferencesBuilder
     */
    private $fragmentsReferencesBuilder;

    /**
     * @var TES5PropertiesFactory
     */
    private $propertiesFactory;

    public function initialize()
    {
        $this->nameTransformer = new TES5NameTransformer();
        $this->fragmentsReferencesBuilder = new FragmentsReferencesBuilder();
        $this->propertiesFactory = new TES5PropertiesFactory();
    }

    public function buildScope($sourcePath, TES5GlobalVariables $globalVariables)
    {
        $scriptName = pathinfo($sourcePath, PATHINFO_FILENAME);
        $referencesPath = pathinfo($sourcePath, PATHINFO_DIRNAME). "/" .$scriptName.".references";

        //Create the header.
        $scriptHeader = new TES5ScriptHeader($this->nameTransformer->transform($scriptName,''), $scriptName, TES5BasicType::T_QUEST(), '', true);
        $globalScope = new TES5GlobalScope($scriptHeader);
        $variableList = $this->fragmentsReferencesBuilder->buildVariableDeclarationList($referencesPath);
        if ($variableList !== null) {
            $this->propertiesFactory->createProperties($variableList, $globalScope, $globalVariables);
        }


        return $globalScope;
    }


}