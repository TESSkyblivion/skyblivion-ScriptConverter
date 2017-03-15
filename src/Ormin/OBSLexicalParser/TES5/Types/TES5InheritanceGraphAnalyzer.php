<?php
namespace Ormin\OBSLexicalParser\TES5\Types;

use Ormin\OBSLexicalParser\TES4\Context\ESMAnalyzer;
use Ormin\OBSLexicalParser\TES5\AST\Object\TES5ObjectCall;
use Ormin\OBSLexicalParser\TES5\Exception\ConversionException;
use Ormin\OBSLexicalParser\TES5\Factory\TES5TypeFactory;

class TES5InheritanceGraphAnalyzer
{

    private static $inheritanceCache;

    private static $inheritance = [

        'Alias' => [
            'ReferenceAlias',
            'LocationAlias'
        ],

        'Utility',

        'ActiveMagicEffect',

        'Debug',

        'Game',

        'Main',

        'Math',

        'Form' => [

            'Action',
            'MagicEffect',
            'Activator' => [
                'Furniture',
                'Flora',
                'TalkingActivator'
            ],
            'Message',
            'ActorBase',
            'MiscObject' => [
                'Apparatus',
                'ConstructibleObject',
                'Key',
                'SoulGem'
            ],
            'Ammo',
            'Armor',
            'ArmorAddon',
            'AssociationType',
            'MusicType',
            'Book',
            'ObjectReference' => [
                'Actor'
            ],
            'Cell',
            'Class',
            'Outfit',
            'ColorForm',
            'Package',
            'CombatStyle',
            'Container',
            'Perk',
            'Door',
            'Potion',
            'EffectShader',
            'Projectile',
            'Enchantment',
            'Quest' => [
                'TES4TimerHelper',
                'TES4Container',
            ],
            'EncounterZone',
            'Race',
            'EquipSlot',
            'Scene',
            'Explosion',
            'Faction',
            'FormList',
            'Scroll',
            'GlobalVariable',
            'Shout',
            'Hazard',
            'Sound',
            'HeadPart',
            'SoundCategory',
            'Idle',
            'Spell',
            'ImageSpaceModifier',
            'Static',
            'ImpactDataSet',
            'TextureSet',
            'Ingredient',
            'Topic',
            'Keyword' => [
                'LocationRefType'
            ],
            'TopicInfo',
            'LeveledActor',
            'VisualEffect',
            'LeveledItem',
            'VoiceType',
            'LeveledSpell',
            'Weapon',
            'Light',
            'Weather',
            'Location',
            'WordOfPower',
            'WorldSpace'

        ],

        'Input',

        'SKSE',

        'StringUtil',

        'UI'

    ];


    private static $callReturns = array (

        'ActiveMagicEffect' =>
            array (
                'AddInventoryEventFilter' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'void',
                    ),
                'Dispel' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetBaseObject' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'MagicEffect',
                    ),
                'GetCasterActor' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Actor',
                    ),
                'GetTargetActor' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Actor',
                    ),
                'RegisterForAnimationEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'RegisterForLOS' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSingleLOSGain' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSingleLOSLost' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSingleUpdate' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSleep' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForTrackedStatsEvent' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForUpdate' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForUpdateGameTime' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSingleUpdateGameTime' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RemoveAllInventoryEventFilters' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RemoveInventoryEventFilter' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'void',
                    ),
                'StartObjectProfiling' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'StopObjectProfiling' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForLOS' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAnimationEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForSleep' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForTrackedStatsEvent' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForUpdate' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForUpdateGameTime' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetDuration' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetTimeElapsed' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'RegisterForKey' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForKey' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAllKeys' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForControl' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForControl' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAllControls' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForMenu' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForMenu' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAllMenus' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForModEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForModEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAllModEvents' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'SendModEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForCameraState' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForCameraState' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForCrosshairRef' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForCrosshairRef' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForActorAction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForActorAction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Actor' =>
            array (
                'ModFavorPoints' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'ModFavorPointsWithGlobal' =>
                    array (
                        'args' =>
                            array (
                                0 => 'GlobalVariable',
                            ),
                        'returnType' => 'void',
                    ),
                'MakePlayerFriend' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'AddPerk' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Perk',
                            ),
                        'returnType' => 'void',
                    ),
                'AddShout' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Shout',
                            ),
                        'returnType' => 'bool',
                    ),
                'AddSpell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Spell',
                                1 => 'bool',
                            ),
                        'returnType' => 'bool',
                    ),
                'AllowBleedoutDialogue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'AllowPCDialogue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'AttachAshPile' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'void',
                    ),
                'CanFlyHere' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'ClearArrested' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ClearExpressionOverride' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ClearExtraArrows' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ClearForcedLandingMarker' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ClearKeepOffsetFromActor' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ClearLookAt' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'DamageActorValue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'DamageAV' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'Dismount' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'DispelAllSpells' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'DispelSpell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Spell',
                            ),
                        'returnType' => 'bool',
                    ),
                'DoCombatSpellApply' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Spell',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'EnableAI' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'EndDeferredKill' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'EquipItem' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'bool',
                                2 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'EquipShout' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Shout',
                            ),
                        'returnType' => 'void',
                    ),
                'EquipSpell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Spell',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'EvaluatePackage' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ForceActorValue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ForceAV' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetActorBase' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'ActorBase',
                    ),
                'GetActorValue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'float',
                    ),
                'GetActorValuePercentage' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'float',
                    ),
                'GetAV' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'float',
                    ),
                'GetAVPercentage' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'float',
                    ),
                'GetBaseActorValue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'float',
                    ),
                'GetBaseAV' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'float',
                    ),
                'GetBribeAmount' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetCrimeFaction' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Faction',
                    ),
                'GetCombatState' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetCombatTarget' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Actor',
                    ),
                'GetCurrentPackage' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Package',
                    ),
                'GetDialogueTarget' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Actor',
                    ),
                'GetEquippedItemType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetEquippedShout' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Shout',
                    ),
                'GetEquippedWeapon' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'Weapon',
                    ),
                'GetEquippedShield' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Armor',
                    ),
                'GetEquippedSpell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Spell',
                    ),
                'GetFactionRank' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                            ),
                        'returnType' => 'int',
                    ),
                'GetFactionReaction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'int',
                    ),
                'GetFlyingState' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetForcedLandingMarker' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'GetGoldAmount' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetHighestRelationshipRank' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetKiller' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Actor',
                    ),
                'GetLevel' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetLightLevel' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetLowestRelationshipRank' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetLeveledActorBase' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'ActorBase',
                    ),
                'GetNoBleedoutRecovery' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'GetPlayerControls' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'GetRace' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Race',
                    ),
                'GetRelationshipRank' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'int',
                    ),
                'GetRestrained' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'GetSitState' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetSleepState' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetVoiceRecoveryTime' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'HasAssociation' =>
                    array (
                        'args' =>
                            array (
                                0 => 'AssociationType',
                                1 => 'Actor',
                            ),
                        'returnType' => 'bool',
                    ),
                'HasFamilyRelationship' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'bool',
                    ),
                'HasLOS' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'bool',
                    ),
                'HasMagicEffect' =>
                    array (
                        'args' =>
                            array (
                                0 => 'MagicEffect',
                            ),
                        'returnType' => 'bool',
                    ),
                'HasMagicEffectWithKeyword' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Keyword',
                            ),
                        'returnType' => 'bool',
                    ),
                'HasParentRelationship' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'bool',
                    ),
                'HasPerk' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Perk',
                            ),
                        'returnType' => 'bool',
                    ),
                'HasSpell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsAlarmed' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsAlerted' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsAllowedToFly' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsArrested' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsArrestingTarget' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsBeingRidden' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsBleedingOut' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsBribed' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsChild' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsCommandedActor' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsDead' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsDetectedBy' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsDoingFavor' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsEquipped' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsEssential' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsFlying' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsGuard' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsGhost' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsHostileToActor' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsInCombat' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsInFaction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsInKillMove' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsIntimidated' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsOnMount' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsPlayersLastRiddenHorse' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsPlayerTeammate' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsRunning' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsSneaking' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsSprinting' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsTorchOut' =>
                    array(
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsTrespassing' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsUnconscious' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsWeaponDrawn' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'KeepOffsetFromActor' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                                4 => 'float',
                                5 => 'float',
                                6 => 'float',
                                7 => 'float',
                                8 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'Kill' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'void',
                    ),
                'KillEssential' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'void',
                    ),
                'KillSilent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'void',
                    ),
                'ModActorValue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ModAV' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ModFactionRank' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'MoveToPackageLocation' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'OpenInventory' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'PathToReference' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'float',
                            ),
                        'returnType' => 'bool',
                    ),
                'PlayIdle' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Idle',
                            ),
                        'returnType' => 'bool',
                    ),
                'PlayIdleWithTarget' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Idle',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'bool',
                    ),
                'PlaySubGraphAnimation' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'RemoveFromFaction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                            ),
                        'returnType' => 'void',
                    ),
                'RemoveFromAllFactions' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RemovePerk' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Perk',
                            ),
                        'returnType' => 'void',
                    ),
                'RemoveShout' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Shout',
                            ),
                        'returnType' => 'bool',
                    ),
                'RemoveSpell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Spell',
                            ),
                        'returnType' => 'bool',
                    ),
                'ResetHealthAndLimbs' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RestoreActorValue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'Resurrect' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RestoreAV' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SendAssaultAlarm' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'SendTrespassAlarm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'void',
                    ),
                'SetActorValue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetAlert' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetAllowFlying' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetAllowFlyingEx' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                                1 => 'bool',
                                2 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetAlpha' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetAttackActorOnSight' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetAV' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetBribed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetCrimeFaction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                            ),
                        'returnType' => 'void',
                    ),
                'SetCriticalStage' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetDoingFavor' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetDontMove' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetExpressionOverride' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetEyeTexture' =>
                    array (
                        'args' =>
                            array (
                                0 => 'TextureSet',
                            ),
                        'returnType' => 'void',
                    ),
                'SetFactionRank' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetForcedLandingMarker' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'SetGhost' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'AddToFaction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                            ),
                        'returnType' => 'void',
                    ),
                'SetHeadTracking' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetIntimidated' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetLookAt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetNoBleedoutRecovery' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetNotShowOnStealthMeter' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetOutfit' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Outfit',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetPlayerControls' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetPlayerResistingArrest' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'SetPlayerTeammate' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetRace' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Race',
                            ),
                        'returnType' => 'void',
                    ),
                'SetRelationshipRank' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetRestrained' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetSubGraphFloatVariable' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetUnconscious' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetVehicle' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'SetVoiceRecoveryTime' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ShowBarterMenu' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ShowGiftMenu' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                                1 => 'FormList',
                                2 => 'bool',
                                3 => 'bool',
                            ),
                        'returnType' => 'int',
                    ),
                'StartCannibal' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'void',
                    ),
                'StartCombat' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'void',
                    ),
                'StartDeferredKill' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'StartVampireFeed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'void',
                    ),
                'StopCombat' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'StopCombatAlarm' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'TrapSoul' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'bool',
                    ),
                'UnequipAll' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnequipItem' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'bool',
                                2 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'UnequipItemSlot' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'UnequipShout' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Shout',
                            ),
                        'returnType' => 'void',
                    ),
                'UnequipSpell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Spell',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'UnLockOwnedDoorsInCell' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'WillIntimidateSucceed' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'WornHasKeyword' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Keyword',
                            ),
                        'returnType' => 'bool',
                    ),
                'StartSneaking' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'DrawWeapon' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ForceMovementDirection' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ForceMovementSpeed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ForceMovementRotationSpeed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ForceMovementDirectionRamp' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ForceMovementSpeedRamp' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ForceMovementRotationSpeedRamp' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ForceTargetDirection' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ForceTargetSpeed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ForceTargetAngle' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ClearForcedMovement' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetWornForm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Form',
                    ),
                'GetWornItemId' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetEquippedObject' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Form',
                    ),
                'GetEquippedItemId' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetSpellCount' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'GetNthSpell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Spell',
                    ),
                'QueueNiNodeUpdate' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegenerateHead' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'EquipItemEx' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'int',
                                2 => 'bool',
                                3 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'EquipItemById' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'int',
                                2 => 'int',
                                3 => 'bool',
                                4 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'UnequipItemEx' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'int',
                                2 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'ChangeHeadPart' =>
                    array (
                        'args' =>
                            array (
                                0 => 'HeadPart',
                            ),
                        'returnType' => 'void',
                    ),
                'UpdateWeight' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'IsAIEnabled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsSwimming' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'SheatheWeapon' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'ActorBase' =>
            array (
                'GetClass' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Class',
                    ),
                'GetDeadCount' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetGiftFilter' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'FormList',
                    ),
                'GetRace' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Race',
                    ),
                'GetSex' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'IsEssential' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsInvulnerable' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsProtected' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsUnique' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'SetEssential' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetInvulnerable' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetProtected' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetOutfit' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Outfit',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'GetCombatStyle' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'CombatStyle',
                    ),
                'SetCombatStyle' =>
                    array (
                        'args' =>
                            array (
                                0 => 'CombatStyle',
                            ),
                        'returnType' => 'void',
                    ),
                'GetOutfit' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'Outfit',
                    ),
                'SetClass' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Class',
                            ),
                        'returnType' => 'void',
                    ),
                'GetHeight' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetHeight' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetWeight' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetWeight' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNumHeadParts' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthHeadPart' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'HeadPart',
                    ),
                'SetNthHeadPart' =>
                    array (
                        'args' =>
                            array (
                                0 => 'HeadPart',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetIndexOfHeadPartByType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetFaceMorph' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'float',
                    ),
                'SetFaceMorph' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetFacePreset' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'SetFacePreset' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetHairColor' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'ColorForm',
                    ),
                'SetHairColor' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ColorForm',
                            ),
                        'returnType' => 'void',
                    ),
                'GetSpellCount' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthSpell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Spell',
                    ),
                'GetFaceTextureSet' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'TextureSet',
                    ),
                'SetFaceTextureSet' =>
                    array (
                        'args' =>
                            array (
                                0 => 'TextureSet',
                            ),
                        'returnType' => 'void',
                    ),
                'GetVoiceType' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'VoiceType',
                    ),
                'SetVoiceType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'VoiceType',
                            ),
                        'returnType' => 'void',
                    ),
                'GetSkin' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Armor',
                    ),
                'SetSkin' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Armor',
                            ),
                        'returnType' => 'void',
                    ),
                'GetSkinFar' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Armor',
                    ),
                'SetSkinFar' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Armor',
                            ),
                        'returnType' => 'void',
                    ),
                'GetTemplate' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'ActorBase',
                    ),
            ),
        'Alias' =>
            array (
                'GetOwningQuest' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Quest',
                    ),
                'RegisterForAnimationEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'RegisterForLOS' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSingleLOSGain' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSingleLOSLost' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSingleUpdate' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForUpdate' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForUpdateGameTime' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSingleUpdateGameTime' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSleep' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForTrackedStatsEvent' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'StartObjectProfiling' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'StopObjectProfiling' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForLOS' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAnimationEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForSleep' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForTrackedStatsEvent' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForUpdate' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForUpdateGameTime' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetName' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'string',
                    ),
                'GetID' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'RegisterForKey' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForKey' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAllKeys' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForControl' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForControl' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAllControls' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForMenu' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForMenu' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAllMenus' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForModEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForModEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAllModEvents' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'SendModEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForCameraState' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForCameraState' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForCrosshairRef' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForCrosshairRef' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForActorAction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForActorAction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Apparatus' =>
            array (
                'GetQuality' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetQuality' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Armor' =>
            array (
                'GetArmorRating' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetAR' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetArmorRating' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetAR' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'ModArmorRating' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'ModAR' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetModelPath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'string',
                    ),
                'SetModelPath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'GetIconPath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'string',
                    ),
                'SetIconPath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'GetMessageIconPath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'string',
                    ),
                'SetMessageIconPath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'GetWeightClass' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetWeightClass' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetEnchantment' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Enchantment',
                    ),
                'SetEnchantment' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Enchantment',
                            ),
                        'returnType' => 'void',
                    ),
                'IsLightArmor' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsHeavyArmor' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsClothing' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsBoots' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsCuirass' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsGauntlets' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsHelmet' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsShield' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsJewelry' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsClothingHead' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsClothingBody' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsClothingFeet' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsClothingHands' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsClothingRing' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsClothingRich' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsClothingPoor' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'GetSlotMask' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetSlotMask' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'AddSlotToMask' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'RemoveSlotFromMask' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetMaskForSlot' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNumArmorAddons' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthArmorAddon' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'ArmorAddon',
                    ),
            ),
        'ArmorAddon' =>
            array (
                'GetModelPath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                                1 => 'bool',
                            ),
                        'returnType' => 'string',
                    ),
                'SetModelPath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'bool',
                                2 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'GetModelNumTextureSets' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                                1 => 'bool',
                            ),
                        'returnType' => 'int',
                    ),
                'GetModelNthTextureSet' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'bool',
                                2 => 'bool',
                            ),
                        'returnType' => 'TextureSet',
                    ),
                'SetModelNthTextureSet' =>
                    array (
                        'args' =>
                            array (
                                0 => 'TextureSet',
                                1 => 'int',
                                2 => 'bool',
                                3 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNumAdditionalRaces' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthAdditionalRace' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Race',
                    ),
                'GetSlotMask' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetSlotMask' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'AddSlotToMask' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'RemoveSlotFromMask' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetMaskForSlot' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
            ),
        'Book' =>
            array (
                'GetSpell' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Spell',
                    ),
                'GetSkill' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'IsRead' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsTakeable' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
            ),
        'Cell' =>
            array (
                'GetActorOwner' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'ActorBase',
                    ),
                'GetFactionOwner' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Faction',
                    ),
                'IsAttached' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsInterior' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'Reset' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'SetActorOwner' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ActorBase',
                            ),
                        'returnType' => 'void',
                    ),
                'SetFactionOwner' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                            ),
                        'returnType' => 'void',
                    ),
                'SetFogPlanes' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetFogPower' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetPublic' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNumRefs' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthRef' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'ObjectReference',
                    ),
            ),
        /* Removed due to GetValue() conflict, i will think how to readd this . todo
        'ColorComponent' =>
            array (
                'GetAlpha' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetRed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetGreen' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetBlue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetHue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'float',
                    ),
                'GetSaturation' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'float',
                    ),
                'GetValue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'float',
                    ),
                'SetAlpha' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'SetRed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'SetGreen' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'SetBlue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'SetHue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'float',
                            ),
                        'returnType' => 'int',
                    ),
                'SetSaturation' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'float',
                            ),
                        'returnType' => 'int',
                    ),
                'SetValue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'float',
                            ),
                        'returnType' => 'int',
                    ),
            ),
        'ColorForm' =>
            array (
                'GetColor' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetColor' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetRed' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetGreen' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetBlue' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetHue' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetSaturation' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetValue' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
            ),*/
        'CombatStyle' =>
            array (
                'GetOffensiveMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetDefensiveMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetGroupOffensiveMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetAvoidThreatChance' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetMeleeMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetRangedMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetMagicMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetShoutMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetStaffMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetUnarmedMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetOffensiveMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetDefensiveMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetGroupOffensiveMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetAvoidThreatChance' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetMeleeMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetRangedMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetMagicMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetShoutMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetStaffMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetUnarmedMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetMeleeAttackStaggeredMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetMeleePowerAttackStaggeredMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetMeleePowerAttackBlockingMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetMeleeBashMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetMeleeBashRecoiledMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetMeleeBashAttackMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetMeleeBashPowerAttackMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetMeleeSpecialAttackMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetAllowDualWielding' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'SetMeleeAttackStaggeredMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetMeleePowerAttackStaggeredMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetMeleePowerAttackBlockingMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetMeleeBashMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetMeleeBashRecoiledMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetMeleeBashAttackMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetMeleeBashPowerAttackMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetMeleeSpecialAttackMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetAllowDualWielding' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'GetCloseRangeDuelingCircleMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetCloseRangeDuelingFallbackMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetCloseRangeFlankingFlankDistance' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetCloseRangeFlankingStalkTime' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetCloseRangeDuelingCircleMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetCloseRangeDuelingFallbackMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetCloseRangeFlankingFlankDistance' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetCloseRangeFlankingStalkTime' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetLongRangeStrafeMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetLongRangeStrafeMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetFlightHoverChance' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetFlightDiveBombChance' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetFlightFlyingAttackChance' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetFlightHoverChance' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetFlightDiveBombChance' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetFlightFlyingAttackChance' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'ConstructibleObject' =>
            array (
                'GetResult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Form',
                    ),
                'SetResult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'void',
                    ),
                'GetResultQuantity' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetResultQuantity' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNumIngredients' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthIngredient' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Form',
                    ),
                'SetNthIngredient' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNthIngredientQuantity' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'SetNthIngredientQuantity' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetWorkbenchKeyword' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Keyword',
                    ),
                'SetWorkbenchKeyword' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Keyword',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'DwarvenMechScript' =>
            array (
                'GetFormIndex' =>
                    array (
                        'args' =>
                            array (
                                0 => 'FormList',
                                1 => 'Form',
                            ),
                        'returnType' => 'int',
                    ),
            ),
        'Enchantment' =>
            array (
                'IsHostile' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'GetNumEffects' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectMagnitude' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'float',
                    ),
                'GetNthEffectArea' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectDuration' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectMagicEffect' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'MagicEffect',
                    ),
                'GetCostliestEffectIndex' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
            ),
        'EquipSlot' =>
            array (
                'GetNumParents' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthParent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'EquipSlot',
                    ),
            ),
        'Flora' =>
            array (
                'GetHarvestSound' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'SoundDescriptor',
                    ),
                'SetHarvestSound' =>
                    array (
                        'args' =>
                            array (
                                0 => 'SoundDescriptor',
                            ),
                        'returnType' => 'void',
                    ),
                'GetIngredient' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Ingredient',
                    ),
                'SetIngredient' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Ingredient',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Form' =>
            array (
                'GetFormID' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'GetGoldValue' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'HasKeyword' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Keyword',
                            ),
                        'returnType' => 'bool',
                    ),
                'PlayerKnows' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'RegisterForAnimationEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'RegisterForLOS' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSingleLOSGain' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSingleLOSLost' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSingleUpdate' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSleep' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForTrackedStatsEvent' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForUpdate' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForUpdateGameTime' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForSingleUpdateGameTime' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'StartObjectProfiling' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'StopObjectProfiling' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAnimationEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForLOS' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForSleep' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForTrackedStatsEvent' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForUpdate' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForUpdateGameTime' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetType' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'GetName' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'string',
                    ),
                'SetName' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'GetWeight' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetWeight' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetGoldValue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNumKeywords' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthKeyword' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Keyword',
                    ),
                'HasKeywordString' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'RegisterForKey' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForKey' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAllKeys' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForControl' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForControl' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAllControls' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForMenu' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForMenu' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAllMenus' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForModEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForModEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForAllModEvents' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'SendModEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForCameraState' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForCameraState' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForCrosshairRef' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForCrosshairRef' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RegisterForActorAction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'UnregisterForActorAction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'TempClone' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Form',
                    ),
            ),
        'FormType' =>
            array (
            ),
        'Game' =>
            array (
                'AddAchievement' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'AddPerkPoints' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'AdvanceSkill' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'AddHavokBallAndSocketConstraint' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'string',
                                2 => 'ObjectReference',
                                3 => 'string',
                                4 => 'float',
                                5 => 'float',
                                6 => 'float',
                                7 => 'float',
                                8 => 'float',
                                9 => 'float',
                            ),
                        'returnType' => 'bool',
                    ),
                'RemoveHavokConstraints' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'string',
                                2 => 'ObjectReference',
                                3 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'CalculateFavorCost' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'ClearPrison' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ClearTempEffects' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'DisablePlayerControls' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                                1 => 'bool',
                                2 => 'bool',
                                3 => 'bool',
                                4 => 'bool',
                                5 => 'bool',
                                6 => 'bool',
                                7 => 'bool',
                                8 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'EnableFastTravel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'FadeOutGame' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                                1 => 'bool',
                                2 => 'float',
                                3 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'FastTravel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'FindClosestReferenceOfType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                                4 => 'float',
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'FindRandomReferenceOfType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                                4 => 'float',
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'FindClosestReferenceOfAnyTypeInList' =>
                    array (
                        'args' =>
                            array (
                                0 => 'FormList',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                                4 => 'float',
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'FindRandomReferenceOfAnyTypeInList' =>
                    array (
                        'args' =>
                            array (
                                0 => 'FormList',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                                4 => 'float',
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'FindClosestReferenceOfTypeFromRef' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'ObjectReference',
                                2 => 'float',
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'FindRandomReferenceOfTypeFromRef' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'ObjectReference',
                                2 => 'float',
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'FindClosestReferenceOfAnyTypeInListFromRef' =>
                    array (
                        'args' =>
                            array (
                                0 => 'FormList',
                                1 => 'ObjectReference',
                                2 => 'float',
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'FindRandomReferenceOfAnyTypeInListFromRef' =>
                    array (
                        'args' =>
                            array (
                                0 => 'FormList',
                                1 => 'ObjectReference',
                                2 => 'float',
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'FindClosestActor' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                            ),
                        'returnType' => 'Actor',
                    ),
                'FindRandomActor' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                            ),
                        'returnType' => 'Actor',
                    ),
                'FindClosestActorFromRef' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'float',
                            ),
                        'returnType' => 'Actor',
                    ),
                'FindRandomActorFromRef' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'float',
                            ),
                        'returnType' => 'Actor',
                    ),
                'ForceThirdPerson' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ForceFirstPerson' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ShowFirstPersonGeometry' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'GetAmountSoldStolen' =>
                    array (
                        'args' => array (),
                        'returnType' => 'int'
                    ),
                'GetForm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Form',
                    ),
                'GetFormFromFile' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'string',
                            ),
                        'returnType' => 'Form',
                    ),
                'GetGameSettingFloat' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'float',
                    ),
                'GetGameSettingInt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'int',
                    ),
                'GetGameSettingString' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'string',
                    ),
                'GetPlayer' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Actor',
                    ),
                'GetPlayerGrabbedRef' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'GetPlayersLastRiddenHorse' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Actor',
                    ),
                'GetSunPositionX' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetSunPositionY' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetSunPositionZ' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetRealHoursPassed' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'IncrementSkill' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'IncrementSkillBy' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'IncrementStat' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'IsActivateControlsEnabled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsCamSwitchControlsEnabled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsFastTravelControlsEnabled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsFastTravelEnabled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsFightingControlsEnabled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsJournalControlsEnabled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsLookingControlsEnabled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsMenuControlsEnabled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsMovementControlsEnabled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsPCAMurderer' =>
                    array (
                        'args' => array (),
                        'returnType' => 'int'
                    ),
                'IsPlayerSungazing' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsSneakingControlsEnabled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsWordUnlocked' =>
                    array (
                        'args' =>
                            array (
                                0 => 'WordOfPower',
                            ),
                        'returnType' => 'bool',
                    ),
                'PrecacheCharGen' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'PrecacheCharGenClear' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'QueryStat' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'int',
                    ),
                'QuitToMainMenu' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RequestAutoSave' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RequestModel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'RequestSave' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ServeTime' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'SendWereWolfTransformation' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'SetBeastForm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetCameraTarget' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'void',
                    ),
                'SetHudCartMode' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetInChargen' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                                1 => 'bool',
                                2 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetPlayerAIDriven' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetPlayerReportCrime' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetSittingRotation' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ShakeCamera' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'float',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ShakeController' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ShowRaceMenu' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ShowLimitedRaceMenu' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ShowTitleSequenceMenu' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'HideTitleSequenceMenu' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'StartTitleSequence' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'SetAllowFlyingMountLandingRequests' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetSunGazeImageSpaceModifier' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ImageSpaceModifier',
                            ),
                        'returnType' => 'void',
                    ),
                'ShowTrainingMenu' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'void',
                    ),
                'TeachWord' =>
                    array (
                        'args' =>
                            array (
                                0 => 'WordOfPower',
                            ),
                        'returnType' => 'void',
                    ),
                'TriggerScreenBlood' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'UnlockWord' =>
                    array (
                        'args' =>
                            array (
                                0 => 'WordOfPower',
                            ),
                        'returnType' => 'void',
                    ),
                'UsingGamepad' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'GetPerkPoints' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetPerkPoints' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'ModPerkPoints' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetModCount' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetModByName' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'int',
                    ),
                'GetModName' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'string',
                    ),
                'GetModAuthor' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'string',
                    ),
                'GetModDescription' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'string',
                    ),
                'GetModDependencyCount' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthModDependency' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'SetGameSettingFloat' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetGameSettingInt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetGameSettingBool' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetGameSettingString' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'SaveGame' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'LoadGame' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNumTintMasks' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthTintMaskColor' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthTintMaskType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'SetNthTintMaskColor' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNthTintMaskTexturePath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'string',
                    ),
                'SetNthTintMaskTexturePath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNumTintsByType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetTintMaskColor' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'SetTintMaskColor' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                                2 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetTintMaskTexturePath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'string',
                    ),
                'SetTintMaskTexturePath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'int',
                                2 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'UpdateTintMaskColors' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UpdateHairColor' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetCameraState' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetMiscStat' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetPlayersLastRiddenHorse' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'void',
                    ),
                'GetSkillLegendaryLevel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'int',
                    ),
                'SetSkillLegendaryLevel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetPlayerMovementMode' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'UpdateThirdPerson' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnbindObjectHotkey' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetHotkeyBoundObject' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Form',
                    ),
                'IsObjectFavorited' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'bool',
                    ),
            ),
        'HeadPart' =>
            array (
                'GetHeadPart' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'HeadPart',
                    ),
                'GetType' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNumExtraParts' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthExtraPart' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'HeadPart',
                    ),
                'HasExtraPart' =>
                    array (
                        'args' =>
                            array (
                                0 => 'HeadPart',
                            ),
                        'returnType' => 'bool',
                    ),
                'GetIndexOfExtraPart' =>
                    array (
                        'args' =>
                            array (
                                0 => 'HeadPart',
                            ),
                        'returnType' => 'int',
                    ),
                'GetValidRaces' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'FormList',
                    ),
                'SetValidRaces' =>
                    array (
                        'args' =>
                            array (
                                0 => 'FormList',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Ingredient' =>
            array (
                'IsHostile' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'LearnEffect' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'LearnNextEffect' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'LearnAllEffects' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetNumEffects' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectMagnitude' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'float',
                    ),
                'GetNthEffectArea' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectDuration' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectMagicEffect' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'MagicEffect',
                    ),
                'GetCostliestEffectIndex' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetNthEffectMagnitude' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetNthEffectArea' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetNthEffectDuration' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Input' =>
            array (
                'IsKeyPressed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'bool',
                    ),
                'TapKey' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'HoldKey' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'ReleaseKey' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNumKeysPressed' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthKeyPressed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetMappedKey' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetMappedControl' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'string',
                    ),
            ),
        'Keyword' =>
            array (
                'GetKeyword' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'Keyword',
                    ),
                'GetString' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'string',
                    ),
            ),
        'Math' =>
            array (
                'abs' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'float',
                    ),
                'acos' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'float',
                    ),
                'asin' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'float',
                    ),
                'atan' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'float',
                    ),
                'Ceiling' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'int',
                    ),
                'cos' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'float',
                    ),
                'DegreesToRadians' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'float',
                    ),
                'Floor' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'int',
                    ),
                'pow' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                            ),
                        'returnType' => 'float',
                    ),
                'RadiansToDegrees' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'float',
                    ),
                'sin' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'float',
                    ),
                'sqrt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'float',
                    ),
                'tan' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'float',
                    ),
                'LeftShift' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'RightShift' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'LogicalAnd' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'LogicalOr' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'LogicalXor' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'LogicalNot' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
            ),
        'ObjectReference' =>
            array (
                'rampRumble' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsNearPlayer' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsInInterior' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'MoveToIfUnloaded' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                            ),
                        'returnType' => 'bool',
                    ),
                'MoveToWhenUnloaded' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'finishes' =>
                    array (
                        'args' =>
                            array (
                                0 => 'and',
                                1 => 'the',
                                2 => '1',
                            ),
                        'returnType' => 'this',
                    ),
                'DeleteWhenAble' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'AddKeyIfNeeded' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'get' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'Activate' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'bool',
                            ),
                        'returnType' => 'bool',
                    ),
                'AddDependentAnimatedObjectReference' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'bool',
                    ),
                'AddInventoryEventFilter' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'void',
                    ),
                'AddItem' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'int',
                                2 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'AddToMap' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'ApplyHavokImpulse' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'BlockActivation' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'CalculateEncounterLevel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'CanFastTravelToMarker' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'ClearDestruction' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'CreateDetectionEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'DamageObject' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'Delete' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'Disable' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'DisableNoWait' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'DropObject' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'int',
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'Enable' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'EnableFastTravel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'EnableNoWait' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'ForceAddRagdollToWorld' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ForceRemoveRagdollFromWorld' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetActorOwner' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'ActorBase',
                    ),
                'GetAngleX' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetAngleY' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetAngleZ' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetAnimationVariableBool' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'GetAnimationVariableInt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'int',
                    ),
                'GetAnimationVariableFloat' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'float',
                    ),
                'GetBaseObject' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Form',
                    ),
                'GetCurrentDestructionStage' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetCurrentLocation' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Location',
                    ),
                'GetCurrentScene' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Scene',
                    ),
                'GetDestroyed' =>
                    array (
                        'args' =>
                            array (),
                        'returnType' => 'int'
                    ),
                'GetDistance' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'float',
                    ),
                'GetEditorLocation' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Location',
                    ),
                'GetEnableParent' => //Only SKSE function atm used
                    array (
                        'args' =>
                            array (

                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'GetFactionOwner' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Faction',
                    ),
                'GetHeadingAngle' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'float',
                    ),
                'GetHeight' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetItemCount' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'int',
                    ),
                'GetItemHealthPercent' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetKey' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Key',
                    ),
                'GetLength' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetLinkedRef' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Keyword',
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'GetLockLevel' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'countLinkedRefChain' =>
                    array (
                        'args' =>
                            array (
                                0 => 'keyword',
                                1 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthLinkedRef' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'GetStartingAngle' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'float',
                    ),
                'GetStartingPos' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'float',
                    ),
                'EnableLinkChain' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Keyword',
                            ),
                        'returnType' => 'void',
                    ),
                'DisableLinkChain' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Keyword',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'GetMass' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetOpenState' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetParentCell' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Cell',
                    ),
                'GetPositionX' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetPositionY' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetPositionZ' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetScale' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetTriggerObjectCount' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetVoiceType' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'VoiceType',
                    ),
                'GetWidth' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetWorldSpace' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'WorldSpace',
                    ),
                'GetSelfAsActor' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'actor',
                    ),
                'HasEffectKeyword' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Keyword',
                            ),
                        'returnType' => 'bool',
                    ),
                'HasNode' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'HasRefType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'LocationRefType',
                            ),
                        'returnType' => 'bool',
                    ),
                'IgnoreFriendlyHits' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'InterruptCast' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'IsActivateChild' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsActivationBlocked' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'Is3DLoaded' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsDeleted' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsDisabled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsEnabled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsFurnitureInUse' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsFurnitureMarkerInUse' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'bool',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsIgnoringFriendlyHits' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsInDialogueWithPlayer' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsLockBroken' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsLocked' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsMapMarkerVisible' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'KnockAreaEffect' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'Lock' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'MoveTo' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                                4 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'MoveToInteractionLocation' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'MoveToMyEditorLocation' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'MoveToNode' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'PlaceAtMe' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'int',
                                2 => 'bool',
                                3 => 'bool',
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'PlaceActorAtMe' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ActorBase',
                                1 => 'int',
                                2 => 'EncounterZone',
                            ),
                        'returnType' => 'Actor',
                    ),
                'PlayAnimation' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'PlayAnimationAndWait' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'PlayGamebryoAnimation' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'bool',
                                2 => 'float',
                            ),
                        'returnType' => 'bool',
                    ),
                'PlayImpactEffect' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ImpactDataSet',
                                1 => 'string',
                                2 => 'float',
                                3 => 'float',
                                4 => 'float',
                                5 => 'float',
                                6 => 'bool',
                                7 => 'bool',
                            ),
                        'returnType' => 'bool',
                    ),
                'PlaySyncedAnimationSS' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'ObjectReference',
                                2 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'PlaySyncedAnimationAndWaitSS' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'ObjectReference',
                                3 => 'string',
                                4 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'PlayTerrainEffect' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'ProcessTrapHit' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                                4 => 'float',
                                5 => 'float',
                                6 => 'float',
                                7 => 'float',
                                8 => 'float',
                                9 => 'int',
                                10 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'PushActorAway' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'RemoveAllInventoryEventFilters' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RemoveAllItems' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'bool',
                                2 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'RemoveInventoryEventFilter' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'void',
                    ),
                'RemoveItem' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'int',
                                2 => 'bool',
                                3 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'RemoveDependentAnimatedObjectReference' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'bool',
                    ),
                'Reset' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'Say' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Topic',
                                1 => 'Actor',
                                2 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SendStealAlarm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'void',
                    ),
                'SetActorCause' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'void',
                    ),
                'SetActorOwner' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ActorBase',
                            ),
                        'returnType' => 'void',
                    ),
                'SetAngle' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetAnimationVariableBool' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetAnimationVariableInt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetAnimationVariableFloat' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetDestroyed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetFactionOwner' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                            ),
                        'returnType' => 'void',
                    ),
                'SetLockLevel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetMotionType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetNoFavorAllowed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetOpen' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetPosition' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetScale' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'TranslateTo' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                                4 => 'float',
                                5 => 'float',
                                6 => 'float',
                                7 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SplineTranslateTo' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                                4 => 'float',
                                5 => 'float',
                                6 => 'float',
                                7 => 'float',
                                8 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SplineTranslateToRefNode' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'string',
                                2 => 'float',
                                3 => 'float',
                                4 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'StopTranslation' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'TranslateToRef' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'float',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SplineTranslateToRef' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'float',
                                2 => 'float',
                                3 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'TetherToHorse' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'WaitForAnimationEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsInLocation' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Location',
                            ),
                        'returnType' => 'bool',
                    ),
                'GetNumItems' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthForm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Form',
                    ),
                'GetTotalItemWeight' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetTotalArmorWeight' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'IsHarvested' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'SetHarvested' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetItemHealthPercent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetItemMaxCharge' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetItemCharge' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetItemCharge' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'ResetInventory' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'IsOffLimits' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'isAnimPlaying' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
            ),
        'Outfit' =>
            array (
                'GetNumParts' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthPart' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Form',
                    ),
            ),
        'Perk' =>
            array (
                'GetNumEntries' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEntryRank' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'SetNthEntryRank' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'bool',
                    ),
                'GetNthEntryPriority' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'SetNthEntryPriority' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'bool',
                    ),
                'GetNthEntryQuest' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Quest',
                    ),
                'SetNthEntryQuest' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'Quest',
                            ),
                        'returnType' => 'bool',
                    ),
                'GetNthEntryStage' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'SetNthEntryStage' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'bool',
                    ),
                'GetNthEntrySpell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Spell',
                    ),
                'SetNthEntrySpell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'Spell',
                            ),
                        'returnType' => 'bool',
                    ),
                'GetNthEntryLeveledList' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'LeveledItem',
                    ),
                'SetNthEntryLeveledList' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'LeveledItem',
                            ),
                        'returnType' => 'bool',
                    ),
                'GetNthEntryText' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'string',
                    ),
                'SetNthEntryText' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'GetNthEntryValue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'float',
                    ),
                'SetNthEntryValue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                                2 => 'float',
                            ),
                        'returnType' => 'bool',
                    ),
            ),
        'Potion' =>
            array (
                'IsHostile' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsFood' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'GetNumEffects' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectMagnitude' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'float',
                    ),
                'GetNthEffectArea' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectDuration' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectMagicEffect' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'MagicEffect',
                    ),
                'GetCostliestEffectIndex' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetNthEffectMagnitude' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetNthEffectArea' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetNthEffectDuration' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Quest' =>
            array (
                'ModObjectiveGlobal' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'GlobalVariable',
                                2 => 'int',
                                3 => 'float',
                                4 => 'bool',
                                5 => 'bool',
                                6 => 'bool',
                            ),
                        'returnType' => 'bool',
                    ),
                'CompleteAllObjectives' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'CompleteQuest' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'FailAllObjectives' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetAlias' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Alias',
                    ),
                'GetCurrentStageID' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetStage' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetStageDone' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsActive' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsCompleted' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsObjectiveCompleted' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsObjectiveDisplayed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsObjectiveFailed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsRunning' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsStageDone' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsStarting' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsStopping' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsStopped' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'PrepareForReinitializing' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),										
                'Reset' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'SetActive' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetCurrentStageID' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'bool',
                    ),
                'SetObjectiveCompleted' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetObjectiveDisplayed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'bool',
                                2 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetObjectiveFailed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetStage' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'bool',
                    ),
                'Start' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'Stop' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UpdateCurrentInstanceGlobal' =>
                    array (
                        'args' =>
                            array (
                                0 => 'GlobalVariable',
                            ),
                        'returnType' => 'bool',
                    ),
                'GetQuest' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'Quest',
                    ),
                'GetID' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'string',
                    ),
                'GetPriority' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNumAliases' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthAlias' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Alias',
                    ),
                'GetAliasByName' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'Alias',
                    ),
            ),
        'Race' =>
            array (
                'GetSpellCount' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthSpell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Spell',
                    ),
                'IsRaceFlagSet' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'bool',
                    ),
                'SetRaceFlag' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'ClearRaceFlag' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetDefaultVoiceType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'VoiceType',
                    ),
                'SetDefaultVoiceType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                                1 => 'VoiceType',
                            ),
                        'returnType' => 'void',
                    ),
                'GetSkin' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Armor',
                    ),
                'SetSkin' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Armor',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNumPlayableRaces' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthPlayableRace' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Race',
                    ),
                'GetRace' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'Race',
                    ),
                'IsPlayable' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'MakePlayable' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'MakeUnplayable' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'IsChildRace' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'MakeChildRace' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'MakeNonChildRace' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'CanFly' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'MakeCanFly' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'MakeNonFlying' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'CanSwim' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'MakeCanSwim' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'MakeNonSwimming' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'CanWalk' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'MakeCanWalk' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'MakeNonWalking' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'IsImmobile' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'MakeImmobile' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'MakeMobile' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'IsNotPushable' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'MakeNotPushable' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'MakePushable' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'NoKnockdowns' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'MakeNoKnockdowns' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ClearNoKNockdowns' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'NoCombatInWater' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'SetNoCombatInWater' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ClearNoCombatInWater' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'AvoidsRoads' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'SetAvoidsRoads' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ClearAvoidsRoads' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'AllowPickpocket' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'SetAllowPickpocket' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ClearAllowPickpocket' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'AllowPCDialogue' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'SetAllowPCDialogue' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ClearAllowPCDialogue' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'CantOpenDoors' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'SetCantOpenDoors' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ClearCantOpenDoors' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'NoShadow' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'SetNoShadow' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ClearNoShadow' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Scroll' =>
            array (
                'Cast' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'GetCastTime' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetPerk' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Perk',
                    ),
                'GetNumEffects' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectMagnitude' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'float',
                    ),
                'GetNthEffectArea' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectDuration' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectMagicEffect' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'MagicEffect',
                    ),
                'GetCostliestEffectIndex' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetNthEffectMagnitude' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetNthEffectArea' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetNthEffectDuration' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetEquipType' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'EquipSlot',
                    ),
                'SetEquipType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'EquipSlot',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Shout' =>
            array (
                'GetNthWordOfPower' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'WordOfPower',
                    ),
                'GetNthSpell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Spell',
                    ),
                'GetNthRecoveryTime' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'float',
                    ),
                'SetNthWordOfPower' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'WordOfPower',
                            ),
                        'returnType' => 'void',
                    ),
                'SetNthSpell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'Spell',
                            ),
                        'returnType' => 'void',
                    ),
                'SetNthRecoveryTime' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'SKSE' =>
            array (
                'GetVersion' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetVersionMinor' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetVersionBeta' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetVersionRelease' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetScriptVersionRelease' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetPluginVersion' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'int',
                    ),
            ),
        'SoulGem' =>
            array (
                'GetSoulSize' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetGemSize' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
            ),
        'Sound' =>
            array (
                'Play' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'int',
                    ),
                'PlayAndWait' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'bool',
                    ),
                'StopInstance' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetInstanceVolume' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetDescriptor' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'SoundDescriptor',
                    ),
            ),
        'SoundDescriptor' =>
            array (
                'GetDecibelAttenuation' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetDecibelAttenuation' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetDecibelVariance' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetDecibelVariance' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetFrequencyVariance' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetFrequencyVariance' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetFrequencyShift' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetFrequencyShift' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Spell' =>
            array (
                'Cast' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'RemoteCast' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'Actor',
                                2 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'IsHostile' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'Preload' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'Unload' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetCastTime' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetPerk' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Perk',
                    ),
                'GetNumEffects' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectMagnitude' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'float',
                    ),
                'GetNthEffectArea' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectDuration' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthEffectMagicEffect' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'MagicEffect',
                    ),
                'GetCostliestEffectIndex' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetMagickaCost' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetEffectiveMagickaCost' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Actor',
                            ),
                        'returnType' => 'int',
                    ),
                'SetNthEffectMagnitude' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetNthEffectArea' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetNthEffectDuration' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetEquipType' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'EquipSlot',
                    ),
                'SetEquipType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'EquipSlot',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'StringUtil' =>
            array (
                'GetLength' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthChar' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'int',
                            ),
                        'returnType' => 'string',
                    ),
                'IsLetter' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsDigit' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsPunctuation' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'IsPrintable' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'Find' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'Substring' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'int',
                                2 => 'int',
                            ),
                        'returnType' => 'string',
                    ),
                'AsOrd' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'int',
                    ),
                'AsChar' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'string',
                    ),
            ),
        'TextureSet' =>
            array (
                'GetNumTexturePaths' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetNthTexturePath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'string',
                    ),
                'SetNthTexturePath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'UI' =>
            array (
                'IsMenuOpen' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'SetBool' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetInt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetFloat' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetString' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'SetNumber' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetBool' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'GetInt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'GetFloat' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'GetString' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNumber' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'Invoke' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'InvokeBool' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'InvokeInt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'InvokeFloat' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'InvokeString' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'InvokeNumber' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'InvokeBoolA' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'bool[]',
                            ),
                        'returnType' => 'void',
                    ),
                'InvokeIntA' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'int[]',
                            ),
                        'returnType' => 'void',
                    ),
                'InvokeFloatA' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'float[]',
                            ),
                        'returnType' => 'void',
                    ),
                'InvokeStringA' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'string[]',
                            ),
                        'returnType' => 'void',
                    ),
                'InvokeNumberA' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'float[]',
                            ),
                        'returnType' => 'void',
                    ),
                'InvokeForm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                                2 => 'Form',
                            ),
                        'returnType' => 'void',
                    ),
                'IsTextInputEnabled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
            ),
        'Utility' =>
            array (
                'GameTimeToString' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'string',
                    ),
                'GetCurrentGameTime' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetCurrentRealTime' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'IsInMenuMode' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'RandomInt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                                1 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'RandomFloat' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                                1 => 'float',
                            ),
                        'returnType' => 'float',
                    ),
                'SetINIFloat' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetINIInt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetINIBool' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetINIString' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                                1 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'Wait' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'WaitGameTime' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'WaitMenuMode' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'CaptureFrameRate' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'string',
                    ),
                'StartFrameRateCapture' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'EndFrameRateCapture' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetAverageFrameRate' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetMinFrameRate' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetMaxFrameRate' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetCurrentMemory' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetBudgetCount' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetCurrentBudget' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'int',
                    ),
                'OverBudget' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'bool',
                    ),
                'GetBudgetName' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'string',
                    ),
                'GetINIFloat' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'float',
                    ),
                'GetINIInt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'int',
                    ),
                'GetINIBool' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'bool',
                    ),
                'GetINIString' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'string',
                    ),
            ),
        'Weapon' =>
            array (
                'Fire' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'Ammo',
                            ),
                        'returnType' => 'void',
                    ),
                'GetBaseDamage' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetBaseDamage' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetCritDamage' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetCritDamage' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetReach' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetReach' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetMinRange' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetMinRange' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetMaxRange' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetMaxRange' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetSpeed' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetSpeed' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetStagger' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetStagger' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetWeaponType' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetWeaponType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetModelPath' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'string',
                    ),
                'SetModelPath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'GetIconPath' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'string',
                    ),
                'SetIconPath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'GetMessageIconPath' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'string',
                    ),
                'SetMessageIconPath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'GetEnchantment' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Enchantment',
                    ),
                'SetEnchantment' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Enchantment',
                            ),
                        'returnType' => 'void',
                    ),
                'GetEnchantmentValue' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'SetEnchantmentValue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetEquippedModel' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Static',
                    ),
                'SetEquippedModel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Static',
                            ),
                        'returnType' => 'void',
                    ),
                'GetEquipType' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'EquipSlot',
                    ),
                'SetEquipType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'EquipSlot',
                            ),
                        'returnType' => 'void',
                    ),
                'GetSkill' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'string',
                    ),
                'SetSkill' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'GetResist' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'string',
                    ),
                'SetResist' =>
                    array (
                        'args' =>
                            array (
                                0 => 'string',
                            ),
                        'returnType' => 'void',
                    ),
                'GetCritEffect' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Spell',
                    ),
                'SetCritEffect' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Spell',
                            ),
                        'returnType' => 'void',
                    ),
                'GetCritEffectOnDeath' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'SetCritEffectOnDeath' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'GetCritMultiplier' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'SetCritMultiplier' =>
                    array (
                        'args' =>
                            array (
                                0 => 'float',
                            ),
                        'returnType' => 'void',
                    ),
                'IsBattleaxe' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsBow' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsDagger' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsGreatsword' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsMace' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsStaff' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsSword' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsWarhammer' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
                'IsWarAxe' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'bool',
                    ),
            ),
        'Weather' =>
            array (
                'ReleaseOverride' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ForceActive' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetActive' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                                1 => 'bool',
                            ),
                        'returnType' => 'void',
                    ),
                'FindWeather' =>
                    array (
                        'args' =>
                            array (
                                0 => 'int',
                            ),
                        'returnType' => 'Weather',
                    ),
                'GetClassification' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetCurrentWeather' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Weather',
                    ),
                'GetOutgoingWeather' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Weather',
                    ),
                'GetCurrentWeatherTransition' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetSkyMode' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'int',
                    ),
                'GetSunGlare' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetSunDamage' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetWindDirection' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetWindDirectionRange' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'float',
                    ),
                'GetFogDistance' =>
                    array (
                        'args' =>
                            array (
                                0 => 'bool',
                                1 => 'int',
                            ),
                        'returnType' => 'float',
                    ),
            ),

        'ReferenceAlias' =>
            array (
                'AddInventoryEventFilter' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'void',
                    ),
                'Clear' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ForceRefIfEmpty' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'Bool',
                    ),
                'ForceRefTo' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'GetActorRef' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Actor',
                    ),
                'GetActorReference' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Actor',
                    ),
                'GetRef' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'GetReference' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'ObjectReference',
                    ),
                'RemoveAllInventoryEventFilters' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'RemoveInventoryEventFilter' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'void',
                    ),
                'TryToAddToFaction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                            ),
                        'returnType' => 'Bool',
                    ),
                'TryToClear' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Bool',
                    ),
                'TryToDisable' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Bool',
                    ),
                'TryToDisableNoWait' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Bool',
                    ),
                'TryToEnable' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Bool',
                    ),
                'TryToEnableNoWait' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Bool',
                    ),
                'TryToEvaluatePackage' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Bool',
                    ),
                'TryToKill' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Bool',
                    ),
                'TryToMoveTo' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'Bool',
                    ),
                'TryToRemoveFromFaction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                            ),
                        'returnType' => 'Bool',
                    ),
                'TryToReset' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Bool',
                    ),
                'TryToStopCombat' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Bool',
                    ),
            ),
        'LocationAlias' =>
            array (
                'Clear' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetLocation' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Location',
                    ),
                'ForceLocationTo' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Location',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Debug' =>
            array (
                'CenterOnCell' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                            ),
                        'returnType' => 'void',
                    ),
                'CenterOnCellAndWait' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                            ),
                        'returnType' => 'Float',
                    ),
                'PlayerMoveToAndWait' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                            ),
                        'returnType' => 'Float',
                    ),
                'CloseUserLog' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                            ),
                        'returnType' => 'void',
                    ),
                'DumpAliasData' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Quest',
                            ),
                        'returnType' => 'void',
                    ),
                'GetConfigName' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'String',
                    ),
                'GetPlatformName' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'String',
                    ),
                'GetVersionNumber' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'String',
                    ),
                'MessageBox' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                            ),
                        'returnType' => 'void',
                    ),
                'Notification' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                            ),
                        'returnType' => 'void',
                    ),
                'OpenUserLog' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                            ),
                        'returnType' => 'Bool',
                    ),
                'QuitGame' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'SetFootIK' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetGodMode' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SendAnimationEvent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'String',
                            ),
                        'returnType' => 'void',
                    ),
                'StartScriptProfiling' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                            ),
                        'returnType' => 'void',
                    ),
                'StartStackProfiling' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'StopScriptProfiling' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                            ),
                        'returnType' => 'void',
                    ),
                'StopStackProfiling' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ToggleAI' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ToggleCollisions' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'ToggleMenus' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'Trace' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                                1 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'TraceAndBox' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                                1 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'TraceConditional' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                                1 => 'Bool',
                            ),
                        'returnType' => 'void',
                    ),
                'TraceStack' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                                1 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'TraceUser' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                                1 => 'String',
                                2 => 'Int',
                            ),
                        'returnType' => 'Bool',
                    ),
            ),
        'Action' =>
            array (
            ),
        'MagicEffect' =>
            array (
                'GetAssociatedSkill' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'String',
                    ),
                'SetAssociatedSkill' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                            ),
                        'returnType' => 'void',
                    ),
                'GetResistance' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'String',
                    ),
                'SetResistance' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                            ),
                        'returnType' => 'void',
                    ),
                'IsEffectFlagSet' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'Bool',
                    ),
                'SetEffectFlag' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'ClearEffectFlag' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetCastTime' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Float',
                    ),
                'SetCastTime' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetSkillLevel' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'SetSkillLevel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetArea' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'SetArea' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetSkillUsageMult' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Float',
                    ),
                'SetSkillUsageMult' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetBaseCost' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Float',
                    ),
                'SetBaseCost' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Float',
                            ),
                        'returnType' => 'void',
                    ),
                'GetLight' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Light',
                    ),
                'SetLight' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Light',
                            ),
                        'returnType' => 'void',
                    ),
                'GetHitShader' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'EffectShader',
                    ),
                'SetHitShader' =>
                    array (
                        'args' =>
                            array (
                                0 => 'EffectShader',
                            ),
                        'returnType' => 'void',
                    ),
                'GetEnchantShader' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'EffectShader',
                    ),
                'SetEnchantShader' =>
                    array (
                        'args' =>
                            array (
                                0 => 'EffectShader',
                            ),
                        'returnType' => 'void',
                    ),
                'GetProjectile' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Projectile',
                    ),
                'SetProjectile' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Projectile',
                            ),
                        'returnType' => 'void',
                    ),
                'GetExplosion' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Explosion',
                    ),
                'SetExplosion' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Explosion',
                            ),
                        'returnType' => 'void',
                    ),
                'GetCastingArt' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Art',
                    ),
                'SetCastingArt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Art',
                            ),
                        'returnType' => 'void',
                    ),
                'GetHitEffectArt' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Art',
                    ),
                'SetHitEffectArt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Art',
                            ),
                        'returnType' => 'void',
                    ),
                'GetEnchantArt' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Art',
                    ),
                'SetEnchantArt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Art',
                            ),
                        'returnType' => 'void',
                    ),
                'GetImpactDataSet' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'ImpactDataSet',
                    ),
                'SetImpactDataSet' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ImpactDataSet',
                            ),
                        'returnType' => 'void',
                    ),
                'GetEquipAbility' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Spell',
                    ),
                'SetEquipAbility' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Spell',
                            ),
                        'returnType' => 'void',
                    ),
                'GetImageSpaceMod' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'ImageSpaceModifier',
                    ),
                'SetImageSpaceMod' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ImageSpaceModifier',
                            ),
                        'returnType' => 'void',
                    ),
                'GetPerk' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Perk',
                    ),
                'SetPerk' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Perk',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Furniture' =>
            array (
            ),
        'TalkingActivator' =>
            array (
            ),
        'Activator' =>
            array (
            ),
        'Message' =>
            array (
                'ResetHelpMessage' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                            ),
                        'returnType' => 'void',
                    ),
                'Show' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Float',
                                1 => 'Float',
                                2 => 'Float',
                                3 => 'Float',
                                4 => 'Float',
                                5 => 'Float',
                                6 => 'Float',
                                7 => 'Float',
                                8 => 'Float',
                            ),
                        'returnType' => 'Int',
                    ),
                'ShowAsHelpMessage' =>
                    array (
                        'args' =>
                            array (
                                0 => 'String',
                                1 => 'Float',
                                2 => 'Float',
                                3 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Key' =>
            array (
            ),
        'MiscObject' =>
            array (
            ),
        'Ammo' =>
            array (
            ),
        'AssociationType' =>
            array (
            ),
        'MusicType' =>
            array (
                'Add' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'Remove' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Class' =>
            array (
            ),
        'Package' =>
            array (
                'GetOwningQuest' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Quest',
                    ),
                'GetTemplate' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Package',
                    ),
            ),
        'Container' =>
            array (
            ),
        'Door' =>
            array (
            ),
        'EffectShader' =>
            array (
            ),
        'Projectile' =>
            array (
            ),
        'EncounterZone' =>
            array (
            ),
        'Scene' =>
            array (
                'ForceStart' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetOwningQuest' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Quest',
                    ),
                'IsActionComplete' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'Bool',
                    ),
                'IsPlaying' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Bool',
                    ),
                'Start' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'Stop' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Explosion' =>
            array (
            ),
        'Faction' =>
            array (
                'CanPayCrimeGold' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Bool',
                    ),
                'GetCrimeGold' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'GetCrimeGoldNonViolent' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'GetCrimeGoldViolent' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'GetInfamy' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'GetInfamyNonViolent' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'GetInfamyViolent' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'GetReaction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                            ),
                        'returnType' => 'Int',
                    ),
                'GetStolenItemValueCrime' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'GetStolenItemValueNoCrime' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'IsFactionInCrimeGroup' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                            ),
                        'returnType' => 'Bool',
                    ),
                'IsPlayerExpelled' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Bool',
                    ),
                'ModCrimeGold' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                                1 => 'Bool',
                            ),
                        'returnType' => 'void',
                    ),
                'ModReaction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                                1 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'PlayerPayCrimeGold' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Bool',
                                1 => 'Bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SendAssaultAlarm' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'SendPlayerToJail' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Bool',
                                1 => 'Bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetCrimeGold' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetCrimeGoldViolent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'SetEnemy' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                                1 => 'Bool',
                                2 => 'Bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetPlayerEnemy' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetPlayerExpelled' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetReaction' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Faction',
                                1 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'FormList' =>
            array (
                'AddForm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'void',
                    ),
                'Find' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'Int',
                    ),
                'GetAt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'Form',
                    ),
                'GetSize' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'HasForm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'Bool',
                    ),
                'RemoveAddedForm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                            ),
                        'returnType' => 'void',
                    ),
                'Revert' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'GlobalVariable' =>
            array (
                'GetValue' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Float',
                    ),
                'GetValueInt' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'Mod' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Float',
                            ),
                        'returnType' => 'Float',
                    ),
                'SetValue' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetValueInt' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Hazard' =>
            array (
            ),
        'SoundCategory' =>
            array (
                'Mute' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'Pause' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'SetFrequency' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Float',
                            ),
                        'returnType' => 'void',
                    ),
                'SetVolume' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Float',
                            ),
                        'returnType' => 'void',
                    ),
                'UnMute' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'UnPause' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Idle' =>
            array (
            ),
        'ImageSpaceModifier' =>
            array (
                'RemoveCrossFade' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Float',
                            ),
                        'returnType' => 'void',
                    ),
                'Apply' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Float',
                            ),
                        'returnType' => 'void',
                    ),
                'ApplyCrossFade' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Float',
                            ),
                        'returnType' => 'void',
                    ),
                'PopTo' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ImageSpaceModifier',
                                1 => 'Float',
                            ),
                        'returnType' => 'void',
                    ),
                'Remove' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Static' =>
            array (
            ),
        'ImpactDataSet' =>
            array (
            ),
        'Topic' =>
            array (
                'Add' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'LocationRefType' =>
            array (
            ),
        'TopicInfo' =>
            array (
                'GetOwningQuest' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Quest',
                    ),
            ),
        'LeveledActor' =>
            array (
                'AddForm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'Revert' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetNumForms' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'GetNthForm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'Form',
                    ),
                'GetNthLevel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'Int',
                    ),
                'SetNthLevel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                                1 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNthCount' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'Int',
                    ),
                'SetNthCount' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                                1 => 'Int',
                            ),
                        'returnType' => 'Int',
                    ),
            ),
        'VisualEffect' =>
            array (
                'Play' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                                1 => 'Float',
                                2 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
                'Stop' =>
                    array (
                        'args' =>
                            array (
                                0 => 'ObjectReference',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'LeveledItem' =>
            array (
                'AddForm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'Int',
                                2 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'Revert' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'void',
                    ),
                'GetChanceNone' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'SetChanceNone' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetChanceGlobal' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'GlobalVariable',
                    ),
                'SetChanceGlobal' =>
                    array (
                        'args' =>
                            array (
                                0 => 'GlobalVariable',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNumForms' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'GetNthForm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'Form',
                    ),
                'GetNthLevel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'Int',
                    ),
                'SetNthLevel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                                1 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNthCount' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'Int',
                    ),
                'SetNthCount' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                                1 => 'Int',
                            ),
                        'returnType' => 'Int',
                    ),
            ),
        'VoiceType' =>
            array (
            ),
        'LeveledSpell' =>
            array (
                'AddForm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Form',
                                1 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetChanceNone' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'SetChanceNone' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
                'GetNumForms' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Int',
                    ),
                'GetNthForm' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'Form',
                    ),
                'GetNthLevel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                            ),
                        'returnType' => 'Int',
                    ),
                'SetNthLevel' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Int',
                                1 => 'Int',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'Light' =>
            array (
            ),
        'Location' =>
            array (
                'GetKeywordData' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Keyword',
                            ),
                        'returnType' => 'Float',
                    ),
                'GetRefTypeAliveCount' =>
                    array (
                        'args' =>
                            array (
                                0 => 'LocationRefType',
                            ),
                        'returnType' => 'Int',
                    ),
                'GetRefTypeDeadCount' =>
                    array (
                        'args' =>
                            array (
                                0 => 'LocationRefType',
                            ),
                        'returnType' => 'Int',
                    ),
                'HasCommonParent' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Location',
                                1 => 'Keyword',
                            ),
                        'returnType' => 'Bool',
                    ),
                'HasRefType' =>
                    array (
                        'args' =>
                            array (
                                0 => 'LocationRefType',
                            ),
                        'returnType' => 'Bool',
                    ),
                'IsCleared' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Bool',
                    ),
                'IsChild' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Location',
                            ),
                        'returnType' => 'Bool',
                    ),
                'IsLoaded' =>
                    array (
                        'args' =>
                            array (
                            ),
                        'returnType' => 'Bool',
                    ),
                'IsSameLocation' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Location',
                                1 => 'Keyword',
                            ),
                        'returnType' => 'Bool',
                    ),
                'SetCleared' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Bool',
                            ),
                        'returnType' => 'void',
                    ),
                'SetKeywordData' =>
                    array (
                        'args' =>
                            array (
                                0 => 'Keyword',
                                1 => 'Float',
                            ),
                        'returnType' => 'void',
                    ),
            ),
        'WordOfPower' =>
            array (
            ),
        'WorldSpace' =>
            array (
            ),

        //Conversion hooks
        'TES4TimerHelper' =>
            array (
                'GetDayOfWeek' => array(
                    'args' => array(),
                    'returnType' => 'int'
                ),
                
                'GetSecondsPassed' => array(
                    'args' => array(
                        0 => 'Float'
                    ),
                    'returnType' => 'Float'
                ),
                'Rotate' => array(
                    'args' => array(
                        0 => 'ObjectReference',
                        1 => 'Float',
                        2 => 'Float',
                        3 => 'Float',
                    ),
                    'returnType' => 'void'
                ),
                'LegacySay' => array(
                    'args' => array(
                        0 => 'ObjectReference',
                        1 => 'Topic',
                        2=> 'Actor',
                        3=> 'Bool'
                    ),
                    'returnType' => 'Float'
                ),

            ),
        'TES4Container' =>
            array (

            ),
    );

    private static function findSubtreeFor($class)
    {
        return self::findInternalSubtreeFor($class, self::$inheritance);
    }

    private static function findInternalSubtreeFor($class, $inputTree)
    {

        foreach ($inputTree as $key => $value) {

            if (is_integer($key)) { //value only
                if ($value == $class) {
                    return []; //Value matches.
                }
            } else {

                if ($key == $class) {
                    return $value;
                } else {
                    $data = self::findInternalSubtreeFor($class, $value);

                    if ($data !== false) {
                        return $data;
                    }
                }

            }


        }

        return false; //Not found.
    }

    private static function treeContains($class, $tree)
    {
        foreach ($tree as $key => $value) {
            if (is_integer($key)) { //value only
                if ($value == $class) {
                    return true;
                }
            } else {

                if ($key == $class) {
                    return true;
                } else {
                    $contains = self::treeContains($class, $value);

                    if ($contains === true) {
                        return $contains;
                    }
                }
            }
        }

        return false;
    }


    public static function isExtending(TES5Type $extendingType, TES5Type $baseType)
    {

        $subTree = self::findSubtreeFor($baseType->value());

        if ($subTree === false) {
            return false;
        }

        return self::treeContains($extendingType->value(), $subTree);

    }

    private static function targetRootBaseClass(TES5Type $type, $baseClassExtenders, $baseClassForNode)
    {
        $targetClassName = $type->value();

        if (is_array($baseClassExtenders)) {


            foreach ($baseClassExtenders as $className => $nodes) {

                if (is_integer($className)) {
                    $className = $nodes; // [0] => "Game" for instance
                }

                if ($className == $targetClassName) {
                    return $baseClassForNode;
                }

            }

            foreach ($baseClassExtenders as $className => $nodes) {

                if (is_integer($className)) {
                    $className = $nodes; // [0] => "Game" for instance
                }

                $recursiveReturn = self::targetRootBaseClass($type, $nodes, $className);

                if ($recursiveReturn !== null) {
                    return $recursiveReturn;
                }

            }

            if ($baseClassForNode == null) {
                throw new ConversionException("Type " . $targetClassName . " not found in inheritance graph.");
            }

            //not found in node.
            return null;

        } else {

            if ($targetClassName === $baseClassExtenders) {
                return $baseClassForNode;
            }

        }


    }

    public static function findBaseClassFor(TES5Type $type)
    {

        if (isset(self::$inheritanceCache[$type->value()])) {
            return self::$inheritanceCache[$type->value()];
        }

        $baseTypeName = TES5InheritanceGraphAnalyzer::targetRootBaseClass($type, self::$inheritance, null);
        $baseType = TES5TypeFactory::memberByValue($baseTypeName);
        self::$inheritanceCache[$type->value()] = $baseType;
        return $baseType;
    }

    public static function findTypeByMethodParameter(TES5Type $calledOnType, $methodName, $parameterIndex)
    {

        if (!isset(self::$callReturns[$calledOnType->value()]) && $calledOnType->isNativePapyrusType()) {
            throw new ConversionException("Inference type exception - no methods found for " . $calledOnType->value() . "!");
        }

        foreach (self::$callReturns[$calledOnType->value()] as $method => $functionData) {

            $arguments = $functionData['args'];

            if (strtolower($method) == strtolower($methodName)) {

                if (!isset($arguments[$parameterIndex])) {
                    throw new ConversionException("Cannot find argument index " . $parameterIndex . " in method " . $methodName . " in type " . $calledOnType->value());
                }

                return TES5TypeFactory::memberByValue($arguments[$parameterIndex]);

            }

        }

        return self::findTypeByMethodParameter(self::findBaseClassFor($calledOnType), $methodName, $parameterIndex);

        //throw new ConversionException("Method ".$methodName." not found in type ".$calledOnType->value());
    }

    public static function findReturnTypeForObjectCall(TES5Type $calledOnType, $methodName) {

        if (!isset(self::$callReturns[$calledOnType->value()])) {

            //Type not present in inheritance graph, check if its a basic type ( which means its basically an exception )
            if($calledOnType->isNativePapyrusType()) {
                throw new ConversionException("Inference type exception - no call returns for " . $calledOnType->value() . "!");
            } else {
                //Otherwise, treat it like a base script
                $calledOnType = $calledOnType->getNativeType();
            }


        }


        foreach (self::$callReturns[$calledOnType->value()] as $method => $functionData) {

            if (strtolower($method) == strtolower($methodName)) {
                return TES5TypeFactory::memberByValue($functionData['returnType']);
            }

        }

        return self::findReturnTypeForObjectCall(self::findBaseClassFor($calledOnType), $methodName);
    }

    /**
     * @param TES5ObjectCall $objectCall
     * @return mixed|TES5Type
     * @throws \Ormin\OBSLexicalParser\TES5\Exception\ConversionException
     *
     */
    public static function findTypeByMethod(TES5ObjectCall $objectCall)
    {
        $methodName = $objectCall->getFunctionName();
        $possibleMatches = [];
        foreach (self::$callReturns as $type => $methods) {

            foreach ($methods as $method => $functionData) {

                if (strtolower($method) == strtolower($methodName)) {
                    $possibleMatches[] = TES5TypeFactory::memberByValue($type);
                }

            }


        }

        $calledOn = $objectCall->getAccessedObject()->getReferencesTo() ;
        $extendingMatches = [];
        $actualType = $calledOn->getPropertyType()->getNativeType();


        /**
         * @var TES5Type[] $possibleMatches
         */
        foreach($possibleMatches as $possibleMatch) {
            if($possibleMatch == $actualType) {
                return $possibleMatch; //if the possible match matches the actual basic type, it means that it surely IS one of those.
            }

            //Ok, so are those matches somehow connected at all?
            if(TES5InheritanceGraphAnalyzer::isExtending($possibleMatch,$actualType) || TES5InheritanceGraphAnalyzer::isExtending($actualType,$possibleMatch)) {
                $extendingMatches[] = $possibleMatch;
            }

        }

        switch(count($extendingMatches)) {

            case 0: {
                $concatTypes = [];
                foreach($possibleMatches as $possibleMatch) {
                    $concatTypes[] = $possibleMatch->value();
                }

                throw new ConversionException("Cannot find any possible type for method ".$methodName.", trying to extend ".$actualType->value()." with following types: ".implode(', ',$concatTypes));
            }

            case 1: {
                return current($extendingMatches);
            }

            default: {

                //We analyze the property name and check inside the ESM analyzer.
                $formType = ESMAnalyzer::instance()->getFormTypeByEDID($calledOn->getReferenceEdid());

                if(!in_array($formType,$extendingMatches)) {
                    throw new ConversionException("ESM <-> Inheritance Graph conflict, ESM returned ".$formType->value().", which is not present in possible matches from inheritance graph");
                }

                return $formType;


            }

        }

    }


}
