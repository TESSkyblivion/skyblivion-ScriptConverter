<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 11/11/2015
 * Time: 12:30 AM
 */

namespace Ormin\OBSLexicalParser\Builds;


use Ormin\OBSLexicalParser\Builds\Service\FragmentsParsingService;
use Ormin\OBSLexicalParser\Builds\Service\StandaloneParsingService;
use Ormin\OBSLexicalParser\TES4\Parser\SyntaxErrorCleanParser;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;

class BuildTargetFactory
{

    public static function getCollection($targetsString, Build $build)
    {
        $targets = explode(",",$targetsString);
        $collection = new BuildTargetCollection();
        foreach($targets as $k => $v)
        {
            $collection->add(static::get(trim($v), $build));
        }

        return $collection;

    }

    public static function get($target, Build $build)
    {

        switch ($target) {

            case 'Standalone': {
                
                $standaloneParsingService = new StandaloneParsingService(
                    new SyntaxErrorCleanParser(new \Ormin\OBSLexicalParser\TES4\Parser\TES4OBScriptGrammar())
                );
                
                return new BuildTarget(
                    'Standalone',
                    $build,
                    new TES5NameTransformer(),
                    new \Ormin\OBSLexicalParser\Builds\Standalone\TranspileCommand($standaloneParsingService),
                    new \Ormin\OBSLexicalParser\Builds\Standalone\CompileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\Standalone\ASTCommand(),
                    new \Ormin\OBSLexicalParser\Builds\Standalone\BuildScopeCommand($standaloneParsingService)
                );

            }

            case 'TIF': {
                $fragmentsParsingService = new FragmentsParsingService(
                    new SyntaxErrorCleanParser(new \Ormin\OBSLexicalParser\TES4\Parser\TES4ObscriptCodeGrammar())
                );


                return new BuildTarget(
                    'TIF',
                    $build,
                    new TES5NameTransformer(),
                    new \Ormin\OBSLexicalParser\Builds\TIF\TranspileCommand($fragmentsParsingService),
                    new \Ormin\OBSLexicalParser\Builds\TIF\CompileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\TIF\ASTCommand(),
                    new \Ormin\OBSLexicalParser\Builds\TIF\BuildScopeCommand()
                );
            }

            case 'PF': {
                return new BuildTarget(
                    'PF',
                    $build,
                    new TES5NameTransformer(),
                    new \Ormin\OBSLexicalParser\Builds\PF\TranspileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\PF\CompileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\PF\ASTCommand(),
                    new \Ormin\OBSLexicalParser\Builds\PF\BuildScopeCommand()
                );
            }

            case 'QF': {
                return new BuildTarget(
                    'QF',
                    $build,
                    new TES5NameTransformer(),
                    new \Ormin\OBSLexicalParser\Builds\QF\TranspileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\QF\CompileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\QF\ASTCommand(),
                    new \Ormin\OBSLexicalParser\Builds\PF\BuildScopeCommand()
                );
            }

            default: {
                throw new \LogicException("Unknown target ".$target);
            }

        }


    }

}