<?php
/**
 * Created by PhpStorm.
 * Date: 10/31/16
 * Time: 3:10 PM
 */

namespace Ormin\OBSLexicalParser\Builds\Service;
use Ormin\OBSLexicalParser\TES4\AST\Block\TES4CodeBlock;
use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunks;
use Ormin\OBSLexicalParser\TES4\Parser\SyntaxErrorCleanParser;


/**
 * Class FragmentsParsingService
 *
 * @package Ormin\OBSLexicalParser\Builds\Service
 */
class FragmentsParsingService
{

    /**
     * @var TES4CodeChunks[]
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
     * @return TES4CodeChunks
     */
    public function parseScript($scriptPath)
    {

        if(!isset($this->parsingCache[$scriptPath])) {
            $lexer = new \Ormin\OBSLexicalParser\TES4\Lexer\FragmentLexer();
            $tokens = $lexer->lex(file_get_contents($scriptPath));
            $this->parsingCache[$scriptPath] = $this->parser->parse($tokens);
        }

        return $this->parsingCache[$scriptPath];

    }

}