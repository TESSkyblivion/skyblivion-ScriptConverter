<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Commands;

use Dariuszp\CliProgressBar;
use Ormin\OBSLexicalParser\Builds\BuildTargetFactory;
use Ormin\OBSLexicalParser\TES4\AST\Value\ObjectAccess\TES4ObjectProperty;
use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\AST\Property\TES5Property;
use Ormin\OBSLexicalParser\TES5\Context\TypeMapper;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Graph\TES5ScriptDependencyGraph;
use Ormin\OBSLexicalParser\TES5\Service\TES5TypeInferencer;
use Ormin\OBSLexicalParser\TES5\Types\TES5BasicType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

class BuildInteroperableCompilationGraphs extends Command
{

    protected function configure()
    {
        $this
            ->setName('skyblivion:parser:buildGraphs')
            ->setDescription('Build graphs of scripts which are interconnected to be transpiled together')
            ->addArgument('targets', InputArgument::OPTIONAL, "The build targets", "Standalone,TIF");

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        set_time_limit(10800);

        $targets = $input->getArgument('targets');
        $errorLog = fopen("graph_error_log","w+");
        $log = fopen("graph_debug_log","w+");
        $buildTargets = BuildTargetFactory::getCollection($targets);

        if(!$buildTargets->canBuild()) {
            $output->writeln("Targets current build dir not clean, archive it manually.");
            return;
        }

        $sourceFiles = $buildTargets->getSourceFiles();
        $totalCount = 0;

        foreach($sourceFiles as $sourceBuildFiles) {
            $totalCount += count($sourceBuildFiles);
        }

        $inferencer = new TES5TypeInferencer(new ESMAnalyzer(new TypeMapper()),'./BuildTargets/Standalone/Source/');

        $dependencyGraph = [];
        $usageGraph = [];

        $progressBar = new CliProgressBar($totalCount);
        $progressBar->display();

        foreach($sourceFiles as $buildTargetName => $sourceFile) {

            try {
                $buildTarget = $buildTargets->getByName($buildTargetName);
                $scriptName = substr($sourceFile, 0, -4);
                $AST = $buildTarget->getAST($buildTarget->getSourceFromPath($scriptName));

                /**
                 * @var TES4ObjectProperty[] $propertiesAccesses
                 */
                $propertiesAccesses = [];
                $AST->filter(function ($data) use (&$propertiesAccesses) {

                    if($data instanceof TES4ObjectProperty) {
                        $propertiesAccesses[] = $data;
                    }

                });

                /**
                 * @var TES5Property[] $preparedProperties
                 */
                $preparedProperties = [];
                /**
                 * @var TES5Type[] $preparedPropertiesTypes
                 */

                $preparedPropertiesTypes = [];
                foreach($propertiesAccesses as $property) {
                    preg_match("#([0-9a-zA-Z]+)\.([0-9a-zA-Z]+)#i", $property->getData(), $matches);
                    $propertyName = $matches[1];
                    $propertyKeyName = strtolower($propertyName);
                    if (!isset($preparedProperties[$propertyKeyName])) {
                        $preparedProperty = new TES5Property($propertyName, TES5BasicType::T_FORM(), $matches[1]);
                        $preparedProperties[$propertyKeyName] = $preparedProperty;
                        $inferencingType = $inferencer->resolveInferenceTypeByReferenceEdid($preparedProperty);
                        $preparedPropertiesTypes[$propertyKeyName] = $inferencingType;
                    } else {
                        $preparedProperty = $preparedProperties[$propertyKeyName];
                        $inferencingType = $inferencer->resolveInferenceTypeByReferenceEdid($preparedProperty);
                        if($inferencingType != $preparedPropertiesTypes[$propertyKeyName]) {
                            throw new ConversionException("Cannot settle up the properties types - conflict.");
                        }
                    }
                }

                fwrite($log, $scriptName." - ".count($preparedProperties)." prepared".PHP_EOL);

                foreach($preparedProperties as $preparedPropertyKey => $preparedProperty) {

                    //Only keys are lowercased.
                    $lowerPropertyType = strtolower($preparedPropertiesTypes[$preparedPropertyKey]->value());
                    $lowerScriptType = strtolower($scriptName);

                    if(!isset($dependencyGraph[$lowerPropertyType])) {
                        $dependencyGraph[$lowerPropertyType] = [];
                    }
                    $dependencyGraph[$lowerPropertyType][] = $lowerScriptType;

                    if(!isset($usageGraph[$lowerScriptType])) {
                        $usageGraph[$lowerScriptType] = [];
                    }

                    $usageGraph[$lowerScriptType][] = $lowerPropertyType;
                    fwrite($log,'Registering a dependency from '.$scriptName.' to '.$preparedPropertiesTypes[$preparedPropertyKey]->value().PHP_EOL);
                }
                $progressBar->progress();

            } catch(\Exception $e) {
                fwrite($errorLog, $sourceFile.PHP_EOL.$e->getMessage());
                continue;
            }
        }

        $progressBar->end();
        $graph = new TES5ScriptDependencyGraph($dependencyGraph, $usageGraph);
        file_put_contents('app/graph_'.$buildTargets->getUniqueBuildFingerprint(),serialize($graph));
        fclose($errorLog);
        fclose($log);
    }


}