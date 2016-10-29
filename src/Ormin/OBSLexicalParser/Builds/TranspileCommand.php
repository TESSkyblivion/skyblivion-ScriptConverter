<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Builds;


interface TranspileCommand {

    public function initialize();

    public function transpile($sourcePaths, $outputPaths);

} 