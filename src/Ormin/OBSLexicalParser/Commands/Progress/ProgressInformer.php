<?php
/**
 * Created by PhpStorm.
 * Date: 2/4/17
 * Time: 4:56 PM
 */

namespace Ormin\OBSLexicalParser\Commands\Progress;


use Symfony\Component\Console\Output\OutputInterface;

class ProgressInformer
{

    /**
     * @var int
     */
    private $counter = 0;

    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function progress()
    {
        $this->counter++;
        if($this->counter % 100 == 0) {
            $this->output->writeln($this->counter." ..");
        }
    }
    
}