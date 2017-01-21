<?php
/**
 * Created by PhpStorm.
 * Date: 10/31/16
 * Time: 3:10 PM
 */

namespace Ormin\OBSLexicalParser\Builds\Service;
use Ormin\OBSLexicalParser\TES4\AST\TES4Script;
use Ormin\OBSLexicalParser\TES4\Parser\SyntaxErrorCleanParser;


/**
 * Class StandaloneParsingService
 *
 * This class is meant only to be a cache layer for parsing a script
 *
 * It was created because both BuildScopeCommand and TranspileCommand need the parsed TES4Script and we didn't
 * want to parse twice.
 *
 * @package Ormin\OBSLexicalParser\Builds\Service
 */
class StandaloneParsingService
{

    /**
     * @var TES4Script[]
     */
    private $parsingCache = [];

    private $parser;

    /**
     * Forcing implementation on purpose.
     * StandaloneParsingService constructor.
     * @param SyntaxErrorCleanParser $parser
     */
    public function __construct(SyntaxErrorCleanParser $parser)
    {
        $this->parser = $parser;
    }


    /**
     * @param $scriptPath
     * @return TES4Script
     */
    public function parseScript($scriptPath)
    {

        if(!isset($this->parsingCache[$scriptPath])) {
            $lexer = new \Ormin\OBSLexicalParser\TES4\Lexer\ScriptLexer();
            $tokens = $lexer->lex(file_get_contents($scriptPath));
            $this->parsingCache[$scriptPath] = $this->parser->parse($tokens);
        }

        return $this->parsingCache[$scriptPath];

    }

}