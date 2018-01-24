<?php

namespace Ormin\OBSLexicalParser\Builds\QF\Factory\Service;


use Ormin\OBSLexicalParser\Builds\Build;

class MappedTargetsLogService
{

    private $handle;

    public function __construct(Build $build) {
        $filename = $build->getBuildPath() . "TargetsMapping";
        $this->handle = fopen($filename,'w+');
    }

    public function writeScriptName($scriptName) {
        fwrite($this->handle,$scriptName.PHP_EOL);
    }


    public function add($originalTargetIndex,$mappedTargetIndexes = []) {
        fwrite($this->handle,$originalTargetIndex.' '.implode('	',$mappedTargetIndexes).PHP_EOL);
    }

}