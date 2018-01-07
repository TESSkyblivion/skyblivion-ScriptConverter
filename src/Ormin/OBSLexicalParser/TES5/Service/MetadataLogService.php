<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Service;


use Ormin\OBSLexicalParser\Builds\Build;

class MetadataLogService {

    private $handle;

    public function __construct(Build $build) {
        $filename = $build->getBuildPath() . "Metadata";
        $this->handle = fopen($filename,'a+');
    }

    public function add($command,$arguments = []) {
        fwrite($this->handle,$command.' '.implode('	',$arguments).PHP_EOL);
    }

} 