<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Service;


class MetadataLogService {

    private $handle;

    public function __construct($filename) {
        $this->handle = fopen($filename,'w+');
    }

    public function add($command,$arguments = []) {
        fwrite($this->handle,$command.' '.implode('	',$arguments).PHP_EOL);
    }

} 