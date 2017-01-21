<?php
/**
 * Created by PhpStorm.
 * Date: 10/31/16
 * Time: 3:51 PM
 */

namespace Ormin\OBSLexicalParser\TES4\AST;


use Ormin\OBSLexicalParser\TES4\AST\Code\TES4CodeChunks;

class TES4FragmentTarget
{
    /**
     * @var TES4CodeChunks
     */
    private $codeChunks;

    /**
     * @var string
     */
    private $outputPath;

    /**
     * TES4FragmentTarget constructor.
     * @param TES4CodeChunks $codeChunks
     * @param string $outputPath
     */
    public function __construct(TES4CodeChunks $codeChunks, $outputPath) {
        $this->codeChunks = $codeChunks;
        $this->outputPath = $outputPath;
    }

    /**
     * @return TES4CodeChunks
     */
    public function getCodeChunks()
    {
        return $this->codeChunks;
    }

    /**
     * @return mixed
     */
    public function getOutputPath()
    {
        return $this->outputPath;
    }
}