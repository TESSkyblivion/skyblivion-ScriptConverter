<?php
/**
 * Created by PhpStorm.
 * Date: 10/31/16
 * Time: 12:37 AM
 */

namespace Ormin\OBSLexicalParser\Builds;


class Build
{
    const DEFAULT_BUILD_PATH = "./Build/";

    private $buildPath;

    public function __construct($buildPath)
    {
        $this->buildPath = $buildPath;
    }

    public function getBuildPath()
    {
        return $this->buildPath;
    }

    public function getErrorLogPath()
    {
        return $this->getBuildPath() . "error_log";
    }

    public function getCompileLogPath()
    {
        return $this->getBuildPath() . "compile_log";
    }

    public function getWorkspacePath()
    {
        return $this->getBuildPath() . "Workspace/";
    }

    public function canBuild()
    {
        return !(
            (count(array_slice(scandir($this->getWorkspacePath()), 2)) > 0)
        );

    }


}