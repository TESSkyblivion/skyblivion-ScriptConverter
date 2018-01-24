<?php
/**
 * Created by PhpStorm.
 * Date: 2/4/17
 * Time: 8:17 PM
 */

namespace Ormin\OBSLexicalParser\Builds\QF\Factory\Map;


use Ormin\OBSLexicalParser\TES5\AST\TES5Target;

class QuestStageScript
{

    /**
     * @var TES5Target
     */
    private $script;

    /**
     * @var int
     */
    private $stage;

    /**
     * @var int
     */
    private $logIndex;

    public function __construct(TES5Target $script, $stage, $logIndex) {
        $this->script = $script;
        $this->stage = $stage;
        $this->logIndex = $logIndex;
    }

    /**
     * @return TES5Target
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * @return int
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * @return int
     */
    public function getLogIndex()
    {
        return $this->logIndex;
    }

    



}