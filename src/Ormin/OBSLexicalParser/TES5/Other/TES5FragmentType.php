<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Other;


use Eloquent\Enumeration\AbstractEnumeration;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

/**
 * Class TES5FragmentType
 * @package Ormin\OBSLexicalParser\TES5\Other
 * @method static TES5FragmentType T_TIF()
 * @method static TES5FragmentType T_QF()
 * @method static TES5FragmentType T_PF()

 */
class TES5FragmentType extends AbstractEnumeration {

    const T_TIF = "TIF";
    const T_QF = "QF";
    const T_PF = "PF";

} 