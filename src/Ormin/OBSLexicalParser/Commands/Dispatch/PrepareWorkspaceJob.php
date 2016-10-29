<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 11/10/2015
 * Time: 10:50 PM
 */

namespace Ormin\OBSLexicalParser\Commands\Dispatch;


use Ormin\OBSLexicalParser\Builds\BuildTarget;
use Ormin\OBSLexicalParser\Builds\BuildTargetFactory;

class PrepareWorkspaceJob
{

    /**
     * @var string
     */
    private $buildTarget;


    public function __construct($buildTarget)
    {
        $this->buildTarget = $buildTarget;
    }


    public function run()
    {

        $buildTarget = BuildTargetFactory::get($this->buildTarget);

        $systemCommand = "cp -a ".$buildTarget->getTranspiledPath().". ".$buildTarget->getWorkspacePath();
        shell_exec(escapeshellcmd($systemCommand));
        $systemCommand = "cp -a ".$buildTarget->getDependenciesPath().". ".$buildTarget->getWorkspacePath();
        shell_exec(escapeshellcmd($systemCommand));
    }

}