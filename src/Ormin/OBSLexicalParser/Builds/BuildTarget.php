<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;


/**
 * Class BuildTarget
 * @package Ormin\OBSLexicalParser\Builds
 */
class BuildTarget
{

    /**
     * @var string
     */
    private $targetName;

    /**
     * @var TranspileCommand
     */
    private $transpileCommand;

    /**
     * @var CompileCommand
     */
    private $compileCommand;

    /**
     * @var ASTCommand
     */
    private $ASTCommand;

    /**
     * Needed for proper resolution of filename
     * @var TES5NameTransformer
     */
    private $nameTransformer;

    public function __construct($targetName, TranspileCommand $transpileCommand, CompileCommand $compileCommand, TES5NameTransformer $nameTransformer, ASTCommand $ASTCommand)
    {
        $this->transpileInitialized = false;
        $this->compileInitialized = false;
        $this->ASTInitialized = false;
        $this->targetName = $targetName;
        $this->transpileCommand = $transpileCommand;
        $this->compileCommand = $compileCommand;
        $this->nameTransformer = $nameTransformer;
        $this->ASTCommand = $ASTCommand;
    }


    public function transpile($sourcePath, $outputPath)
    {

        if (!$this->transpileInitialized) {
            $this->transpileCommand->initialize();
            $this->transpileInitialized = true;
        }

        return $this->transpileCommand->transpile($sourcePath, $outputPath);
    }

    public function compile($sourcePath, $workspacePath, $outputPath)
    {

        if (!$this->compileInitialized) {
            $this->compileCommand->initialize();
            $this->compileInitialized = true;
        }

        return $this->compileCommand->compile($sourcePath, $workspacePath, $outputPath);
    }

    public function getAST($sourcePath)
    {

        if (!$this->ASTInitialized) {
            $this->ASTCommand->initialize();
            $this->ASTInitialized = true;
        }

        return $this->ASTCommand->getAST($sourcePath);
    }


    /**
     * @return string
     */
    public function getTargetName()
    {
        return $this->targetName;
    }


    public function getSourcePath()
    {
        return $this->getRootBuildTargetPath() . "/Source/";
    }

    public function getDependenciesPath()
    {
        return $this->getRootBuildTargetPath() . "/Dependencies/";
    }

    public function getBuildPath()
    {
        return $this->getRootBuildTargetPath() . "/Build/";
    }

    public function getArchivePath()
    {
        return $this->getRootBuildTargetPath() . "/Archive/";
    }

    public function getArchivedBuildPath($buildNumber)
    {
        return $this->getRootBuildTargetPath() . "/Archive/" . $buildNumber . "/";
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

    public function getTranspiledPath()
    {
        return $this->getBuildPath() . "Transpiled/";
    }

    public function getArtifactsPath()
    {
        return $this->getBuildPath() . "Artifacts/";
    }

    public function getSourceFromPath($scriptName)
    {
        return $this->getSourcePath() . $scriptName . ".txt";
    }

    public function getWorkspaceFromPath($scriptName)
    {
        return $this->getWorkspacePath() . $scriptName . ".psc";
    }

    public function getTranspileToPath($scriptName)
    {
        $prefix = "TES4";
        $transformedName = $this->nameTransformer->transform($scriptName, "TES4");
        return $this->getTranspiledPath() . $prefix . $transformedName . ".psc";
    }

    public function getCompileToPath($scriptName)
    {
        return $this->getArtifactsPath() . $scriptName . ".pex";
    }

    private function getRootBuildTargetPath()
    {
        return "./BuildTargets/" . $this->getTargetName();
    }

} 