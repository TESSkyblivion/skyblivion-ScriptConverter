<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES5\Types;


use Eloquent\Enumeration\AbstractEnumeration;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;

/**
 * Class TES5BasicType
 * @package Ormin\OBSLexicalParser\TES5\Types
 * @method static TES5BasicType T_ACTIVEMAGICEFFECT()
 * @method static TES5BasicType T_INT()
 * @method static TES5BasicType T_FLOAT()
 * @method static TES5BasicType T_ALIAS()
 * @method static TES5BasicType T_REFERENCEALIAS()
 * @method static TES5BasicType T_LOCATIONALIAS()
 * @method static TES5BasicType T_UTILITY()
 * @method static TES5BasicType T_DEBUG()
 * @method static TES5BasicType T_GAME()
 * @method static TES5BasicType T_MAIN()
 * @method static TES5BasicType T_MATH()
 * @method static TES5BasicType T_FORM()
 * @method static TES5BasicType T_ACTION()
 * @method static TES5BasicType T_MAGICEFFECT()
 * @method static TES5BasicType T_ACTIVATOR()
 * @method static TES5BasicType T_FURNITURE()
 * @method static TES5BasicType T_FLORA()
 * @method static TES5BasicType T_TALKINGACTIVATOR()
 * @method static TES5BasicType T_MESSAGE()
 * @method static TES5BasicType T_ACTORBASE()
 * @method static TES5BasicType T_MISCOBJECT()
 * @method static TES5BasicType T_APPARATUS()
 * @method static TES5BasicType T_CONSTRUCTIBLEOBJECT()
 * @method static TES5BasicType T_KEY()
 * @method static TES5BasicType T_SOULGEM()
 * @method static TES5BasicType T_AMMO()
 * @method static TES5BasicType T_ARMOR()
 * @method static TES5BasicType T_ARMORADDON()
 * @method static TES5BasicType T_ASSOCIATIONTYPE()
 * @method static TES5BasicType T_MUSICTYPE()
 * @method static TES5BasicType T_BOOK()
 * @method static TES5BasicType T_BOOL()
 * @method static TES5BasicType T_OBJECTREFERENCE()
 * @method static TES5BasicType T_ACTOR()
 * @method static TES5BasicType T_CELL()
 * @method static TES5BasicType T_CLASS()
 * @method static TES5BasicType T_OUTFIT()
 * @method static TES5BasicType T_COLORFORM()
 * @method static TES5BasicType T_PACKAGE()
 * @method static TES5BasicType T_COMBATSTYLE()
 * @method static TES5BasicType T_CONTAINER()
 * @method static TES5BasicType T_PERK()
 * @method static TES5BasicType T_DOOR()
 * @method static TES5BasicType T_POTION()
 * @method static TES5BasicType T_EFFECTSHADER()
 * @method static TES5BasicType T_PROJECTILE()
 * @method static TES5BasicType T_ENCHANTMENT()
 * @method static TES5BasicType T_QUEST()
 * @method static TES5BasicType T_ENCOUNTERZONE()
 * @method static TES5BasicType T_RACE()
 * @method static TES5BasicType T_COLORCOMPONENT()
 * @method static TES5BasicType T_EQUIPSLOT()
 * @method static TES5BasicType T_SCENE()
 * @method static TES5BasicType T_EXPLOSION()
 * @method static TES5BasicType T_FACTION()
 * @method static TES5BasicType T_FORMLIST()
 * @method static TES5BasicType T_SCROLL()
 * @method static TES5BasicType T_GLOBALVARIABLE()
 * @method static TES5BasicType T_SHOUT()
 * @method static TES5BasicType T_HAZARD()
 * @method static TES5BasicType T_SOUND()
 * @method static TES5BasicType T_HEADPART()
 * @method static TES5BasicType T_SOUNDCATEGORY()
 * @method static TES5BasicType T_IDLE()
 * @method static TES5BasicType T_SPELL()
 * @method static TES5BasicType T_IMAGESPACEMODIFIER()
 * @method static TES5BasicType T_STATIC()
 * @method static TES5BasicType T_IMPACTDATASET()
 * @method static TES5BasicType T_TEXTURESET()
 * @method static TES5BasicType T_INGREDIENT()
 * @method static TES5BasicType T_TOPIC()
 * @method static TES5BasicType T_KEYWORD()
 * @method static TES5BasicType T_LOCATIONREFTYPE()
 * @method static TES5BasicType T_TOPICINFO()
 * @method static TES5BasicType T_LEVELEDACTOR()
 * @method static TES5BasicType T_VISUALEFFECT()
 * @method static TES5BasicType T_LEVELEDITEM()
 * @method static TES5BasicType T_VOICETYPE()
 * @method static TES5BasicType T_LEVELEDSPELL()
 * @method static TES5BasicType T_WEAPON()
 * @method static TES5BasicType T_LIGHT()
 * @method static TES5BasicType T_WEATHER()
 * @method static TES5BasicType T_LOCATION()
 * @method static TES5BasicType T_WORDOFPOWER()
 * @method static TES5BasicType T_WORLDSPACE()
 * @method static TES5BasicType T_INPUT()
 * @method static TES5BasicType T_SKSE()
 * @method static TES5BasicType T_STRING()
 * @method static TES5BasicType T_STRINGUTIL()
 * @method static TES5BasicType T_UI()
 * @method static TES5BasicType T_TES4TIMERHELPER()
 */
class TES5BasicType extends AbstractEnumeration implements TES5Type {

    const T_ACTIVEMAGICEFFECT = "ActiveMagicEffect";
    const T_ALIAS = "Alias";
    const T_REFERENCEALIAS = "ReferenceAlias";
    const T_LOCATIONALIAS = "LocationAlias";
    const T_UTILITY = "Utility";
    const T_DEBUG = "Debug";
    const T_GAME = "Game";
    const T_MAIN = "Main";
    const T_MATH = "Math";
    const T_FORM = "Form";
    const T_ACTION = "Action";
    const T_MAGICEFFECT = "MagicEffect";
    const T_ACTIVATOR = "Activator";
    const T_FURNITURE = "Furniture";
    const T_FLORA = "Flora";
    const T_TALKINGACTIVATOR = "TalkingActivator";
    const T_MESSAGE = "Message";
    const T_COLORCOMPONENT = "ColorComponent";
    const T_ACTORBASE = "ActorBase";
    const T_MISCOBJECT = "MiscObject";
    const T_APPARATUS = "Apparatus";
    const T_CONSTRUCTIBLEOBJECT = "ConstructibleObject";
    const T_KEY = "Key";
    const T_SOULGEM = "SoulGem";
    const T_AMMO = "Ammo";
    const T_ARMOR = "Armor";
    const T_ARMORADDON = "ArmorAddon";
    const T_ASSOCIATIONTYPE = "AssociationType";
    const T_MUSICTYPE = "MusicType";
    const T_BOOK = "Book";
    const T_BOOL = "Bool";
    const T_OBJECTREFERENCE = "ObjectReference";
    const T_ACTOR = "Actor";
    const T_CELL = "Cell";
    const T_CLASS = "Class";
    const T_OUTFIT = "Outfit";
    const T_COLORFORM = "ColorForm";
    const T_PACKAGE = "Package";
    const T_COMBATSTYLE = "CombatStyle";
    const T_CONTAINER = "Container";
    const T_PERK = "Perk";
    const T_DOOR = "Door";
    const T_POTION = "Potion";
    const T_EFFECTSHADER = "EffectShader";
    const T_PROJECTILE = "Projectile";
    const T_ENCHANTMENT = "Enchantment";
    const T_QUEST = "Quest";
    const T_ENCOUNTERZONE = "EncounterZone";
    const T_RACE = "Race";
    const T_EQUIPSLOT = "EquipSlot";
    const T_SCENE = "Scene";
    const T_EXPLOSION = "Explosion";
    const T_FACTION = "Faction";
    const T_FORMLIST = "FormList";
    const T_FLOAT = "Float";
    const T_STRING = "String";
    const T_SCROLL = "Scroll";
    const T_GLOBALVARIABLE = "GlobalVariable";
    const T_SHOUT = "Shout";
    const T_HAZARD = "Hazard";
    const T_SOUND = "Sound";
    const T_HEADPART = "HeadPart";
    const T_SOUNDCATEGORY = "SoundCategory";
    const T_IDLE = "Idle";
    const T_INT = "Int";
    const T_SPELL = "Spell";
    const T_IMAGESPACEMODIFIER = "ImageSpaceModifier";
    const T_STATIC = "Static";
    const T_IMPACTDATASET = "ImpactDataSet";
    const T_TEXTURESET = "TextureSet";
    const T_INGREDIENT = "Ingredient";
    const T_TOPIC = "Topic";
    const T_KEYWORD = "Keyword";
    const T_LOCATIONREFTYPE = "LocationRefType";
    const T_TOPICINFO = "TopicInfo";
    const T_LEVELEDACTOR = "LeveledActor";
    const T_VISUALEFFECT = "VisualEffect";
    const T_LEVELEDITEM = "LeveledItem";
    const T_VOICETYPE = "VoiceType";
    const T_LEVELEDSPELL = "LeveledSpell";
    const T_WEAPON = "Weapon";
    const T_LIGHT = "Light";
    const T_WEATHER = "Weather";
    const T_LOCATION = "Location";
    const T_WORDOFPOWER = "WordOfPower";
    const T_WORLDSPACE = "WorldSpace";
    const T_INPUT = "Input";
    const T_SKSE = "SKSE";
    const T_STRINGUTIL = "StringUtil";
    const T_UI = "UI";
    const T_TES4TIMERHELPER= "TES4TimerHelper";

    public function isPrimitive() {
        return ($this == self::T_BOOL() || $this == self::T_INT() || $this == self::T_STRING() || $this == self::T_FLOAT());
    }

    public function isNativePapyrusType() {
        return true;
    }

    public function output() {
        return $this->value();
    }


    public function setNativeType(TES5BasicType $basicType) {
        throw new ConversionException("Cannot set native type on basic type - wrong logic.");
    }

    public function getNativeType() {
        return $this;
    }

} 