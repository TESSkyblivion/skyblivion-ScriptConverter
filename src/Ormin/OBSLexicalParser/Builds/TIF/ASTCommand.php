<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds\TIF;

class ASTCommand implements \Ormin\OBSLexicalParser\Builds\ASTCommand
{

    /**
     * @var \Ormin\OBSLexicalParser\TES4\Parser\SyntaxErrorCleanParser
     */
    private $parser;

    public function initialize()
    {
        $parser = new \Ormin\OBSLexicalParser\TES4\Parser\SyntaxErrorCleanParser(new \Ormin\OBSLexicalParser\TES4\Parser\TES4ObscriptCodeGrammar());
        $this->parser = $parser;
    }


    public function getAST($sourcePath)
    {

        $lexer = new \Ormin\OBSLexicalParser\TES4\Lexer\FragmentLexer();
        $tokens = $lexer->lex(file_get_contents($sourcePath));
        $AST = $this->parser->parse($tokens);

        return $AST;

    }


}