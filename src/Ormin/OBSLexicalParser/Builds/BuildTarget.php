<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds;
use Ormin\OBSLexicalParser\TES5\AST\Property\Collection\TES5GlobalVariables;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5GlobalScope;
use Ormin\OBSLexicalParser\TES5\AST\Scope\TES5MultipleScriptsScope;
use Ormin\OBSLexicalParser\TES5\Service\TES5NameTransformer;


/**
 * Class BuildTarget
 * @package Ormin\OBSLexicalParser\Builds
 */
class BuildTarget
{
    const BUILD_TARGET_STANDALONE = "Standalone";

    const BUILD_TARGET_TIF = "TIF";

    const BUILD_TARGET_QF = "QF";

    const BUILD_TARGET_PF = "PF";

    const DEFAULT_TARGETS = self::BUILD_TARGET_STANDALONE .
                            "," .
                            self::BUILD_TARGET_TIF .
                            "," .
                            self::BUILD_TARGET_QF;

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
     * @var WriteCommand
     */
    private $writeCommand;

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
                                BuildScopeCommand $buildScopeCommand,
                                WriteCommand $writeCommand)
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
        $this->writeCommand = $writeCommand;
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
            $this->transpileCommand->initialize($this->build);
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

    /**
     * @param string $sourcePath
     * @param TES5GlobalVariables $globalVariables
     * @return TES5GlobalScope
     */
    public function buildScope($sourcePath, TES5GlobalVariables $globalVariables)
    {
        if (!$this->scopeInitialized) {
            $this->buildScopeCommand->initialize();
            $this->scopeInitialized = true;
        }

        return $this->buildScopeCommand->buildScope($sourcePath, $globalVariables);
    }

    public function write(BuildTracker $buildTracker)
    {
        $this->writeCommand->write($this, $buildTracker);
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
     * If intersected source files is not null, they will be intersected with build target source files,
     * otherwise all files will be claimed
     * @param array|null $intersectedSourceFiles
     * @return array
     */
    public function getSourceFileList(array $intersectedSourceFiles = null)
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

        if(null !== $intersectedSourceFiles) {
            $sourcePaths = array_intersect($sourcePaths, $intersectedSourceFiles);
        }


        return $sourcePaths;

    }

} 