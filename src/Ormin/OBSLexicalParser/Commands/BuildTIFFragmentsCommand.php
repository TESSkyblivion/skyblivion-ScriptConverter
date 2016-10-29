<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Commands;


use Dice\Dice;
use Ormin\OBSLexicalParser\Input\FragmentsReferencesBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildTIFFragmentsCommand extends Command
{

    private $fragmentsReferencesBuilder;


    public function __construct($name = null) {
        $this->fragmentsReferencesBuilder = new FragmentsReferencesBuilder();
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('skyblivion:parser:buildTifFragments')
            ->setDescription('Run lexing and parsing test against whole TIF fragments suite and build papyrus scripts')
            ->addOption('skip-parsing','sp',InputOption::VALUE_OPTIONAL,"Skip the parsing part.",false)
            ->addArgument('mode', InputArgument::OPTIONAL, "The mode this build runs in. Allowed: sloppy ( will start building on over 50% scripts parsed ), normal ( 85% and over will trigger the build ),
                                                            strict ( over 95% will trigger the build ) and perfect ( only 100% will trigger the build ), defaults to strict.", "strict");

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        set_time_limit(10800); // 3 hours is the maximum for this command. Need more? You really screwed something, full suite for all Oblivion vanilla data takes 20 minutes. :)
        $mode = $input->getArgument('mode');
        switch($mode) {
            case "sloppy": {
                $threshold = 0.5;
                break;
            }

            case "normal": {
                $threshold = 0.85;
                break;
            }


            case "strict":
            default: {
            $threshold = 0.95;
            break;
            }

            case "perfect": {
                $threshold = 1;
                break;
            }
        }

        $skipParsing = $input->getOption('skip-parsing');

        if(!$skipParsing) {


            $parser = new \Ormin\OBSLexicalParser\TES4\Parser\SyntaxErrorCleanParser(new \Ormin\OBSLexicalParser\TES4\Parser\TES4ObscriptCodeGrammar());
    #        $parser = new Parser(new TES4OBScriptGrammar());
            $dice = new Dice();
            $rule = new \Dice\Rule;
            $rule->shared = true;
            $rule->shareInstances = ['Ormin\\OBSLexicalParser\\TES4\\Context\\ESMAnalyzer'];
            $dice->addRule('Ormin\\OBSLexicalParser\\TES5\\Converter\\TES4ToTES5ASTTIFFragmentConverter', $rule);
            $dice->addRule('Ormin\\OBSLexicalParser\\TES5\\Factory\\TES5TypeFactory', $rule);
            /**
             * @var \Ormin\OBSLexicalParser\TES5\Converter\TES4ToTES5ASTConverter $converter
             */
            $converter = $dice->create("Ormin\\OBSLexicalParser\\TES5\\Converter\\TES4ToTES5ASTTIFFragmentConverter");

            $inputFolder = './Fragments/TIF/fragments/';
            $outputFolder = './Fragments/TIF/PapyrusFragments/';

            $scandir = scandir($inputFolder);
            $success = 0;
            $total = 0;

            $f = fopen("php://stderr",'r+');

            $ASTTable = [];

            $output->writeln("Lexing and parsing..");
            $totalNumber = count($scandir) - 2;

            foreach($scandir as $scriptPath) {

                if($scriptPath == '.' || $scriptPath == '..') {
                    continue;
                }

                if(substr($scriptPath,-4) != ".txt") {
                    continue;
                }

                if(($total % 10) == 0){
                    $output->writeln($total." / ".$totalNumber." ...");
                }

                $outputScriptPath = substr($scriptPath,0,-4).'.psc';
                $path = $inputFolder.$scriptPath;
                ++$total;
                try {

                    $scriptName = substr($scriptPath,0,-4);
                    $output->writeln($scriptName.' ...');
                    $lexer = new \Ormin\OBSLexicalParser\TES4\Lexer\FragmentLexer();
                    $tokens = $lexer->lex(file_get_contents($path));
                    $variableList = $this->fragmentsReferencesBuilder->buildVariableDeclarationList($inputFolder.$scriptName.'.references');

                    $AST = $parser->parse($tokens);
                    $ASTTable[$scriptPath] = $AST;

                    $TES5AST = $converter->convert($scriptName, $variableList, $AST);
                    $outputScript = $TES5AST->output();
                    file_put_contents($outputFolder.$outputScriptPath,$outputScript);
                    system('lua "Utilities/beautifier.lua" "'.$outputFolder.$outputScriptPath.'"');
                    ++$success;
                } catch(\Exception $e) {
                    fwrite($f,$scriptPath.PHP_EOL.get_class($e).PHP_EOL.$e->getMessage().PHP_EOL.PHP_EOL);
                    continue;
                }
            }

            fclose($f);

            $successRate = $success/$total;

            if($successRate < $threshold) {
                $percent = round($successRate * 100);
                $output->writeln("ERROR: Build failed on parsing step in ".$mode." mode. The rate is ".$success."/".$total." ( ".$percent." %)");
                return;
            }

            $output->writeln("Parsing in ".$mode." mode succedeed ( rate ".$success."/".$total." ), copying Skyrim scripts and parsed papyrus fragments to build folder...");
        }


        $output->writeln("Build in ".$mode." mode succeeded!");
        

    }
}