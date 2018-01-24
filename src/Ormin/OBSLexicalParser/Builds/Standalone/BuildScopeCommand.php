<?php
/**
 * Created by PhpStorm.
 * Date: 10/31/16
 * Time: 2:16 PM
 */

namespace Ormin\OBSLexicalParser\Builds\Standalone;

use Ormin\OBSLexicalParser\TES4\AST\TES4Script;
use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\AST\Property\Collection\TES5GlobalVariables;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\TES5ScriptHeader;
use Ormin\OBSLexicalParser\TES5\Context\TypeMapper;
use Ormin\OBSLexicalParser\TES5\Factory\TES5PropertiesFactory;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;
use Ormin\OBSLexicalParser\Builds\Service\StandaloneParsingService;

class BuildScopeCommand implements \Ormin\OBSLexicalParser\Builds\BuildScopeCommand
{
    const SCRIPTS_PREFIX = "TES4";

    /**
     * @var ESMAnalyzer
     */
    private $esmAnalyzer;

    /**
     * @var TES5NameTransformer
     */
    private $nameTransformer;

    /**
     * @var TES5PropertiesFactory
     */
    private $propertiesFactory;

    /**
     * @var StandaloneParsingService
     */
    private $standaloneParsingService;

    public function __construct(StandaloneParsingService $standaloneParsing)
    {
        $this->standaloneParsingService = $standaloneParsing;
    }

    public function initialize()
    {
        $typeMapper = new TypeMapper();
        $this->esmAnalyzer = new ESMAnalyzer($typeMapper,'Oblivion.esm');
        $this->nameTransformer = new TES5NameTransformer();
        $this->propertiesFactory = new TES5PropertiesFactory();
    }

    /**
     * @param TES4Script $script
     * @return TES5ScriptHeader
     * @throws \Ormin\OBSLexicalParser\TES5\Exception\ConversionException
     */
    private function createHeader(TES4Script $script)
    {
        $edid = $script->getScriptHeader()->getScriptName();
        $scriptName = $this->nameTransformer->transform($edid, self::SCRIPTS_PREFIX);
        return new TES5ScriptHeader($scriptName, $edid, $this->esmAnalyzer->getScriptType($edid),self::SCRIPTS_PREFIX);
    }


    public function buildScope($scriptPath, TES5GlobalVariables $globalVariables)
    {
        $parsedScript = $this->standaloneParsingService->parseScript($scriptPath);
        $scriptHeader = $this->createHeader($parsedScript);
        $variableList = $parsedScript->getVariableDeclarationList();

        $globalScope = new TES5GlobalScope($scriptHeader);
        
        if ($variableList !== null) {
            $this->propertiesFactory->createProperties($variableList, $globalScope, $globalVariables);
        }

        return $globalScope;

    }

}