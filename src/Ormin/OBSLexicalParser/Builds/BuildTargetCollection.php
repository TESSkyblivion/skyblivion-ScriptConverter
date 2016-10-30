<?php
/**
 * Created by PhpStorm.
 * Date: 10/30/16
 * Time: 11:10 PM
 */

namespace Ormin\OBSLexicalParser\Builds;


class BuildTargetCollection implements \IteratorAggregate
{

    /**
     * @var BuildTarget[]
     */
    private $buildTargets = [];

    public function add(BuildTarget $buildTarget) {
        $this->buildTargets[$buildTarget->getTargetName()] = $buildTarget;
    }

    public function canBuild()
    {
        $result = true;

        foreach($this->buildTargets as $buildTarget) {
            $result = $result && $buildTarget->canBuild();
        }

        return $result;
    }

    public function getByName($name)
    {
        if(!isset($this->buildTargets[$name]))
        {
            return null;
        }

        return $this->buildTargets[$name];
    }

    public function getSourceFiles()
    {
        $collection = new BuildSourceFilesCollection();
        foreach ($this->buildTargets as $buildTarget)
        {
            $collection->add($buildTarget, $buildTarget->getSourceFileList());
        }

        return $collection;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->buildTargets);
    }

}