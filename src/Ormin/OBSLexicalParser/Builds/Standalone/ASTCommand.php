<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds\Standalone;

class ASTCommand implements \Ormin\OBSLexicalParser\Builds\ASTCommand
{

    /**
     * @var \Ormin\OBSLexicalParser\TES4\Parser\SyntaxErrorCleanParser
     */
    private $parser;

    public function initialize()
    {
        $parser = new \Ormin\OBSLexicalParser\TES4\Parser\SyntaxErrorCleanParser(new \Ormin\OBSLexicalParser\TES4\Parser\TES4OBScriptGrammar());
        $this->parser = $parser;
    }


    public function getAST($sourcePath)
    {

        $lexer = new \Ormin\OBSLexicalParser\TES4\Lexer\ScriptLexer();
        $tokens = $lexer->lex(file_get_contents($sourcePath));
        $AST = $this->parser->parse($tokens);

        return $AST;

    }


} 