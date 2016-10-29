<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\Types;


use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class TES4Type
 * @package Ormin\OBSLexicalParser\TES4\Types
 * @method static TES4Type T_REF()
 * @method static TES4Type T_SHORT()
 * @method static TES4Type T_LONG()
 * @method static TES4Type T_FLOAT()
 * @method static TES4Type T_INT()
 * @method static TES4Type T_STRING()
 */
class TES4Type extends AbstractEnumeration {

    const T_REF = "ref";

    const T_SHORT = "short";

    const T_LONG = "long";

    const T_FLOAT = "float";

    const T_INT = "int";

    const T_STRING = "string";

} 