<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Context;


use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class TES5LocalVariableParameterMeaning
 * @package Ormin\OBSLexicalParser\TES5\Context
 * @method static TES5LocalVariableParameterMeaning ACTIVATOR()
 * @method static TES5LocalVariableParameterMeaning CONTAINER()
 */
class TES5LocalVariableParameterMeaning extends  AbstractEnumeration {


    const ACTIVATOR = "Activator";

    const CONTAINER = "Container";

} 