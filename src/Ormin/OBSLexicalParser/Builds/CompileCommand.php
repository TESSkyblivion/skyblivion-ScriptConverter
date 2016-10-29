<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds;


interface CompileCommand {

    public function initialize();

    public function compile($sourcePath, $workspacePath, $outputPath);

}