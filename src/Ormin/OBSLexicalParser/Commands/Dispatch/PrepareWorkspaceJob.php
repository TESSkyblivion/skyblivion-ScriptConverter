<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 11/10/2015
 * Time: 10:50 PM
 */

namespace Ormin\OBSLexicalParser\Commands\Dispatch;


use Ormin\OBSLexicalParser\Builds\Build;
use Ormin\OBSLexicalParser\Builds\BuildTarget;
use Ormin\OBSLexicalParser\Builds\BuildTargetCollection;
use Ormin\OBSLexicalParser\Builds\BuildTargetFactory;

class PrepareWorkspaceJob
{

    /**
     * @var BuildTargetCollection
     */
    private $buildTargetCollection;

    public function __construct(BuildTargetCollection $buildTargetCollection)
    {
        $this->buildTargetCollection = $buildTargetCollection;
    }


    public function run()
    {

        /**
         * @var BuildTarget $buildTarget
         */
        foreach($this->buildTargetCollection->getIterator() as $buildTarget) {
            $systemCommand = "cp -a ".$buildTarget->getTranspiledPath().". ".$buildTarget->getWorkspacePath();
            shell_exec(escapeshellcmd($systemCommand));
            $systemCommand = "cp -a ".$buildTarget->getDependenciesPath().". ".$buildTarget->getWorkspacePath();
            shell_exec(escapeshellcmd($systemCommand));
        }

    }

}