<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds\QF;

use Ormin\OBSLexicalParser\Builds\BuildTarget;
use Ormin\OBSLexicalParser\Builds\BuildTracker;
use Ormin\OBSLexicalParser\Builds\QF\Factory\QFFragmentFactory;
use Ormin\OBSLexicalParser\Builds\QF\Map\QuestStageScript;

class WriteCommand implements \Ormin\OBSLexicalParser\Builds\WriteCommand
{

    /**
     * @var QFFragmentFactory
     */
    private $QFFragmentFactory;

    public function __construct(QFFragmentFactory $QFFragmentFactory)
    {
        $this->QFFragmentFactory = $QFFragmentFactory;
    }

    public function write(BuildTarget $target, BuildTracker $buildTracker)
    {
        $scripts = $buildTracker->getBuiltScripts($target->getTargetName());

        $connectedQuestFragments = [];
        $jointScripts = [];

        /**
         * Group the fragments together
         */
        foreach($scripts as $script)
        {
            $scriptName = $script->getScript()->getScriptHeader()->getScriptName();
            $parts = explode("_", $scriptName);

            if(count($parts) < 3) {
                //Not able to categorize, probably wrong name of the fragment.
                continue;
            }

            $baseName = $parts[0] . "_" . $parts[1] . "_" . $parts[2];

            if(!isset($jointScripts[$baseName])) {
                $jointScripts[$baseName] = [];
            }

            $jointScripts[$baseName][] = new QuestStageScript($script,$parts[3],$parts[4]);

        }

        foreach($jointScripts as $resultingFragmentName => $subfragmentsTrees)
        {
            $connectedQuestFragments[] = $this->QFFragmentFactory->joinQFFragments($target, $resultingFragmentName, $subfragmentsTrees);
        }


        foreach($connectedQuestFragments as $connectedQuestFragment)
        {
            file_put_contents($connectedQuestFragment->getOutputPath(), $connectedQuestFragment->getScript()->output());
        }
    }

} 