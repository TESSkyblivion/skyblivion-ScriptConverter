<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 11/11/2015
 * Time: 12:30 AM
 */

namespace Ormin\OBSLexicalParser\Builds;


use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;

class BuildTargetFactory
{

    public static function getCollection($targetsString)
    {
        $targets = explode(",",$targetsString);
        $collection = new BuildTargetCollection();
        foreach($targets as $k => $v)
        {
            $collection->add(static::get(trim($targets[$v])));
        }

        return $collection;

    }

    public static function get($target)
    {

        switch ($target) {

            case 'Standalone': {
                return new BuildTarget(
                    'Standalone',
                    new \Ormin\OBSLexicalParser\Builds\Standalone\TranspileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\Standalone\CompileCommand(),
                    new TES5NameTransformer(),
                    new \Ormin\OBSLexicalParser\Builds\Standalone\ASTCommand()
                );

            }

            case 'TIF': {
                return new BuildTarget(
                    'TIF',
                    new \Ormin\OBSLexicalParser\Builds\TIF\TranspileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\TIF\CompileCommand(),
                    new TES5NameTransformer(),
                    new \Ormin\OBSLexicalParser\Builds\TIF\ASTCommand()
                );
            }

            case 'PF': {
                return new BuildTarget(
                    'PF',
                    new \Ormin\OBSLexicalParser\Builds\PF\TranspileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\PF\CompileCommand(),
                    new TES5NameTransformer(),
                    new \Ormin\OBSLexicalParser\Builds\PF\ASTCommand()
                );
            }

            case 'QF': {
                return new BuildTarget(
                    'QF',
                    new \Ormin\OBSLexicalParser\Builds\QF\TranspileCommand(),
                    new \Ormin\OBSLexicalParser\Builds\QF\CompileCommand(),
                    new TES5NameTransformer(),
                    new \Ormin\OBSLexicalParser\Builds\QF\ASTCommand()
                );
            }

            default: {
                throw new \LogicException("Unknown target");
            }

        }


    }

}