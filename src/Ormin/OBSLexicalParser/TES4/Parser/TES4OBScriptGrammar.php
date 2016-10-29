<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\Parser;


use Dissect\Lexer\CommonToken;
use Dissect\Parser\Grammar;
use Ormin\OBSLexicalParser\TES4\AST\Expression\TES4Expression;
use Ormin\OBSLexicalParser\TES4\AST\TES4Script;
use Ormin\OBSLexicalParser\TES4\AST\TES4ScriptHeader;
use Ormin\OBSLexicalParser\TES4\AST\Block\TES4BlockList;
use Ormin\OBSLexicalParser\TES4\AST\Block\TES4BlockParameter;
use Ormin\OBSLexicalParser\TES4\AST\Block\TES4BlockParameterList;
use Ormin\OBSLexicalParser\TES4\AST\Block\TES4CodeBlock;
use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunk;
use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunks;
use Ormin\OBSLexicalParser\TES4\AST\Code\TES4Return;
use Ormin\OBSLexicalParser\TES4\AST\Code\TES4VariableAssignation;
use Ormin\OBSLexicalParser\TES4\AST\Code\Branch\TES4Branch;
use Ormin\OBSLexicalParser\TES4\AST\Code\Branch\TES4ElseSubBranch;
use Ormin\OBSLexicalParser\TES4\AST\Code\Branch\TES4SubBranch;
use Ormin\OBSLexicalParser\TES4\AST\Code\Branch\TES4SubBranchList;
use Ormin\OBSLexicalParser\TES4\AST\Expression\Operators\TES4BinaryExpressionOperator;
use Ormin\OBSLexicalParser\TES4\AST\Expression\TES4ArithmeticExpression;
use Ormin\OBSLexicalParser\TES4\AST\Expression\TES4BinaryExpression;
use Ormin\OBSLexicalParser\TES4\AST\Expression\TES4LogicalExpression;
use Ormin\OBSLexicalParser\TES4\AST\Expression\Operators\TES4ArithmeticExpressionOperator;
use Ormin\OBSLexicalParser\TES4\AST\Expression\Operators\TES4LogicalExpressionOperator;
use Ormin\OBSLexicalParser\TES4\AST\Expression\TES4TrueBooleanExpression;
use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4Function;
use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4FunctionArguments;
use Ormin\OBSLexicalParser\TES4\AST\Value\FunctionCall\TES4FunctionCall;
use Ormin\OBSLexicalParser\TES4\AST\Value\Primitive\TES4Float;
use Ormin\OBSLexicalParser\TES4\AST\Value\Primitive\TES4Integer;
use Ormin\OBSLexicalParser\TES4\AST\Value\Primitive\TES4String;
use Ormin\OBSLexicalParser\TES4\AST\Value\TES4ApiToken;
use Ormin\OBSLexicalParser\TES4\AST\Value\ObjectAccess\TES4ObjectCall;
use Ormin\OBSLexicalParser\TES4\AST\Value\ObjectAccess\TES4ObjectProperty;
use Ormin\OBSLexicalParser\TES4\AST\Value\TES4Value;
use Ormin\OBSLexicalParser\TES4\AST\VariableDeclaration\TES4VariableDeclaration;
use Ormin\OBSLexicalParser\TES4\AST\VariableDeclaration\TES4VariableDeclarationList;
use Ormin\OBSLexicalParser\TES4\Types\TES4Type;

class TES4OBScriptGrammar extends TES4ObscriptCodeGrammar {

    public function __construct() {

        $this('Script')
            ->is('ScriptHeader','Block+')
            ->call(function ($header, $blockList) {
                return new TES4Script($header, null, $blockList);
            })
            ->is('ScriptHeader','VariableDeclaration+')
            ->call(function ($header, $variableList) {
                return new TES4Script($header, $variableList, null);
            })
            ->is('ScriptHeader','VariableDeclaration+','Block+')
            ->call(function ($header, $variableList, $blockList) {
                return new TES4Script($header, $variableList, $blockList);
            });

        $this('ScriptHeader')
            ->is('ScriptHeaderToken','ScriptName')
            ->call(function ($headerToken, CommonToken $scriptName) {
                    return new TES4ScriptHeader($scriptName->getValue());
                });

        $this('VariableDeclaration+')
            ->is('VariableDeclaration+','VariableDeclaration')
            ->call(function (TES4VariableDeclarationList $list, TES4VariableDeclaration $variableDeclaration) {

                $list->add($variableDeclaration);
                return $list;

            })
            ->is('VariableDeclaration')
            ->call(function(TES4VariableDeclaration $variableDeclaration) {
                $list = new TES4VariableDeclarationList();
                $list->add($variableDeclaration);
                return $list;
            });


        $this('VariableDeclaration')
            ->is('VariableDeclarationType','VariableName')
            ->call(function(CommonToken $variableDeclarationType,CommonToken $variableName) {
                    return new TES4VariableDeclaration($variableName->getValue(),TES4Type::memberByValue(strtolower($variableDeclarationType->getValue())));
                });

        $this('Block+')
            ->is('Block+','Block')
            ->call(function (TES4BlockList $list, TES4CodeBlock $blockDeclaration) {

                $list->add($blockDeclaration);
                return $list;
            })
            ->is('Block')
            ->call(function(TES4CodeBlock $blockDeclaration) {
                $list = new TES4BlockList();
                $list->add($blockDeclaration);
                return $list;
            });

        $this('Block')
            ->is('BlockStart','BlockType','BlockParameter+','Code+','BlockEnd')
            ->call(function($blockStart, CommonToken $blockType, TES4BlockParameterList $blockParameters, TES4CodeChunks $codeChunks, $blockEnd) {
                return new TES4CodeBlock($blockType->getValue(),$blockParameters,$codeChunks);

            })
            ->is('BlockStart','BlockType','BlockParameter+','BlockEnd')
            ->call(function($blockStart,CommonToken $blockType, TES4BlockParameterList $blockParameters, $blockEnd) {
                return new TES4CodeBlock($blockType->getValue(),$blockParameters,null);

            })  //rare empty block
            ->is('BlockStart','BlockType','Code+','BlockEnd')
            ->call(function($blockStart, CommonToken $blockType, TES4CodeChunks $codeChunks, $blockEnd) {
                return new TES4CodeBlock($blockType->getValue(),null, $codeChunks);

            })
            ->is('BlockStart','BlockType','BlockEnd')
            ->call(function($blockStart, CommonToken $blockType, $blockEnd) {
                return new TES4CodeBlock($blockType->getValue(),null, null);

            }); //rare empty block

        $this('BlockParameter+')
            ->is('BlockParameter+', 'BlockParameter')
            ->call(function (TES4BlockParameterList $list, TES4BlockParameter $blockParameter) {
                $list->add($blockParameter);
                return $list;
            })
            ->is('BlockParameter')
            ->call(function (TES4BlockParameter $blockParameter) {
                $block = new TES4BlockParameterList();
                $block->add($blockParameter);
                return $block;
            });

        $this('BlockParameter')
            ->is('BlockParameterToken')
            ->call(function (CommonToken $token) {
            return new TES4BlockParameter($token->getValue());
        });


        $this->createObscriptCodeParsingTree();

        $this->start('Script');


    }



} 