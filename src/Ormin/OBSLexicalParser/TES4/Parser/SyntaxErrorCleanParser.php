<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\Parser;


use Dissect\Lexer\TokenStream\TokenStream;
use Dissect\Parser\Exception\UnexpectedTokenException;
use Dissect\Parser\LALR1\Parser;
use Dissect\Lexer\CommonToken;
use Dissect\Lexer\TokenStream\ArrayTokenStream;

class SyntaxErrorCleanParser extends Parser
{

    /**
     * {@inheritDoc}
     */
    public function parse(TokenStream $stream)
    {
        try {
            $oldAST = parent::parse($stream);

            return $oldAST;
        }
        catch (UnexpectedTokenException $e) {
            $token = $e->getToken();
            $fixed = false;
            if ($token->getValue() == "endif") {
                $iterator = $stream->getIterator();

                $nesting = 0;
                /**
                 * @var CommonToken $token
                 */

                $tokens = [];


                foreach ($iterator as $token) {
                    if ($token->getType() == "BranchStartToken") {
                        ++$nesting;
                        $tokens[] = $token;
                    } else {
                        if ($token->getType() == "BranchEndToken") {
                            $nesting = $nesting - 1;
                            if ($nesting > -1) {
                                $tokens[] = $token;
                            } else {
                                $fixed   = true;
                                $nesting = 0; //Clear up the token and nesting will be again 0
                            }

                        } else {
                            $tokens[] = $token;
                        }
                    }
                }

                if (!$fixed) {
                    throw $e;
                }

                $newTokenStream = new ArrayTokenStream($tokens);

                $newAST = $this->parse($newTokenStream);

                return $newAST;

            } else {
                throw $e;
            }


        }

    }
} 