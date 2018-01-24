<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds\Standalone;

use Ormin\OBSLexicalParser\Builds\BuildTarget;
use Ormin\OBSLexicalParser\Builds\BuildTracker;

class WriteCommand implements \Ormin\OBSLexicalParser\Builds\WriteCommand
{

    public function write(BuildTarget $target, BuildTracker $buildTracker)
    {
        $scripts = $buildTracker->getBuiltScripts($target->getTargetName());

        foreach($scripts as $script)
        {
            file_put_contents($script->getOutputPath(), $script->getScript()->output());
        }
    }


} 