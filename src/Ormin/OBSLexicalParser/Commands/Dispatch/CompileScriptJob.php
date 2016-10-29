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

class CompileScriptJob extends \Threaded
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
        $compileLog = fopen($buildTarget->getCompileLogPath(), "w+");
        $logs = $buildTarget->compile($buildTarget->getTranspiledPath(), $buildTarget->getWorkspacePath(), $buildTarget->getArtifactsPath());
        fwrite($compileLog, $logs);
        fclose($compileLog);
    }

}