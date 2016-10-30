<?php
/**
 * Created by PhpStorm.
 * Date: 10/30/16
 * Time: 11:35 PM
 */

namespace Ormin\OBSLexicalParser\Builds;


class BuildSourceFilesCollection
{

    /**
     * @var string[][]
     */
    private $sourceFiles = [];

    public function add(BuildTarget $buildTarget, $sourceFiles) {

        if(!isset($this->sourceFiles[$buildTarget->getTargetName()])) {
            $this->sourceFiles[$buildTarget->getTargetName()] = [];
        }

        $this->sourceFiles[$buildTarget->getTargetName()] = array_unique(array_merge($this->sourceFiles[$buildTarget->getTargetName()],$sourceFiles));
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->sourceFiles);
    }

}