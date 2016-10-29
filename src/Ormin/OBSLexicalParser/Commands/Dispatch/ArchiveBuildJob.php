<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 11/10/2015
 * Time: 10:50 PM
 */

namespace Ormin\OBSLexicalParser\Commands\Dispatch;


use Ormin\OBSLexicalParser\Builds\BuildTarget;
use Ormin\OBSLexicalParser\Builds\BuildTargetFactory;

class ArchiveBuildJob
{

    /**
     * @var string
     */
    private $buildTarget;


    public function __construct($buildTarget)
    {
        $this->buildTarget = $buildTarget;
    }


    public function run()
    {

        $buildTarget = BuildTargetFactory::get($this->buildTarget);

        $archiveScan = scandir($buildTarget->getArchivePath());

        $latestBuild = array_reduce($archiveScan, function ($latestBuild, $proposedBuild) {

            $castedProposedBuild = ((int)$proposedBuild >= 0) ? (int)$proposedBuild : 0;
            return max($latestBuild, $castedProposedBuild);

        }, 0);


        $archivedBuild = $latestBuild + 1;

        $systemCommand = "mkdir " . $buildTarget->getArchivedBuildPath($archivedBuild);
        shell_exec(escapeshellcmd($systemCommand));
        $systemCommand = "cp -a " . $buildTarget->getBuildPath() . ". " . $buildTarget->getArchivedBuildPath($archivedBuild);
        shell_exec(escapeshellcmd($systemCommand));
        $systemCommand = "./clean.sh " . $this->buildTarget;
        shell_exec(escapeshellcmd($systemCommand));


    }

}