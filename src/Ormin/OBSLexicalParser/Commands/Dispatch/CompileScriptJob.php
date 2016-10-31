<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 11/10/2015
 * Time: 10:50 PM
 */

namespace Ormin\OBSLexicalParser\Commands\Dispatch;


use Ormin\OBSLexicalParser\Builds\BuildTarget;
use Ormin\OBSLexicalParser\Builds\BuildTargetCollection;
use Ormin\OBSLexicalParser\Builds\BuildTargetFactory;

class CompileScriptJob
{

    /**
     * @var BuildTargetCollection
     */
    private $buildTargetCollection;

    private $logPath;

    public function __construct(BuildTargetCollection $buildTargetCollection, $logPath)
    {
        $this->buildTargetCollection = $buildTargetCollection;
        $this->logPath = $logPath;
    }


    public function run()
    {

        /**
         * @var BuildTarget $buildTarget
         */
        foreach($this->buildTargetCollection->getIterator() as $buildTarget) {
            $compileLog = fopen($this->logPath, "w+");
            $logs = $buildTarget->compile($buildTarget->getTranspiledPath(), $buildTarget->getWorkspacePath(), $buildTarget->getArtifactsPath());
            fwrite($compileLog, $logs);
            fclose($compileLog);
        }
    }

}