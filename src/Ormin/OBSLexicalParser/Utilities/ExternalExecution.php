<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 08.12.15
 * Time: 00:18
 */

namespace Ormin\OBSLexicalParser\Utilities;


class ExternalExecution
{
    public static function run($cmd, &$stdout, &$stderr)
    {
        $outfile = tempnam(".", "cmd");
        $errfile = tempnam(".", "cmd");
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("file", $outfile, "w"),
            2 => array("file", $errfile, "w")
        );
        $proc = proc_open($cmd, $descriptorspec, $pipes);

        if (!is_resource($proc)) return 255;

        fclose($pipes[0]);    //Don't really want to give any input

        $exit = proc_close($proc);
        $stdout = file($outfile);
        $stderr = file($errfile);

        unlink($outfile);
        unlink($errfile);
        return $exit;
    }


}