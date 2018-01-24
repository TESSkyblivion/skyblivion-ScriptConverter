<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 3/28/2017
 * Time: 7:28 PM
 */

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCall;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCallArguments;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\Service\TES5TypeInferencer;

class TES5ObjectCallFactory
{
    /**
     * @var TES5TypeInferencer
     */
    private $typeInferencer;

    public function __construct(TES5TypeInferencer $typeInferencer)
    {
        $this->typeInferencer = $typeInferencer;
    }

    public function createObjectCall(TES5Referencer $callable, $functionName,
                                     TES5MultipleScriptsScope $multipleScriptsScope,
                                     TES5ObjectCallArguments $arguments = null, $inference = true)
    {
        $objectCall = new TES5ObjectCall($callable, $functionName, $arguments);

        if ($inference)
            $this->typeInferencer->inferenceObjectByMethodCall($objectCall, $multipleScriptsScope);

        return $objectCall;
    }

}