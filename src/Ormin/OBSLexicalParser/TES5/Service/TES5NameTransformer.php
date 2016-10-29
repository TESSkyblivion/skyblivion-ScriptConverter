<?php


namespace Ormin\OBSLexicalParser\TES5\Service;


class TES5NameTransformer
{

    public static function transform($originalName, $prefix = "")
    {

        if (strlen($prefix . $originalName) > 38) { //Cannot have more than 38 characters..
            return md5(strtolower($originalName));
        } else {
            return $originalName;
        }


    }

}