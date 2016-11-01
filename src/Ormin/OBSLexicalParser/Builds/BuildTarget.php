<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;


/**
 * Class BuildTarget
 * @package Ormin\OBSLexicalParser\Builds
 */
class BuildTarget
{

    const DEFAULT_TARGETS = "Standalone,TIF";

    /**
     * @var string
     */
    private $targetName;

    /**
     * @var string
     */
    private $filePrefix;

    /**
     * @var Build
     */
    private $build;

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
     * @var BuildScopeCommand
     */
    private $buildScopeCommand;

    /**
     * Needed for proper resolution of filename
     * @var TES5NameTransformer
     */
    private $nameTransformer;

    public function __construct($targetName,
                                $filePrefix,
                                Build $build,
                                TES5NameTransformer $nameTransformer,
                                TranspileCommand $transpileCommand,
                                CompileCommand $compileCommand,
                                ASTCommand $ASTCommand,
                                BuildScopeCommand $buildScopeCommand)
    {
        $this->transpileInitialized = false;
        $this->compileInitialized = false;
        $this->ASTInitialized = false;
        $this->scopeInitialized = false;
        $this->targetName = $targetName;
        $this->build = $build;
        $this->filePrefix = $filePrefix;
        $this->transpileCommand = $transpileCommand;
        $this->compileCommand = $compileCommand;
        $this->nameTransformer = $nameTransformer;
        $this->ASTCommand = $ASTCommand;
        $this->buildScopeCommand = $buildScopeCommand;
    }


    /**
     * @param $sourcePath
     * @param $outputPath
     * @param TES5GlobalScope $globalScope
     * @param TES5MultipleScriptsScope $compilingScope
     * @return \Ormin\OBSLexicalParser\TES5\AST\TES5Target
     */
    public function transpile($sourcePath, $outputPath, TES5GlobalScope $globalScope, TES5MultipleScriptsScope $compilingScope)
    {

        if (!$this->transpileInitialized) {
            $this->transpileCommand->initialize();
            $this->transpileInitialized = true;
        }

        return $this->transpileCommand->transpile($sourcePath, $outputPath, $globalScope, $compilingScope);
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

    public function buildScope($sourcePath)
    {
        if (!$this->scopeInitialized) {
            $this->buildScopeCommand->initialize();
            $this->scopeInitialized = true;
        }

        return $this->buildScopeCommand->buildScope($sourcePath);
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

    public function getArchivePath()
    {
        return $this->getRootBuildTargetPath() . "/Archive/";
    }

    public function getArchivedBuildPath($buildNumber)
    {
        return $this->getRootBuildTargetPath() . "/Archive/" . $buildNumber . "/";
    }

    public function getSourceFromPath($scriptName)
    {
        return $this->getSourcePath() . $scriptName . ".txt";
    }

    public function getWorkspaceFromPath($scriptName)
    {
        return $this->build->getWorkspacePath() . $scriptName . ".psc";
    }

    public function getTranspiledPath()
    {
        return $this->build->getBuildPath() . "/Transpiled/".$this->targetName."/";
    }

    public function getArtifactsPath()
    {
        return $this->build->getBuildPath() . "/Artifacts/".$this->targetName."/";
    }

    public function getWorkspacePath()
    {
        return $this->build->getWorkspacePath();
    }

    public function getTranspileToPath($scriptName)
    {
        $transformedName = $this->nameTransformer->transform($scriptName, $this->filePrefix);
        return $this->getTranspiledPath() . $this->filePrefix . $transformedName . ".psc";
    }

    public function getCompileToPath($scriptName)
    {
        return $this->getArtifactsPath() . $scriptName . ".pex";
    }

    private function getRootBuildTargetPath()
    {
        return "./BuildTargets/" . $this->getTargetName();
    }

    public function canBuild()
    {
        return (
            !(
                (count(array_slice(scandir($this->getTranspiledPath()), 2))) > 0 ||
                (count(array_slice(scandir($this->getArtifactsPath()), 2))) > 0
            ) &&
            $this->build->canBuild()
        );

    }

    /**
     * Get the sources file list
     * @return array
     */
    public function getSourceFileList()
    {
        $fileList = array_slice(scandir($this->getSourcePath()), 2);
        $sourcePaths = [];

        foreach($fileList as $file) {

            $extension = pathinfo($file, PATHINFO_EXTENSION);

            /**
             * Only files without extension or .txt are considered sources
             * You can add metadata next to those files, but they cannot have those extensions.
             */
            if($extension == "txt") {
                $sourcePaths[] = $file;
            }

        }

        return $sourcePaths;

    }

} 