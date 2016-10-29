<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 * Date: 11/10/2015
 * Time: 11:54 PM
 */

namespace Ormin\OBSLexicalParser\Commands\Dispatch;


class LoadAutoloader extends \Threaded {

    public function run() {
        require 'vendor/autoload.php';
    }
}