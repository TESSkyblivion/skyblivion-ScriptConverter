<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\Utilities;

class AutoloadingTask extends \Threaded {
    public function run() {
        spl_autoload_register(function($class) {
            if (0 === strpos($class, 'Ormin\\')) {
                $class = str_replace('\\', '/', $class);
                $file = __DIR__ . "/src/$class.php";
                if (file_exists($file)) {
                    require $file;
                }
            }
        });
    }
}