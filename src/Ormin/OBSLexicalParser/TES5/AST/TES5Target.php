<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 23.12.15
 * Time: 21:23
 */

namespace Ormin\OBSLexicalParser\TES5\AST;


class TES5Target
{

    private $script;

    private $outputPath;

    public function __construct(TES5Script $script, $outputPath) {
        $this->script = $script;
        $this->outputPath = $outputPath;
    }

    /**
     * @return TES5Script
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * @return mixed
     */
    public function getOutputPath()
    {
        return $this->outputPath;
    }



}