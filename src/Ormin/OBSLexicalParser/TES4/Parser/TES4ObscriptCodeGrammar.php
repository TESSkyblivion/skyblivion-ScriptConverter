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


class TES4ObscriptCodeGrammar extends Grammar  {

    public function __construct() {
        $this->createObscriptCodeParsingTree();
        $this->start("Code+");
    }

    protected function createObscriptCodeParsingTree() {

        $this('Code+')
            ->is('Code+','Code')
            ->call(function (TES4CodeChunks $list, TES4CodeChunk $codeDeclaration) {
                $list->add($codeDeclaration);
                return $list;
            })
            ->is('Code')
            ->call(function(TES4CodeChunk $codeDeclaration) {
                $list = new TES4CodeChunks();
                $list->add($codeDeclaration);
                return $list;
            });

        $this('Code')
            ->is('Branch')
            ->is('SetValue','NWL')
            ->is('Function','NWL')
            ->is('ObjectCall','NWL')
            ->is('LocalVariableDeclaration+')
            ->is('Return'); #todo - THIS should be fixed on lexer level, right now it ignores NWL after the return

        $this('LocalVariableDeclaration+')
            ->is('LocalVariableDeclaration+','LocalVariableDeclaration')
            ->call(function (TES4VariableDeclarationList $list, TES4VariableDeclaration $variableDeclaration) {

                $list->add($variableDeclaration);
                return $list;

            })
            ->is('LocalVariableDeclaration')
            ->call(function(TES4VariableDeclaration $variableDeclaration) {
                $list = new TES4VariableDeclarationList();
                $list->add($variableDeclaration);
                return $list;
            });


        $this('LocalVariableDeclaration')
            ->is('LocalVariableDeclarationType','VariableName')
            ->call(function(CommonToken $variableDeclarationType,CommonToken $variableName) {
                return new TES4VariableDeclaration($variableName->getValue(),TES4Type::memberByValue(strtolower($variableDeclarationType->getValue())));
            });

        $this('Branch')
            ->is('BranchStart','BranchEndToken') //If a == 2 { doSomeCode(); endIf
            ->call(function(TES4SubBranch $branchStart,$end) {
                return new TES4Branch(

                    $branchStart,
                    null,
                    null

                );

            })
            ->is('BranchStart','BranchSubBranch+','BranchEndToken') //If a == 2 { doSomeCode(); endIf
            ->call(function(TES4SubBranch $branchStart,TES4SubBranchList $subbranches,$end) {

                return new TES4Branch(

                    $branchStart,
                    $subbranches,
                    null

                );

            })
            ->is('BranchStart','BranchElse','BranchEndToken') //If a == 2 { doSomeCode(); endIf
            ->call(function(TES4SubBranch $branchStart,TES4ElseSubBranch $branchElse,$end) {
                return new TES4Branch(

                    $branchStart,
                    null,
                    $branchElse
                );

            })
            ->is('BranchStart','BranchSubBranch+','BranchElse','BranchEndToken')
            ->call(function(TES4SubBranch $branchStart,TES4SubBranchList $subbranches,TES4ElseSubBranch $branchElse,$end) {
                return new TES4Branch(
                    $branchStart,
                    $subbranches,
                    $branchElse
                );

            });

        $this('BranchElse')
            ->is('BranchElseToken','Code+')
            ->call(function($branchElseToken,TES4CodeChunks $code) {
                return new TES4ElseSubBranch($code);
            })
            ->is('BranchElseToken')
            ->call(function($branchElseToken) {
                return new TES4ElseSubBranch(null);
            });

        $this('BranchStart')
            ->is('BranchStartToken','Value','NWL','Code+')
            ->call(function($branchStart,TES4Value $expression, $newLine, TES4CodeChunks $code) {
                return new TES4SubBranch($expression,$code);
            })
            ->is('BranchStartToken','Value','NWL')
            ->call(function($branchStart,TES4Value $expression, $newLine) {
                return new TES4SubBranch($expression,null);
            });

        $this('BranchSubBranch+')
            ->is('BranchSubBranch+','BranchSubBranch')
            ->call(function (TES4SubBranchList $list, TES4SubBranch $branchSubBranchDeclaration) {
                $list->add($branchSubBranchDeclaration);
                return $list;
            })
            ->is('BranchSubBranch')
            ->call(function(TES4SubBranch $branchSubBranchDeclaration) {
                $list = new TES4SubBranchList();
                $list->add($branchSubBranchDeclaration);
                return $list;
            });

        $this('BranchSubBranch')
            ->is('BranchElseifToken','Value','NWL','Code+')
            ->call(function($branchElseif,TES4Value $expression,$nwl,TES4CodeChunks $codeChunks) {

                return new TES4SubBranch($expression,$codeChunks);

            })
            ->is('BranchElseifToken','Value','NWL')
            ->call(function($branchElseif,TES4Value $expression,$nwl) {

                return new TES4SubBranch($expression,null);

            });


        $this('MathOperator')
            ->is('==')->call(function(CommonToken $operator) { return TES4ArithmeticExpressionOperator::memberByValue($operator->getValue()); })
            ->is('!=')->call(function(CommonToken $operator) { return TES4ArithmeticExpressionOperator::memberByValue($operator->getValue()); })
            ->is('>')->call(function(CommonToken $operator) { return TES4ArithmeticExpressionOperator::memberByValue($operator->getValue()); })
            ->is('<')->call(function(CommonToken $operator) { return TES4ArithmeticExpressionOperator::memberByValue($operator->getValue()); })
            ->is('<=')->call(function(CommonToken $operator) { return TES4ArithmeticExpressionOperator::memberByValue($operator->getValue()); })
            ->is('>=')->call(function(CommonToken $operator) { return TES4ArithmeticExpressionOperator::memberByValue($operator->getValue()); });

        $this('LogicalOperator')->
            is('||')->call(function(CommonToken $operator) { return TES4LogicalExpressionOperator::memberByValue($operator->getValue()); })->
            is('&&')->call(function(CommonToken $operator) { return TES4LogicalExpressionOperator::memberByValue($operator->getValue()); });

        $this('Value')
            ->is('Value','LogicalOperator', 'NotLogicalValue')
            ->call(function(TES4Value $left,TES4LogicalExpressionOperator $operator,TES4Value $right) {
                return new TES4LogicalExpression($left, $operator, $right);
            })
            ->is('NotLogicalValue');


        $this('NotLogicalValue')
            ->is('NotLogicalValue','MathOperator','NotLogicalAndBinaryValue')
            ->call(function(TES4Value $left,TES4ArithmeticExpressionOperator $operator,TES4Value $right) {
                return new TES4ArithmeticExpression($left, $operator, $right);
            })
            ->is('NotLogicalAndBinaryValue');

        $this('NotLogicalAndBinaryValue')
            ->is('NotLogicalAndBinaryValue','BinaryOperator','NonExpressionValue')
            ->call(function(TES4Value $left,TES4BinaryExpressionOperator $operator,TES4Value $right) {
                return new TES4BinaryExpression($left, $operator, $right);
            })
            ->is('NonExpressionValue');

        $this('NonExpressionValue')
            ->is('ObjectAccess')
            ->is('Function')
            ->is('APIToken')
            ->is('Primitive');

        $this('BinaryOperator')
            ->is('+')->call(function(CommonToken $operator) { return TES4BinaryExpressionOperator::memberByValue($operator->getValue()); })
            ->is('-')->call(function(CommonToken $operator) { return TES4BinaryExpressionOperator::memberByValue($operator->getValue()); })
            ->is('*')->call(function(CommonToken $operator) { return TES4BinaryExpressionOperator::memberByValue($operator->getValue()); })
            ->is('/')->call(function(CommonToken $operator) { return TES4BinaryExpressionOperator::memberByValue($operator->getValue()); });

        $this('ObjectAccess')
            ->is('ObjectCall')
            ->is('ObjectProperty');

        $this('ObjectCall')
            ->is('APIToken','TokenDelimiter','Function')
            ->call(function (TES4ApiToken $apiToken, $delimiter, TES4Function $function) {
                return new TES4ObjectCall($apiToken,$function);
            });

        $this('ObjectProperty')
            ->is('APIToken','TokenDelimiter','APIToken')
            ->call(function (TES4ApiToken $apiToken, $delimiter, TES4ApiToken $nextApiToken) {
                return new TES4ObjectProperty($apiToken,$nextApiToken);
            });

        $this('SetValue')
            ->is('SetInitialization','ObjectProperty','Value')
            ->call(function($setInitialization,TES4ObjectProperty $objectProperty,TES4Value $expression) {
                return new TES4VariableAssignation($objectProperty,$expression);
            })
            ->is('SetInitialization','APIToken','Value')
            ->call(function($setInitialization,TES4ApiToken $apiToken, TES4Value $expression) {
                return new TES4VariableAssignation($apiToken,$expression);
            });

        $this('Function')
            ->is('FunctionCall','FunctionArguments')
            ->call(function ($functionCall, $functionArguments) {

                return new TES4Function($functionCall, $functionArguments);
            })
            ->is('FunctionCall')
            ->call(function ($functionCall) {
                return new TES4Function($functionCall, new TES4FunctionArguments());
            });

        $this('FunctionCall')
            ->is('FunctionCallToken')
            ->call(function (CommonToken $functionCall) {
                return new TES4FunctionCall($functionCall->getValue());
            });

        $this('APIToken')
            ->is('ReferenceToken')
            ->call(function (CommonToken $token) {
                return new TES4ApiToken($token->getValue());
            });

        $this('FunctionArguments')
            ->is('FunctionArguments','FunctionParameter')
            ->call(function (TES4FunctionArguments $list, TES4Value $value) {
                $list->add($value);
                return $list;
            })
            ->is('FunctionParameter')
            ->call(function(TES4Value $value) {
                $list = new TES4FunctionArguments();
                $list->add($value);
                return $list;
            });

        $this('FunctionParameter')
            ->is('ObjectAccess')
            ->is('Function')
            ->is('APIToken')
            ->is('Primitive');

        $this('Primitive')
            ->is('Float')
            ->call(function(CommonToken $float) {

                $floatValue = $float->getValue();
                if(substr((string)$floatValue,0,1) == ".") {
                    $floatValue = "0".$floatValue;
                }

                return new TES4Float((float)$floatValue);
            })
            ->is('Integer')
            ->call(function(CommonToken $integer) {
                return new TES4Integer($integer->getValue());
            })
            ->is('Boolean')
            ->call(function(CommonToken $integer) {
                if(strtolower($integer->getValue()) == "true") {
                    return new TES4Integer(1);
                }

                return new TES4Integer(0);
            })
            ->is('String')
            ->call(function(CommonToken $string) {
                return new TES4String($string->getValue());
            });

        $this('Return')
            ->is('ReturnToken','NWL')
            ->call(function($returnToken, $nwl) {
                return new TES4Return();
            })
            ->is('ReturnToken')
            ->call(function($returnToken) {
                return new TES4Return();
            });

    }

} 