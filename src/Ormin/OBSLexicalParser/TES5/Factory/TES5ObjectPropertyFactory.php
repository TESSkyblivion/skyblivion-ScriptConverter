<?php

namespace Ormin\OBSLexicalParser\TES5\Factory;


use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectProperty;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5Referencer;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\Service\TES5TypeInferencer;

class TES5ObjectPropertyFactory {

    /**
     * @var \Ormin\OBSLexicalParser\TES5\Service\TES5TypeInferencer
     */
    private $typeInferencer;

    public function __construct(TES5TypeInferencer $typeInferencer) {
        $this->typeInferencer = $typeInferencer;
    }

    public function createObjectProperty(TES5MultipleScriptsScope $multipleScriptsScope, TES5Referencer $reference, $propertyName) {

        $this->typeInferencer->inferenceVariableByReferenceEdid($reference->getReferencesTo(), $multipleScriptsScope);

        $remoteProperty = $multipleScriptsScope->getPropertyFromScript($reference->getReferencesTo()->getPropertyType()->value(), $propertyName);

        $objectProperty = new TES5ObjectProperty($reference, $remoteProperty);
        return $objectProperty;
    }

} 