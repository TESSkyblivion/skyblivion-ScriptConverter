<?php
/**
 * Created by PhpStorm.
 * Date: 1/25/17
 * Time: 11:18 PM
 */

namespace Ormin\OBSLexicalParser\Builds;


class TranspiledScriptsWriter
{
    
    public function writeTranspiledScripts(BuildTargetCollection $buildTargets, BuildTracker $buildTracker)
    {
        
        foreach($buildTargets->getIterator() as $buildTarget)
        {
            $scripts = $buildTracker->getBuiltScripts($buildTarget);
            
            if($buildTarget->getTargetName() == "QF")
            {
                /**
                 * Combine the Quest fragments into one big quest fragment script
                 * todo
                 */
            }
            
            foreach($scripts as $script)
            {
                file_put_contents($script->getOutputPath(), $script->getScript()->output());
            }
            
            
        }
        
    }

}