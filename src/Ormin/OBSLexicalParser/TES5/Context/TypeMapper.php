<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Context;

use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Factory\TES5TypeFactory;
use Ormin\OBSLexicalParser\TES5\Types\TES5Type;

class TypeMapper
{


    public static function map($type)
    {
        switch ($type) {

            case "AACT":
            {
                $propertyType = 'Action';
                break;
            }
            case "ACTI":
            {
                $propertyType = 'Activator';
                break;
            }
            case "ACHR":
            case "ACRE":
            case "NPC_":
            {
                $propertyType = 'Actor';
                break;
            }
            case "AMMO":
            {
                $propertyType = 'Ammo';
                break;
            }
            case "APPA":
            {
                $propertyType = 'Apparatus';
                break;
            }

            case "CREA":
            {
                $propertyType = "Actor";
                break;
            }

            case "CLMT":
            {
                $propertyType = "Climate";
                break;
            }

            case "CLOT":
            case "ARMO":
            {
                $propertyType = 'Armor';
                break;
            }
            case "ASTP":
            {
                $propertyType = 'AssociationType';
                break;
            }
            case "BOOK":
            {
                $propertyType = 'Book';
                break;
            }
            case "CELL":
            {
                $propertyType = 'Cell';
                break;
            }
            case "CLAS":
            {
                $propertyType = 'Class';
                break;
            }
            case "COBJ":
            {
                $propertyType = 'ConstructibleObject';
                break;
            }
            case "CONT":
            {
                $propertyType = 'Container';
                break;
            }
            case "DOOR":
            {
                $propertyType = 'Door';
                break;
            }
            case "EFSH":
            {
                $propertyType = 'EffectShader';
                break;
            }
            case "ENCH":
            {
                $propertyType = 'Enchantment';
                break;
            }
            case "ECNZ":
            {
                $propertyType = 'EncounterZone';
                break;
            }
            case "EXPL":
            {
                $propertyType = 'Explosion';
                break;
            }
            case "FACT":
            {
                $propertyType = 'Faction';
                break;
            }
            case "FLOR":
            {
                $propertyType = 'Flora';
                break;
            }
            case "FLST":
            {
                $propertyType = 'FormList';
                break;
            }
            case "FURN":
            {
                $propertyType = 'Furniture';
                break;
            }
            case "GLOB":
            {
                $propertyType = 'GlobalVariable';
                break;
            }
            case "HAZD":
            {
                $propertyType = 'Hazard';
                break;
            }
            case "IMAD":
            {
                $propertyType = 'ImageSpaceModifier';
                break;
            }
            case "IPDS":
            {
                $propertyType = 'ImpactDataSet';
                break;
            }
            case "INGR":
            {
                $propertyType = 'Ingredient';
                break;
            }
            case "KEYM":
            {
                $propertyType = 'Key';
                break;
            }
            case "KYWD":
            {
                $propertyType = 'Keyword';
                break;
            }
            case "LVLN":
            {
                $propertyType = 'LeveledActor';
                break;
            }
            case "LVLI":
            {
                $propertyType = 'LeveledItem';
                break;
            }
            case "LVSP":
            {
                $propertyType = 'LeveledSpell';
                break;
            }
            case "LIGH":
            {
                $propertyType = 'Light';
                break;
            }
            case "LCTN":
            {
                $propertyType = 'Location';
                break;
            }
            case "LCRT":
            {
                $propertyType = 'LocationRefType';
                break;
            }
            case "MGEF":
            {
                $propertyType = 'MagicEffect';
                break;
            }
            case "MATH":
            {
                $propertyType = 'Math';
                break;
            }
            case "MESG":
            {
                $propertyType = 'Message';
                break;
            }
            case "MISC":
            {
                $propertyType = 'MiscObject';
                break;
            }
            case "MUSC":
            {
                $propertyType = 'MusicType';
                break;
            }
            case "REFR":
            {
                $propertyType = 'ObjectReference';
                break;
            }
            case "OTFT":
            {
                $propertyType = 'Outfit';
                break;
            }
            case "PACK":
            {
                $propertyType = 'Package';
                break;
            }
            case "PERK":
            {
                $propertyType = 'Perk';
                break;
            }
            case "ALCH":
            {
                $propertyType = 'Potion';
                break;
            }
            case "PROJ":
            {
                $propertyType = 'Projectile';
                break;
            }
            case "QUST":
            {
                $propertyType = 'Quest';
                break;
            }
            case "RACE":
            {
                $propertyType = 'Race';
                break;
            }
            case "SCEN":
            {
                $propertyType = 'Scene';
                break;
            }
            case "SCRL":
            {
                $propertyType = 'Scroll';
                break;
            }
            case "SHOU":
            {
                $propertyType = 'Shout';
                break;
            }
            case "SLGM":
            {
                $propertyType = 'SoulGem';
                break;
            }
            case "SOUN":
            {
                $propertyType = 'Sound';
                break;
            }
            case "SNCT":
            {
                $propertyType = 'SoundCategory';
                break;
            }
            case "SPEL":
            {
                $propertyType = 'Spell';
                break;
            }
            case "STAT":
            {
                $propertyType = 'Static';
                break;
            }
            case "TACT":
            {
                $propertyType = 'TalkingActivator';
                break;
            }
            case "DIAL":
            {
                $propertyType = 'Topic';
                break;
            }
            case "INFO":
            {
                $propertyType = 'TopicInfo';
                break;
            }
            case "RFCT":
            {
                $propertyType = 'VisualEffect';
                break;
            }
            case "VTYP":
            {
                $propertyType = 'VoiceType';
                break;
            }
            case "WEAP":
            {
                $propertyType = 'Weapon';
                break;
            }
            case "WTHR":
            {
                $propertyType = 'Weather';
                break;
            }
            case "WOOP":
            {
                $propertyType = 'WordOfPower';
                break;
            }
            case "WRLD":
            {
                $propertyType = 'WorldSpace';
                break;
            }

            default:
            {
                throw new ConversionException("Unknown " . $type . " formid reference.");
            }


        }

        return TES5TypeFactory::memberByValue($propertyType);
    }

} 