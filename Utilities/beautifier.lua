

    --
    --
    -- Sky Indent PROXiCiDE aka Taewon
    -- Version 0.4
    --
    -- Allows you to beautify Papyrus source code
    --
    -- Steam:      http://steamcommunity.com/id/PROXiCiDE
    -- Nexus:      http://skyrim.nexusmods.com/users/3018315
    -- Bethesda:   http://forums.bethsoft.com/user/425376-taewon/
    --
    --
    -- Version History
    -- 0.4
    --		Keywords for Functions and Events used to be generated from the Creation Kit WIKI
    --		Functions and Events are now parsed from a collection of Papyrus source code
    --		Alot eaiser to keep SkyIndent updated
    --
    --		Reason was because there are undocumented functions and shortcuts
    --		Example GetRef() -> GetReference()
    --
    --		*.PSC
    --		Skyrim default
    --		SkyUI
    --		SKSE
    -- 0.3
    --		Optimizations, supports SKSE Events / Functions
    --		File size of SkyIndent.lua has been reduced significantly
    -- 0.2b
    --		Fixed array indentation
    -- 0.2a
    --		Tab spacing is the default if no option -s# passed in the command line arguments
    -- 0.1a
    --		Initial Release
    --
    --
     
     
    local StrLen = string.len
    local StrSub = string.sub
    local StrByte = string.byte
    local StrChar = string.char
    local StrRep = string.rep
    local StrFormat = string.format
    local OsExit = os.exit
     
     
    local function OsError(str)
            print('Error: '..StrFormat(str))
            os.execute("pause")
            OsExit(1)
    end
     
    local program = nil
     
    local codeTable01 = {}
    local codeTable02 = {}
    local function EraseTable(t)
            for k in next,t do
                    t[k] = nil
            end
    end
     
    local function StrIns(s, pos, insertStr)
            return StrSub(s, 1, pos) .. insertStr .. StrSub(s, pos + 1)
    end
     
    local function StrDel(s, pos1, pos2)
            return StrSub(s, 1, pos1 - 1) .. StrSub(s, pos2 + 1)
    end
     
    local Lexer = {}
    local TokenData = {}
    TokenData.AND = 1
    TokenData.ASSIGNMENT = 2
    TokenData.ASTERISK = 3
    TokenData.CIRCUMFLEX = 4
    TokenData.COLON = 5
    TokenData.COMMA = 6
    TokenData.COMMENT_LONG = 7
    TokenData.COMMENT_SHORT = 8
    TokenData.DOUBLEPERIOD = 9
    TokenData.EQUALITY = 10
    TokenData.GT = 11
    TokenData.GTE = 12
    TokenData.IDENTIFIER = 13
    TokenData.KEYWORD = 14
    TokenData.LEFTBRACKET = 15
    TokenData.LEFTPAREN = 16
    TokenData.LEFTWING = 17
    TokenData.LINEBREAK = 18
    TokenData.LT = 19
    TokenData.LTE = 20
    TokenData.MINUS = 21
    TokenData.NOT = 22
    TokenData.NE = 23
    TokenData.NUMBER = 24
    TokenData.OR = 25
    TokenData.MODULO = 26
    TokenData.PERIOD = 27
    TokenData.PLUS = 28
    TokenData.RIGHTBRACKET = 29
    TokenData.RIGHTPAREN = 30
    TokenData.RIGHTWING = 31
    TokenData.SEMICOLON = 32
    TokenData.SLASH = 33
    TokenData.SPECIAL = 34
    TokenData.STRING = 35
    TokenData.TRIPLEPERIOD = 37
    TokenData.UNKNOWN = 38
    TokenData.VERTICAL = 39
    TokenData.WHITESPACE = 40
     
     
    -- ascii codes
    local ByteData = {}
    ByteData.LINEBREAK_UNIX = StrByte("\n")
    ByteData.LINEBREAK_MAC = StrByte("\r")
    ByteData.SINGLE_QUOTE = StrByte("'")
    ByteData.DOUBLE_QUOTE = StrByte('"')
    ByteData.NUM_0 = StrByte("0")
    ByteData.NUM_9 = StrByte("9")
    ByteData.PERIOD = StrByte(".")
    ByteData.SPACE = StrByte(" ")
    ByteData.TAB = StrByte("\t")
    ByteData.E = StrByte("E")
    ByteData.e = StrByte("e")
    ByteData.MINUS = StrByte("-")
    ByteData.EQUALS = StrByte("=")
    ByteData.LEFTBRACKET = StrByte("[")
    ByteData.RIGHTBRACKET = StrByte("]")
    ByteData.BACKSLASH = StrByte("\\")
    ByteData.COMMA = StrByte(",")
    ByteData.SEMICOLON = StrByte(";")
    ByteData.COLON = StrByte(":")
    ByteData.LEFTPAREN = StrByte("(")
    ByteData.RIGHTPAREN = StrByte(")")
    ByteData.EXCLAMATIONMARK = StrByte("!")
    ByteData.PLUS = StrByte("+")
    ByteData.SLASH = StrByte("/")
    ByteData.LEFTWING = StrByte("{")
    ByteData.RIGHTWING = StrByte("}")
    ByteData.CIRCUMFLEX = StrByte("^")
    ByteData.ASTERISK = StrByte("*")
    ByteData.LESSTHAN = StrByte("<")
    ByteData.GREATERTHAN = StrByte(">")
    ByteData.AND = StrByte("&")
    ByteData.OR = StrByte("|")
    ByteData.MODULO = StrByte("%")
     
    local newline = {}
    newline[ByteData.LINEBREAK_UNIX] = 1
    newline[ByteData.LINEBREAK_MAC] = 1
     
    local whitespaces = {}
    whitespaces[ByteData.SPACE] = 1
    whitespaces[ByteData.TAB] = 1
     
    local SymbolData = {}
    SymbolData[ByteData.PERIOD] = -1
    SymbolData[ByteData.LESSTHAN] = -1
    SymbolData[ByteData.GREATERTHAN] = -1
    SymbolData[ByteData.LEFTBRACKET] = -1
    SymbolData[ByteData.EQUALS] = -1
    SymbolData[ByteData.MINUS] = -1
    SymbolData[ByteData.SINGLE_QUOTE] = -1
    SymbolData[ByteData.DOUBLE_QUOTE] = -1
    SymbolData[ByteData.EXCLAMATIONMARK] = -1
    SymbolData[ByteData.SEMICOLON] = -1
    SymbolData[ByteData.RIGHTBRACKET] = TokenData.RIGHTBRACKET
    SymbolData[ByteData.COMMA] = TokenData.COMMA
    SymbolData[ByteData.COLON] = TokenData.COLON
    SymbolData[ByteData.LEFTPAREN] = TokenData.LEFTPAREN
    SymbolData[ByteData.RIGHTPAREN] = TokenData.RIGHTPAREN
    SymbolData[ByteData.PLUS] = TokenData.PLUS
    SymbolData[ByteData.SLASH] = TokenData.SLASH
    SymbolData[ByteData.LEFTWING] = TokenData.LEFTWING
    SymbolData[ByteData.RIGHTWING] = TokenData.RIGHTWING
    SymbolData[ByteData.CIRCUMFLEX] = TokenData.CIRCUMFLEX
    SymbolData[ByteData.ASTERISK] = TokenData.ASTERISK
    SymbolData[ByteData.OR] = -1
    SymbolData[ByteData.AND] = -1
    SymbolData[ByteData.MODULO] = TokenData.MODULO
     
    -- The following was generated from the Creation Kit wiki
    -- http://www.creationkit.com/Keyword_Reference
     
    local IndentIgnore = {0, 0}
    local IndentLeft = {-1, 0}
    local IndentRight = {0, 1}
    local IndentBoth = {-1, 1}
     
     
    function IndentType(t)
            local s={}
            table.insert(s, {
                            name = string.lower(t[1]),
                            orig = t[1],
                            type = t[2]
            })
            return s
    end
     
    KeywordReferenceData = {
            IndentType {"Else",IndentBoth},
            IndentType {"ElseIf",IndentBoth},
     
            IndentType {"Native",IndentIgnore},
            IndentType {"New",IndentIgnore},
            IndentType {"As",IndentIgnore},
            IndentType {"Int",IndentIgnore},
            IndentType {"Length",IndentIgnore},
            IndentType {"Import",IndentIgnore},
            IndentType {"None",IndentIgnore},
            IndentType {"Parent",IndentIgnore},
            IndentType {"Property",IndentIgnore},
            IndentType {"String",IndentIgnore},
            IndentType {"Hidden",IndentIgnore},
            IndentType {"Self",IndentIgnore},
            IndentType {"Return",IndentIgnore},
            IndentType {"ScriptName",IndentIgnore},
            IndentType {"True",IndentIgnore},
            IndentType {"Global",IndentIgnore},
            IndentType {"Float",IndentIgnore},
            IndentType {"Bool",IndentIgnore},
            IndentType {"AutoReadOnly",IndentIgnore},
            IndentType {"Extends",IndentIgnore},
            IndentType {"False",IndentIgnore},
            IndentType {"Auto",IndentIgnore},
     
            IndentType {"EndEvent",IndentLeft},
            IndentType {"EndFunction",IndentLeft},
            IndentType {"EndIf",IndentLeft},
            IndentType {"EndProperty",IndentLeft},
            IndentType {"EndState",IndentLeft},
            IndentType {"EndWhile",IndentLeft},
     
            IndentType {"If",IndentRight},
            IndentType {"State",IndentRight},
            IndentType {"Event",IndentRight},
            IndentType {"Function",IndentRight},
            IndentType {"While",IndentRight},
    }
     
    function FindKeywordReference(name)
            for k,v in ipairs(KeywordReferenceData) do
                    if string.lower(name) == v[1].name then
                            return v[1].name,v[1].orig,v[1].type
                    end
            end
            return nil,nil,nil
    end
     
    -- The following was generated from the Creation Kit wiki
    -- http://www.creationkit.com/Category:Script_Objects
     
    function CaseType(t)
            local s={}
            for i,v in ipairs(t) do
                    s[string.lower(v)]=v
            end
            return s
    end
     
    ScriptObjects = CaseType {
    		"Action",  "Activator",  "ActiveMagicEffect",  "Actor",
    		"ActorBase",  "Alias",  "Ammo",  "Apparatus",
    		"Armor",  "AssociationType",  "Book",  "Cell",
    		"Class",  "ColorForm",  "ConstructibleObject",  "Container",
    		"Debug",  "Door",  "EffectShader",  "Enchantment",
    		"EncounterZone",  "Explosion",  "Faction",  "Flora",
    		"Form",  "FormList",  "Furniture",  "Game",
    		"GlobalVariable",  "Hazard",  "Idle",  "ImageSpaceModifier",
    		"ImpactDataSet",  "Ingredient",  "Input",  "Key",
    		"Keyword",  "LeveledActor",  "LeveledItem",  "LeveledSpell",
    		"Light",  "Location",  "LocationAlias",  "LocationRefType",
    		"MagicEffect",  "Math",  "Message",  "MiscObject",
    		"MusicType",  "ObjectReference",  "Outfit",  "Package",
    		"Perk",  "Potion",  "Projectile",  "Quest",
    		"Race",  "ReferenceAlias",  "Scene",  "Scroll",
    		"Shout",  "SKSE",  "SoulGem",  "Sound",
    		"SoundCategory",  "Spell",  "Static",  "StringUtil",
    		"TalkingActivator",  "TextureSet",  "Topic",  "TopicInfo",
    		"UI",  "Utility",  "VisualEffect",  "VoiceType",
    		"Weapon",  "Weather",  "WordOfPower",  "WorldSpace",
    }
     
    -- The following used to be generated from the Creation Kit wiki
    -- http://www.creationkit.com/Category:Papyrus
    --
    -- Now generated from a collection of Papyrus files
    ApiData = CaseType {
    -- Generated data for Skyrim
    		"IsFurnitureMarkerInUse",  "GetSunPositionX",  "UnregisterForTrackedStatsEvent",  "CanFlyHere",
    		"ShowLimitedRaceMenu",  "SetAV",  "ProcessTrapHit",  "SetFogPower",
    		"SetDestroyed",  "KillSilent",  "TryToClear",  "RemoveShout",
    		"ClearTempEffects",  "KeepOffsetFromActor",  "ModActorValue",  "GetSleepState",
    		"IsInInterior",  "SetCrimeGold",  "TryToKill",  "Resurrect",
    		"StopObjectProfiling",  "CanPayCrimeGold",  "GetRace",  "Dispel",
    		"FindRandomReferenceOfAnyTypeInListFromRef",  "GetCrimeFaction",  "ForceMovementRotationSpeedRamp",  "HasCommonParent",
    		"StartScriptProfiling",  "IsFightingControlsEnabled",  "SetCrimeFaction",  "GetValueInt",
    		"RegisterForUpdateGameTime",  "IsStopping",  "SetActorOwner",  "GetEquippedWeapon",
    		"ShowTitleSequenceMenu",  "GetAlias",  "HasLOS",  "IsMapMarkerVisible",
    		"GetActorOwner",  "HasPerk",  "GetLeveledActorBase",  "TryToEvaluatePackage",
    		"HasParentRelationship",  "SetCleared",  "SetValueInt",  "Disable",
    		"GetKiller",  "TryToRemoveFromFaction",  "UnequipItem",  "CaptureFrameRate",
    		"GetEditorLocation",  "FailAllObjectives",  "SetSubGraphFloatVariable",  "IsActive",
    		"HasMagicEffectWithKeyword",  "AddItem",  "PlaySyncedAnimationAndWaitSS",  "FindClosestReferenceOfAnyTypeInListFromRef",
    		"FindClosestReferenceOfTypeFromRef",  "SetMotionType",  "MoveToPackageLocation",  "GetCurrentWeatherTransition",
    		"IsSneakingControlsEnabled",  "AddInventoryEventFilter",  "IsInterior",  "GetSize",
    		"GetBudgetCount",  "IncrementSkill",  "ClearLookAt",  "Show",
    		"IsInFaction",  "QuitGame",  "PlayerKnows",  "TriggerScreenBlood",
    		"WornHasKeyword",  "GetGoldValue",  "IsBeingRidden",  "GetAVPercentage",
    		"SetObjectiveDisplayed",  "SetSunGazeImageSpaceModifier",  "SetPublic",  "GetLockLevel",
    		"GetItemHealthPercent",  "RemoveDependentAnimatedObjectReference",  "DoCombatSpellApply",  "SetLockLevel",
    		"FindClosestActor",  "PlayImpactEffect",  "StartDeferredKill",  "Activate",
    		"GetBribeAmount",  "SetINIFloat",  "GetLocation",  "IsFastTravelControlsEnabled",
    		"SetDontMove",  "ModFavorPoints",  "ResetHealthAndLimbs",  "UnequipSpell",
    		"ShowGiftMenu",  "AddDependentAnimatedObjectReference",  "ForceMovementDirection",  "GetPositionY",
    		"SetAnimationVariableInt",  "SetPlayerAIDriven",  "UnequipShout",  "AddPerk",
    		"IsWordUnlocked",  "ForceAV",  "GetAnimationVariableInt",  "SetInvulnerable",
    		"ToggleMenus",  "CenterOnCell",  "EnableFastTravel",  "ForceLocationTo",
    		"GetAnimationVariableFloat",  "DumpAliasData",  "GetAngleZ",  "SetAllowFlying",
    		"AddToMap",  "RemoveSpell",  "GetStolenItemValueCrime",  "BlockActivation",
    		"GetAssociatedSkill",  "IsBleedingOut",  "SetINIBool",  "SetAngle",
    		"SetPlayerReportCrime",  "SendTrespassAlarm",  "SetAnimationVariableFloat",  "TryToReset",
    		"IsIntimidated",  "FindRandomReferenceOfAnyTypeInList",  "ForceRefTo",  "SplineTranslateToRefNode",
    		"TrapSoul",  "IsMenuControlsEnabled",  "Lock",  "ModReaction",
    		"GetPositionZ",  "ShakeCamera",  "SendStealAlarm",  "GetHeadingAngle",
    		"CloseUserLog",  "PlaceActorAtMe",  "ClearExpressionOverride",  "ClearKeepOffsetFromActor",
    		"Notification",  "SetOpen",  "PlaySubGraphAnimation",  "GetDialogueTarget",
    		"Delete",  "IsAllowedToFly",  "IsActivationBlocked",  "EnableNoWait",
    		"InterruptCast",  "UnregisterForUpdate",  "Pause",  "SetPlayerControls",
    		"DrawWeapon",  "RemoveFromAllFactions",  "GetNoBleedoutRecovery",  "GetPlayerControls",
    		"IsCleared",  "SetOutfit",  "IsInCombat",  "FindRandomReferenceOfType",
    		"SetFogPlanes",  "AdvanceSkill",  "IsPlayerTeammate",  "SetNoBleedoutRecovery",
    		"FindRandomReferenceOfTypeFromRef",  "Reset",  "UnregisterForLOS",  "IsPlayerSungazing",
    		"GetAngleX",  "OpenInventory",  "HasEffectKeyword",  "Clear",
    		"GetConfigName",  "IsLocked",  "TraceConditional",  "ShowAsHelpMessage",
    		"FindRandomActorFromRef",  "FindRandomActor",  "LearnAllEffects",  "SetFactionRank",
    		"IsEquipped",  "IsDead",  "AttachAshPile",  "HasAssociation",
    		"IsActivateChild",  "GetFactionRank",  "GetHighestRelationshipRank",  "LearnEffect",
    		"LearnNextEffect",  "GetForm",  "ForceRemoveRagdollFromWorld",  "TraceUser",
    		"PathToReference",  "GetMass",  "StopTranslation",  "EvaluatePackage",
    		"GetAverageFrameRate",  "SetPlayerExpelled",  "UnLockOwnedDoorsInCell",  "SetActorCause",
    		"GetCurrentScene",  "SetVehicle",  "OverBudget",  "GetSunPositionZ",
    		"GetCurrentPackage",  "SetPosition",  "GameTimeToString",  "GetLowestRelationshipRank",
    		"Play",  "AddHavokBallAndSocketConstraint",  "IsInLocation",  "ClearExtraArrows",
    		"EquipItem",  "GetMinFrameRate",  "RegisterForUpdate",  "SetHudCartMode",
    		"IsInMenuMode",  "PushActorAway",  "IsActivateControlsEnabled",  "StopInstance",
    		"GetSitState",  "GetCurrentRealTime",  "DisableLinkChain",  "HasSpell",
    		"ForceTargetSpeed",  "IsNearPlayer",  "GetFactionReaction",  "Start",
    		"IsDetectedBy",  "TakeScreenshot",  "CalculateFavorCost",  "SetINIString",
    		"RemoveInventoryEventFilter",  "ClearPrison",  "GetStolenItemValueNoCrime",  "RegisterForAnimationEvent",
    		"GetEquippedShield",  "StartObjectProfiling",  "SetAllowFlyingEx",  "Mod",
    		"EquipSpell",  "Fire",  "IsFastTravelEnabled",  "SendAnimationEvent",
    		"HasNode",  "Add",  "GetTemplate",  "GetKeywordData",
    		"IsActionComplete",  "SetEyeTexture",  "IsPlaying",  "GetClassification",
    		"SetInstanceVolume",  "ForceThirdPerson",  "IgnoreFriendlyHits",  "PlayAndWait",
    		"IsPlayersLastRiddenHorse",  "SetFrequency",  "SetObjectiveCompleted",  "UnMute",
    		"Mute",  "IsLoaded",  "SetCrimeGoldViolent",  "CreateDetectionEvent",
    		"Revert",  "EndDeferredKill",  "HasForm",  "GetAt",
    		"GetPlayersLastRiddenHorse",  "UnequipItemSlot",  "Find",  "GetCasterActor",
    		"AddForm",  "RemoveCrossFade",  "GetCurrentDestructionStage",  "GetCurrentGameTime",
    		"SendWereWolfTransformation",  "GetKey",  "Remove",  "PopTo",
    		"GetLength",  "Dismount",  "IncrementStat",  "ModCrimeGold",
    		"TetherToHorse",  "Apply",  "RemoveAllInventoryEventFilters",  "RegisterForLOS",
    		"CompleteQuest",  "GetCurrentWeather",  "ApplyHavokImpulse",  "IsEssential",
    		"IsInKillMove",  "MoveTo",  "ResetHelpMessage",  "Unload",
    		"Preload",  "IsHostile",  "MoveToMyEditorLocation",  "RemoteCast",
    		"SetGhost",  "TryToEnableNoWait",  "IsAttached",  "GetSkyMode",
    		"GetOutgoingWeather",  "ForceStart",  "FindWeather",  "GetBaseAV",
    		"ForceActive",  "RegisterForTrackedStatsEvent",  "ReleaseOverride",  "SetProtected",
    		"IsProtected",  "SetEssential",  "GetWorldSpace",  "GetWidth",
    		"IsUnique",  "RegisterForSleep",  "IsInvulnerable",  "ModFactionRank",
    		"GetForcedLandingMarker",  "GetGiftFilter",  "GetDeadCount",  "GetClass",
    		"tan",  "sqrt",  "GetHeight",  "DeleteWhenAble",
    		"UnregisterForSleep",  "SetIntimidated",  "StartSneaking",  "IsChild",
    		"SetBeastForm",  "IsSprinting",  "IsSneaking",  "ShowRaceMenu",
    		"UnPause",  "pow",  "Floor",  "SetPlayerEnemy",
    		"DegreesToRadians",  "cos",  "Ceiling",  "GetDistance",
    		"atan",  "asin",  "ShowRefPosition",  "GetActorReference",
    		"abs",  "SetKeywordData",  "IsSameLocation",  "GetReaction",
    		"GetRefTypeAliveCount",  "PrecacheCharGenClear",  "IsDeleted",  "IsGhost",
    		"GetBudgetName",  "FindClosestActorFromRef",  "SetExpressionOverride",  "GetCurrentBudget",
    		"PlayAnimationAndWait",  "GetCurrentMemory",  "KnockAreaEffect",  "IsDisabled",
    		"PlayIdleWithTarget",  "Enable",  "TryToAddToFaction",  "GetPlatformName",
    		"SetStage",  "IsAlerted",  "RestoreActorValue",  "HasKeyword",
    		"IsGuard",  "StopScriptProfiling",  "AddToFaction",  "GetOpenState",
    		"countLinkedRefChain",  "IsInDialogueWithPlayer",  "IsHostileToActor",  "SetINIInt",
    		"ModAV",  "RandomInt",  "PlayerMoveToAndWait",  "SetEnemy",
    		"RadiansToDegrees",  "StopCombatAlarm",  "SetAlly",  "MakePlayerFriend",
    		"RemoveAllItems",  "SendPlayerToJail",  "IsMovementControlsEnabled",  "IsArrested",
    		"UnregisterForAnimationEvent",  "TranslateTo",  "IsPlayerExpelled",  "acos",
    		"GetParentCell",  "ForceMovementSpeed",  "GetRefTypeDeadCount",  "GetInfamyViolent",
    		"SetAllowFlyingMountLandingRequests",  "GetInfamy",  "DisableNoWait",  "SetCameraTarget",
    		"RandomFloat",  "GetItemCount",  "GetBaseActorValue",  "GetCrimeGold",
    		"DBSendPlayerPosition",  "AddSpell",  "GetRef",  "IsFactionInCrimeGroup",
    		"DropObject",  "TraceAndBox",  "TraceStack",  "Trace",
    		"ToggleCollisions",  "RemovePerk",  "IsIgnoringFriendlyHits",  "ToggleAI",
    		"StopStackProfiling",  "StartStackProfiling",  "rampRumble",  "SetGodMode",
    		"SetFootIK",  "IsCamSwitchControlsEnabled",  "DispelSpell",  "OpenUserLog",
    		"EquipShout",  "MessageBox",  "SetAlpha",  "GetVersionNumber",
    		"EndFrameRateCapture",  "QueryStat",  "IsDoingFavor",  "DebugChannelNotify",
    		"HasMagicEffect",  "SetReaction",  "SetSittingRotation",  "MoveToIfUnloaded",
    		"ForceMovementSpeedRamp",  "GetSelfAsActor",  "CenterOnCellAndWait",  "GetOwningQuest",
    		"PlayGamebryoAnimation",  "StartFrameRateCapture",  "GetTargetActor",  "SetCurrentStageID",
    		"SetUnconscious",  "GetFormID",  "SetVolume",  "IsStarting",
    		"SetVoiceRecoveryTime",  "SetAnimationVariableBool",  "UnequipAll",  "SetRelationshipRank",
    		"GetEquippedSpell",  "RegisterForSingleLOSGain",  "ShowBarterMenu",  "GetAngleY",
    		"GetVoiceRecoveryTime",  "GetAnimationVariableBool",  "CompleteAllObjectives",  "RequestSave",
    		"StopCombat",  "GetRelationshipRank",  "CalculateEncounterLevel",  "AddShout",
    		"DamageAV",  "SetCriticalStage",  "UpdateCurrentInstanceGlobal",  "GetPositionX",
    		"TryToStopCombat",  "ForceMovementDirectionRamp",  "SetObjectiveFailed",  "SetActive",
    		"IsStopped",  "IsUnconscious",  "IsStageDone",  "IsObjectiveFailed",
    		"IsObjectiveDisplayed",  "IsObjectiveCompleted",  "IsCompleted",  "GetStageDone",
    		"GetStage",  "GetCurrentStageID",  "StartTitleSequence",  "RequestAutoSave",
    		"ApplyCrossFade",  "PlayIdle",  "GetAV",  "set",
    		"DamageActorValue",  "GetActorValue",  "GetPlayer",  "RemoveFromFaction",
    		"DamageObject",  "ClearForcedMovement",  "IsBribed",  "TryToMoveTo",
    		"TryToEnable",  "get",  "TryToDisableNoWait",  "SetLookAt",
    		"Kill",  "GetSex",  "IsLockBroken",  "IsWeaponDrawn",
    		"GetMaxFrameRate",  "PlaySyncedAnimationSS",  "PlaceAtMe",  "IsAlarmed",
    		"GetActorRef",  "TranslateToRef",  "GetLightLevel",  "EnableAI",
    		"ForceRefIfEmpty",  "ForceTargetDirection",  "GetReference",  "TryToDisable",
    		"UsingGamepad",  "GetTriggerObjectCount",  "EnableLinkChain",  "ClearArrested",
    		"Is3DLoaded",  "GetGameSettingString",  "TeachWord",  "ShowTrainingMenu",
    		"GetInfamyNonViolent",  "HideTitleSequenceMenu",  "SendAssaultAlarm",  "ModObjectiveGlobal",
    		"ModFavorPointsWithGlobal",  "SetAlert",  "GetLevel",  "ShakeController",
    		"WaitGameTime",  "SetInChargen",  "IsArrestingTarget",  "MoveToInteractionLocation",
    		"GetScale",  "HasFamilyRelationship",  "GetCrimeGoldViolent",  "GetPlayerGrabbedRef",
    		"RequestModel",  "ServeTime",  "WaitForAnimationEvent",  "RegisterForSingleUpdateGameTime",
    		"PlayerPayCrimeGold",  "IsFlying",  "PrecacheCharGen",  "SetNoFavorAllowed",
    		"SetAttackActorOnSight",  "IsFurnitureInUse",  "IsJournalControlsEnabled",  "IncrementSkillBy",
    		"ClearDestruction",  "IsOnMount",  "GetActorBase",  "GetCurrentLocation",
    		"GetSunPositionY",  "GetGameSettingInt",  "GetGameSettingFloat",  "GetFormFromFile",
    		"SetActorValue",  "AllowPCDialogue",  "SetDoingFavor",  "GetBaseObject",
    		"ForceMovementRotationSpeed",  "GetEquippedShout",  "FindClosestReferenceOfAnyTypeInList",  "SetHeadTracking",
    		"SetFactionOwner",  "FindClosestReferenceOfType",  "FastTravel",  "HasRefType",
    		"FadeOutGame",  "IsRunning",  "SplineTranslateToRef",  "PlayAnimation",
    		"MoveToWhenUnloaded",  "WaitMenuMode",  "GetFactionOwner",  "ForceActorValue",
    		"Say",  "AddPerkPoints",  "MoveToNode",  "sin",
    		"AddAchievement",  "GetFlyingState",  "GetCombatTarget",  "RegisterForSingleLOSLost",
    		"RemoveHavokConstraints",  "GetActorValuePercentage",  "ClearForcedLandingMarker",  "WillIntimidateSucceed",
    		"CanFastTravelToMarker",  "UnregisterForUpdateGameTime",  "StartCannibal",  "KillEssential",
    		"QuitToMainMenu",  "RegisterForSingleUpdate",  "IsTrespassing",  "ForceAddRagdollToWorld",
    		"GetVoiceType",  "SetRace",  "IsEnabled",  "RemoveItem",
    		"GetRealHoursPassed",  "SetForcedLandingMarker",  "AllowBleedoutDialogue",  "ShowFirstPersonGeometry",
    		"GetEquippedItemType",  "SetRestrained",  "ForceTargetAngle",  "SplineTranslateTo",
    		"StartCombat",  "SetPlayerTeammate",  "GetNthLinkedRef",  "SetPlayerResistingArrest",
    		"SetNotShowOnStealthMeter",  "Cast",  "RemoveAddedForm",  "GetCombatState",
    		"SetBribed",  "RestoreAV",  "UnlockWord",  "GetCrimeGoldNonViolent",
    		"DispelAllSpells",  "SetScale",  "ForceFirstPerson",  "GetValue",
    		"StartVampireFeed",  "IsLookingControlsEnabled",  "GetGoldAmount",  "Wait",
    		"IsCommandedActor",  "Stop",  "GetLinkedRef",  "AddKeyIfNeeded",
    		"PlayTerrainEffect",  "SetValue",
     
    -- Generated data for SkyUI
    		"SetMenuDialogOptions",  "SetKeyMapOptionValue",  "SetSliderDialogDefaultValue",  "AddEmptyOption",
    		"SetCursorFillMode",  "SetTextOptionValue",  "GetCustomControl",  "CheckVersion",
    		"SetTitleText",  "AddHeaderOption",  "Guard",  "SetMenuDialogDefaultIndex",
    		"SetSliderOptionValue",  "SetToggleOptionValue",  "SetColorDialogStartColor",  "ShowMessage",
    		"AddSliderOption",  "AddToggleOption",  "SetMenuDialogStartIndex",  "AddMenuOption",
    		"SetOptionFlags",  "SetSliderDialogInterval",  "GetVersion",  "SetSliderDialogRange",
    		"SetSliderDialogStartValue",  "SetMenuOptionValue",  "AddColorOption",  "SetCursorPosition",
    		"SetColorDialogDefaultColor",  "UnloadCustomContent",  "ForcePageReset",  "LoadCustomContent",
    		"AddKeyMapOption",  "SetInfoText",  "AddTextOption",  "SetColorOptionValue",
     
    -- Generated data for SKSE
    		"GetNumHeadParts",  "GetFlightHoverChance",  "ModPerkPoints",  "SetMeleePowerAttackStaggeredMult",
    		"GetNthPlayableRace",  "LeftShift",  "SetNthIngredientQuantity",  "SaveGame",
    		"GetNthAdditionalRace",  "GetWornForm",  "IsFood",  "GetNumParts",
    		"GetIndexOfExtraPart",  "SetInt",  "GetNthPart",  "SetMagicMult",
    		"UnregisterForModEvent",  "GetNumTintsByType",  "IsImmobile",  "SetReach",
    		"SetFaceMorph",  "GetSpell",  "UnregisterForKey",  "GetMagicMult",
    		"IsMace",  "CanWalk",  "IsPunctuation",  "SetWeight",
    		"LoadGame",  "SetNthSpell",  "UnregisterForMenu",  "GetIconPath",
    		"InvokeFloatA",  "SetIconPath",  "GetFrequencyVariance",  "GetINIString",
    		"CanFly",  "SetStaffMult",  "GetNthForm",  "GetWeight",
    		"GetTimeElapsed",  "SetFacePreset",  "ResetInventory",  "GetSpellCount",
    		"GetEnchantment",  "LogicalAnd",  "GetVersionRelease",  "GetNumExtraParts",
    		"SetCritDamage",  "GetName",  "GetCritDamage",  "SetName",
    		"GetItemMaxCharge",  "SetOffensiveMult",  "GetNthSpell",  "GetTintMaskColor",
    		"IsChildRace",  "SetQuality",  "GetReach",  "IsLightArmor",
    		"GetQuality",  "GetNumIngredients",  "GetNthAlias",  "GetNthExtraPart",
    		"TempClone",  "MakeCanSwim",  "GetNthEffectMagnitude",  "ClearRaceFlag",
    		"IsSword",  "ClearAllowPCDialogue",  "SetEnchantment",  "SetCombatStyle",
    		"UnregisterForAllControls",  "IsClothingHands",  "SetArmorRating",  "SetCloseRangeFlankingStalkTime",
    		"GetModName",  "GetCloseRangeFlankingFlankDistance",  "CanSwim",  "SetMeleePowerAttackBlockingMult",
    		"NoKnockdowns",  "GetMeleePowerAttackBlockingMult",  "GetFlightFlyingAttackChance",  "SetModelNthTextureSet",
    		"IsClothingRing",  "SetModelPath",  "SetResult",  "SetMeleeBashMult",
    		"IsWarAxe",  "GetMeleeBashMult",  "GetModDescription",  "SetResultQuantity",
    		"GetModelPath",  "GetNthEffectDuration",  "SetCloseRangeDuelingCircleMult",  "SetAllowDualWielding",
    		"MakeImmobile",  "GetResult",  "GetAllowDualWielding",  "QueueNiNodeUpdate",
    		"GetStagger",  "GetArmorRating",  "UnregisterForControl",  "GetCloseRangeFlankingStalkTime",
    		"GetPerkPoints",  "IsStaff",  "SetSlotMask",  "SetNoCombatInWater",
    		"GetDuration",  "ClearNoCombatInWater",  "GetSaturation",  "GetINIFloat",
    		"MakeCanWalk",  "SetSaturation",  "GetSoulSize",  "GetNthWordOfPower",
    		"IsKeyPressed",  "GetNumAliases",  "SendModEvent",  "MakeCanFly",
    		"GetMaskForSlot",  "SetPerkPoints",  "GetNumArmorAddons",  "SetMessageIconPath",
    		"GetModAuthor",  "UnregisterForAllMenus",  "ClearAvoidsRoads",  "GetModelNthTextureSet",
    		"GetMessageIconPath",  "GetAvoidThreatChance",  "GetNumEffects",  "UnregisterForAllKeys",
    		"GetUnarmedMult",  "GetFogDistance",  "GetNthTintMaskType",  "GetWindDirectionRange",
    		"GetMeleeAttackStaggeredMult",  "SetAllowPCDialogue",  "ClearNoKNockdowns",  "GetString",
    		"SetGameSettingBool",  "GetWindDirection",  "GetModByName",  "GetColor",
    		"InvokeString",  "GetDefensiveMult",  "GetSunGlare",  "LogicalXor",
    		"SetMeleeAttackStaggeredMult",  "SetDefensiveMult",  "GetDecibelAttenuation",  "InvokeInt",
    		"IsWarhammer",  "MakeChildRace",  "IsGreatsword",  "IsDagger",
    		"MakeMobile",  "IsBattleaxe",  "GetNthModDependency",  "GetType",
    		"SetUnarmedMult",  "GetWeaponType",  "GetNiNodePositionY",  "SetStagger",
    		"TapKey",  "SetSpeed",  "IsPrintable",  "SetCloseRangeDuelingFallbackMult",
    		"SetMeleeMult",  "GetSpeed",  "GetWeightClass",  "AddSlotToMask",
    		"SetMaxRange",  "AvoidsRoads",  "AsChar",  "GetBool",
    		"SetNthHeadPart",  "SetMinRange",  "GetModDependencyCount",  "GetMinRange",
    		"GetMeleeMult",  "RegisterForControl",  "SetBaseDamage",  "GetBaseDamage",
    		"IsLetter",  "IsHarvested",  "Invoke",  "ModArmorRating",
    		"GetCloseRangeDuelingFallbackMult",  "InvokeForm",  "GetTotalArmorWeight",  "InvokeNumberA",
    		"SetGameSettingInt",  "InvokeStringA",  "IsMenuOpen",  "SetColor",
    		"GetHue",  "GetID",  "SetItemHealthPercent",  "SetMeleeBashRecoiledMult",
    		"InvokeIntA",  "InvokeBoolA",  "GetFlightDiveBombChance",  "InvokeNumber",
    		"SetNoShadow",  "IsRaceFlagSet",  "GetSunDamage",  "SetClass",
    		"InvokeBool",  "ClearAllowPickpocket",  "GetFloat",  "GetInt",
    		"GetSlotMask",  "GetNiNodePositionX",  "SetFlightDiveBombChance",  "SetLongRangeStrafeMult",
    		"GetNumTintMasks",  "SetString",  "SetAllowPickpocket",  "GetMeleeBashRecoiledMult",
    		"GetTotalItemWeight",  "UnregisterForAllModEvents",  "GetNthChar",  "GetResultQuantity",
    		"SetHue",  "GetMaxRange",  "IsDigit",  "Substring",
    		"AsOrd",  "GetCostliestEffectIndex",  "GetINIBool",  "GetNthRef",
    		"MakeNonSwimming",  "SetFrequencyShift",  "GetNumItems",  "GetFrequencyShift",
    		"SetFlightFlyingAttackChance",  "GetGemSize",  "GetNthTintMaskColor",  "SetRed",
    		"SetFrequencyVariance",  "SetDecibelVariance",  "GetDecibelVariance",  "GetNthKeyword",
    		"SetDecibelAttenuation",  "GetNumKeysPressed",  "GetDescriptor",  "GetVersionBeta",
    		"GetAlpha",  "HasExtraPart",  "GetNthEffectArea",  "GetVersionMinor",
    		"SetTintMaskColor",  "SetNthWordOfPower",  "AllowPickpocket",  "ClearCantOpenDoors",
    		"GetOutfit",  "MakePushable",  "GetSkill",  "GetRed",
    		"GetCastTime",  "GetValidRaces",  "SetTintMaskTexturePath",  "GetPerk",
    		"NoShadow",  "ClearNoShadow",  "SetCantOpenDoors",  "GetNumPlayableRaces",
    		"CantOpenDoors",  "GetNumber",  "GetNthKeyPressed",  "IsClothingRich",
    		"SetAR",  "SetFloat",  "GetNthRecoveryTime",  "SetAvoidsRoads",
    		"NoCombatInWater",  "GetNumKeywords",  "GetMeleeBashAttackMult",  "MakeNotPushable",
    		"IsNotPushable",  "IsBow",  "GetTintMaskTexturePath",  "MakeNonWalking",
    		"GetAR",  "GetMagickaCost",  "SetNthRecoveryTime",  "SetFlightHoverChance",
    		"ReleaseKey",  "GetHairColor",  "GetGroupOffensiveMult",  "MakePlayable",
    		"SetNthIngredient",  "GetNumRefs",  "SetGroupOffensiveMult",  "RightShift",
    		"IsPlayable",  "IsGauntlets",  "SetRaceFlag",  "GetLongRangeStrafeMult",
    		"IsClothingBody",  "GetOffensiveMult",  "RegisterForKey",  "MakeNonFlying",
    		"GetMeleePowerAttackStaggeredMult",  "SetWorkbenchKeyword",  "GetINIInt",  "IsHeavyArmor",
    		"GetPriority",  "GetNthHeadPart",  "GetFaceMorph",  "GetNthIngredient",
    		"SetShoutMult",  "GetQuest",  "InvokeFloat",  "IsClothingHead",
    		"GetWorkbenchKeyword",  "GetNiNodeScale",  "SetNthTintMaskColor",  "GetNiNodePositionZ",
    		"GetNumAdditionalRaces",  "IsShield",  "GetFacePreset",  "IsTextInputEnabled",
    		"MakeUnplayable",  "SetHeight",  "HasKeywordString",  "SetGreen",
    		"GetNthEffectMagicEffect",  "GetNthTintMaskTexturePath",  "SetNiNodeScale",  "GetRangedMult",
    		"RegisterForModEvent",  "IsHelmet",  "SetHarvested",  "GetStaffMult",
    		"GetAliasByName",  "LogicalOr",  "GetKeyword",  "SetWeightClass",
    		"GetGreen",  "GetMappedKey",  "SetMeleeBashAttackMult",  "ModAR",
    		"GetModCount",  "SetGoldValue",  "HoldKey",  "IsJewelry",
    		"IsBoots",  "SetValidRaces",  "SetWeaponType",  "SetBool",
    		"IsClothing",  "SetMeleeBashPowerAttackMult",  "IsClothingPoor",  "SetNthTintMaskTexturePath",
    		"GetNthIngredientQuantity",  "SetGameSettingFloat",  "GetItemCharge",  "SetGameSettingString",
    		"RemoveSlotFromMask",  "SetHairColor",  "SetItemCharge",  "GetShoutMult",
    		"GetCombatStyle",  "GetMeleeBashPowerAttackMult",  "GetBlue",  "SetAvoidThreatChance",
    		"GetNthArmorAddon",  "GetMeleeSpecialAttackMult",  "RegisterForMenu",  "GetMappedControl",
    		"IsCuirass",  "GetModelNumTextureSets",  "GetCloseRangeDuelingCircleMult",  "MakeNonChildRace",
    		"SetCloseRangeFlankingFlankDistance",  "SetMeleeSpecialAttackMult",  "LogicalNot",  "MakeNoKnockdowns",
    		"SetRangedMult",  "SetNumber",  "IsClothingFeet",  "SetBlue",
    }
     
     
    -- The following used to be generated from the Creation Kit wiki
    -- http://www.creationkit.com/Category:Events
    --
    -- Now generated from a collection of Papyrus files
    EventData = CaseType {
    -- Generated data for Skyrim
    		"OnAnimationEvent",  "OnSell",  "OnObjectUnequipped",  "OnStoryIncreaseLevel",
    		"OnTranslationAlmostComplete",  "OnPackageChange",  "OnTranslationFailed",  "OnUpdate",
    		"OnRelease",  "OnUpdateGameTime",  "OnRead",  "OnDying",
    		"OnStoryCraftItem",  "OnPackageStart",  "OnAnimationEventUnregistered",  "OnClose",
    		"OnTriggerLeave",  "OnPackageEnd",  "OnStoryIncreaseSkill",  "OnItemAdded",
    		"OnTranslationComplete",  "OnWardHit",  "OnTrigger",  "OnItemRemoved",
    		"OnStoryFlatterNPC",  "OnDetachedFromCell",  "OnStoryPickLock",  "OnCellLoad",
    		"OnUnequipped",  "OnSleepStop",  "OnSpellCast",  "OnCombatStateChanged",
    		"OnEffectFinish",  "OnLocationChange",  "OnRaceSwitchComplete",  "OnEquipped",
    		"OnCellAttach",  "OnStoryHello",  "OnActivate",  "OnStoryNewVoicePower",
    		"OnStoryCure",  "OnTrapHitStop",  "OnStoryInfection",  "OnStoryEscapeJail",
    		"OnSleepStart",  "OnLoad",  "OnTrackedStatsEvent",  "OnStoryDialogue",
    		"OnPlayerBowShot",  "OnStoryPlayerGetsFavor",  "OnEffectStart",  "OnPlayerLoadGame",
    		"OnAttachedToCell",  "OnSit",  "OnGetUp",  "OnCellDetach",
    		"OnObjectEquipped",  "OnMagicEffectApply",  "OnTriggerEnter",  "OnGrab",
    		"OnStoryBribeNPC",  "OnStoryIntimidateNPC",  "OnUnload",  "OnLockStateChanged",
    		"OnLostLOS",  "OnDestructionStageChanged",  "OnEnterBleedout",  "OnDeath",
    		"OnReset",  "OnStoryActivateActor",  "OnOpen",  "OnContainerChanged",
    		"OnHit",  "OnGainLOS",
     
    -- Generated data for SkyUI
    		"OnOptionColorOpen",  "OnConfigOpen",  "OnConfigInit",  "OnOptionSelect",
    		"OnGameReload",  "OnOptionMenuOpen",  "OnInit",  "OnPageReset",
    		"OnOptionDefault",  "OnOptionMenuAccept",  "OnOptionSliderAccept",  "OnOptionHighlight",
    		"OnOptionKeyMapChange",  "OnConfigRegister",  "OnConfigClose",  "OnVersionUpdate",
    		"OnOptionSliderOpen",  "OnOptionColorAccept",
     
    -- Generated data for SKSE
    		"OnMenuClose",  "OnControlDown",  "OnKeyDown",  "OnKeyUp",
    		"OnMenuOpen",  "OnControlUp",
    }
     
    IndentData = {}
    IndentData[TokenData.LEFTPAREN] = IndentRight
    IndentData[TokenData.LEFTBRACKET] = IndentRight
    IndentData[TokenData.LEFTWING] = IndentRight
     
    IndentData[TokenData.RIGHTPAREN] = IndentLeft
    IndentData[TokenData.RIGHTBRACKET] = IndentLeft
    IndentData[TokenData.RIGHTWING] = IndentLeft
    --
    --
    --
     
    function Lexer.NumberExponent(text, pos)
            local function IntExponent(text, pos)
                    while true do
                            local byte = StrByte(text, pos)
                            if not byte then
                                    return TokenData.NUMBER, pos
                            end
     
                            if byte >= ByteData.NUM_0 and byte <= ByteData.NUM_9 then
                                    pos = pos + 1
                            else
                                    return TokenData.NUMBER, pos
                            end
                    end
            end
     
     
            local byte = StrByte(text, pos)
            if not byte then
                    return TokenData.NUMBER, pos
            end
     
            if byte == ByteData.MINUS then
                    byte = StrByte(text, pos + 1)
                    if byte == ByteData.MINUS then
                            return TokenData.NUMBER, pos
                    end
                    return IntExponent(text, pos + 1)
            end
     
            return IntExponent(text, pos)
    end
     
    function Lexer.NumberFraction(text, pos)
            while true do
                    local byte = StrByte(text, pos)
                    if not byte then
                            return TokenData.NUMBER, pos
                    end
     
                    if byte >= ByteData.NUM_0 and byte <= ByteData.NUM_9 then
                            pos = pos + 1
                    elseif byte == ByteData.E or byte == ByteData.e then
                            return Lexer.NumberExponent(text, pos + 1)
                    else
                            return TokenData.NUMBER, pos
                    end
            end
    end
     
    function Lexer.Number(text, pos)
            while true do
                    local byte = StrByte(text, pos)
                    if not byte then
                            return TokenData.NUMBER, pos
                    end
     
                    if byte >= ByteData.NUM_0 and byte <= ByteData.NUM_9 then
                            pos = pos + 1
                    elseif byte == ByteData.PERIOD then
                            return Lexer.NumberFraction(text, pos + 1)
                    elseif byte == ByteData.E or byte == ByteData.e then
                            return Lexer.NumberExponent(text, pos + 1)
                    else
                            return TokenData.NUMBER, pos
                    end
            end
    end
     
    function Lexer.Identifier(text, pos)
            while true do
                    local byte = StrByte(text, pos)
     
                    if not byte or
                    newline[byte] or
                    whitespaces[byte] or
                    SymbolData[byte] then
                            return TokenData.IDENTIFIER, pos
                    end
                    pos = pos + 1
            end
    end
     
     
    function Lexer.Comment(text, pos)
            local byte = StrByte(text, pos)
     
            while true do
                    byte = StrByte(text, pos)
                    if not byte then
                            return TokenData.COMMENT_SHORT, pos
                    end
                    if newline[byte] then
                            return TokenData.COMMENT_SHORT, pos
                    end
                    pos = pos + 1
            end
    end
     
    function Lexer.String(text, pos, character)
            local even = true
            while true do
                    local byte = StrByte(text, pos)
                    if not byte then
                            return TokenData.STRING, pos
                    end
     
                    if byte == character then
                            if even then
                                    return TokenData.STRING, pos + 1
                            end
                    end
                    if byte == ByteData.BACKSLASH then
                            even = not even
                    else
                            even = true
                    end
     
                    pos = pos + 1
            end
    end
     
    function Lexer.GetToken(text, pos)
            local byte = StrByte(text, pos)
            if not byte then
                    return nil
            end
     
            if newline[byte] then
                    return TokenData.LINEBREAK, pos + 1
            end
     
            if whitespaces[byte] then
                    while true do
                            pos = pos + 1
                            byte = StrByte(text, pos)
                            if not byte or not whitespaces[byte] then
                                    return TokenData.WHITESPACE, pos
                            end
                    end
            end
     
            local token = SymbolData[byte]
            if token then
                    if token ~= -1 then
                            return token, pos + 1
                    end
     
                    if byte == ByteData.OR then
                            byte = StrByte(text, pos + 1)
                            if byte == ByteData.OR then
                                    return TokenData.OR, pos + 2
                            end
                            return TokenData.UNKNOWN, pos + 1
                    end
     
                    if byte == ByteData.AND then
                            byte = StrByte(text, pos + 1)
                            if byte == ByteData.AND then
                                    return TokenData.AND, pos + 2
                            end
                            return TokenData.UNKNOWN, pos + 1
                    end
     
                    if byte == ByteData.SEMICOLON then
                            return Lexer.Comment(text, pos + 1)
                    end
     
                    if byte == ByteData.SINGLE_QUOTE then
                            return Lexer.String(text, pos + 1, ByteData.SINGLE_QUOTE)
                    end
     
                    if byte == ByteData.DOUBLE_QUOTE then
                            return Lexer.String(text, pos + 1, ByteData.DOUBLE_QUOTE)
                    end
     
                    if byte == ByteData.LEFTBRACKET then
                            return TokenData.LEFTBRACKET, pos + 1
                    end
     
                    if byte == ByteData.EQUALS then
                            byte = StrByte(text, pos + 1)
                            if not byte then
                                    return TokenData.ASSIGNMENT, pos + 1
                            end
                            if byte == ByteData.EQUALS then
                                    return TokenData.EQUALITY, pos + 2
                            end
                            return TokenData.ASSIGNMENT, pos + 1
                    end
     
                    if byte == ByteData.PERIOD then
                            byte = StrByte(text, pos + 1)
                            if not byte then
                                    return TokenData.PERIOD, pos + 1
                            end
                            if byte >= ByteData.NUM_0 and byte <= ByteData.NUM_9 then
                                    return Lexer.NumberFraction(text, pos + 2)
                            end
                            return TokenData.PERIOD, pos + 1
                    end
     
                    if byte == ByteData.LESSTHAN then
                            byte = StrByte(text, pos + 1)
                            if byte == ByteData.EQUALS then
                                    return TokenData.LTE, pos + 2
                            end
                            return TokenData.LT, pos + 1
                    end
     
                    if byte == ByteData.GREATERTHAN then
                            byte = StrByte(text, pos + 1)
                            if byte == ByteData.EQUALS then
                                    return TokenData.GTE, pos + 2
                            end
                            return TokenData.GT, pos + 1
                    end
     
                    if byte == ByteData.EXCLAMATIONMARK then
                            byte = StrByte(text, pos + 1)
                            if byte == ByteData.EQUALS then
                                    return TokenData.NE, pos + 2
                            end
                            return TokenData.NOT, pos + 1
                    end
     
                    return TokenData.UNKNOWN, pos + 1
            elseif byte >= ByteData.NUM_0 and byte <= ByteData.NUM_9 then
                    return Lexer.Number(text, pos + 1)
            else
                    return Lexer.Identifier(text, pos + 1)
            end
    end
     
    --
    --
    --
     
    local function IndentTabs(n, unused)
            return StrRep("\t", n)
    end
     
    local function IndentSpaces(a, b)
            return StrRep(" ", a*b)
    end
     
    function IndentCode(code, width, position)
            local Indentfunction
     
     
            local tsize2 = 0
            local totalLen2 = 0
     
            local newPosition
            local bNewPosition = false
            local prevTokenWidth = 0
     
            local pos = 1
            local IndentLevel = 0
     
            local bNonWhitespace = false
            local bIndentRight = false
            local preIndent = 0
            local postIndent = 0
     
            local tsize = 0
            local totalLen = 0
     
            if width == nil then
                    width = defaultTabWidth
            end
            if width then
                    Indentfunction = IndentSpaces
            else
                    Indentfunction = IndentTabs
            end
     
            EraseTable(codeTable01)
            EraseTable(codeTable02)
     
            while true do
                    if position and not newPosition and pos >= position then
                            if pos == position then
                                    newPosition = totalLen + totalLen2
                            else
                                    newPosition = totalLen + totalLen2
                                    local diff = pos - position
                                    if diff > prevTokenWidth then
                                            diff = prevTokenWidth
                                    end
                                    newPosition = newPosition - diff
                            end
                    end
     
                    prevTokenWasColored = false
                    prevTokenWidth = 0
     
                    local tokenType, nextPos = Lexer.GetToken(code, pos)
     
                    if not tokenType or tokenType == TokenData.LINEBREAK then
                            IndentLevel = IndentLevel + preIndent
                            if IndentLevel < 0 then IndentLevel = 0 end
     
                            local s = Indentfunction(IndentLevel, width)
     
                            tsize = tsize + 1
                            codeTable01[tsize] = s
                            totalLen = totalLen + StrLen(s)
     
                            if newPosition and not bNewPosition then
                                    newPosition = newPosition + StrLen(s)
                                    bNewPosition = true
                            end
     
     
                            for k, v in next,codeTable02 do
                                    tsize = tsize + 1
                                    codeTable01[tsize] = v
                                    totalLen = totalLen + StrLen(v)
                            end
     
                            if not tokenType then
                                    break
                            end
     
                            tsize = tsize + 1
                            codeTable01[tsize] = StrSub(code, pos, nextPos - 1)
                            totalLen = totalLen + nextPos - pos
     
                            IndentLevel = IndentLevel + postIndent
                            if IndentLevel < 0 then IndentLevel = 0 end
     
                            EraseTable(codeTable02)
                            tsize2 = 0
                            totalLen2 = 0
     
                            bNonWhitespace = false
                            bIndentRight = false
                            preIndent = 0
                            postIndent = 0
                    elseif tokenType == TokenData.WHITESPACE then
                            if bNonWhitespace then
                                    prevTokenWidth = nextPos - pos
     
                                    tsize2 = tsize2 + 1
                                    local s = StrSub(code, pos, nextPos - 1)
                                    codeTable02[tsize2] = s
                                    totalLen2 = totalLen2 + StrLen(s)
                            end
                    else
                            bNonWhitespace = true
     
                            local str = StrSub(code, pos, nextPos - 1)
     
                            prevTokenWidth = nextPos - pos
     
                            local indentTable
                            if tokenType == TokenData.IDENTIFIER then
                                    local tempstr = string.lower(str)
     
                                    local RefName,RefOrig,RefType = FindKeywordReference(tempstr)
                                    indentTable = RefType
     
                                    if ApiData[tempstr] then
                                            str = ApiData[tempstr]
                                    elseif RefName then
                                            str = RefOrig
                                    elseif ScriptObjects[tempstr] then
                                            str = ScriptObjects[tempstr]
                                    elseif EventData[tempstr] then
                                            str = EventData[tempstr]
                                    end
                            else
                                    indentTable = IndentData[tokenType]
                            end
     
                            if indentTable then
                                    if bIndentRight then
                                            postIndent = postIndent + indentTable[1] + indentTable[2]
                                    else
                                            local pre = indentTable[1]
                                            local post = indentTable[2]
                                            if post > 0 then
                                                    bIndentRight = true
                                            end
                                            preIndent = preIndent + pre
                                            postIndent = postIndent + post
                                    end
                            end
     
                            if FindKeywordReference(str) then
                                    tokenType = TokenData.KEYWORD
                            end
     
                            tsize2 = tsize2 + 1
                            codeTable02[tsize2] = str
                            totalLen2 = totalLen2 + nextPos - pos
                    end
                    pos = nextPos
            end
            return table.concat(codeTable01), newPosition
    end
     
    local function PrintUsage()
            io.stderr:write(StrFormat(
                            "Usage %s [options] [script.psc]\n"..
                            "Available options are:\n"..
                            "	-s[number]  Spacing to indent, TAB spacing is default\n"..
                            "	-b          Backup the Papyrus Script\n"
            , program))
            io.stderr:flush()
    end
     
    if _G.arg and _G.arg[0] and #_G.arg[0] > 0 then
            program = _G.arg[0]
    end
     
    local argv = {...}
     
     
    local function CollectArgs(argv,p)
            local i = 1
            while i <= #argv do
                    if StrSub(argv[i],1,1) ~= '-' then
                            return i
                    end
     
                    local option = StrSub(argv[i],2,2)
                    if option == 'b' then
                            if #argv[i] > 2 then return -1 end
                            p[option] = true
                    elseif option == 's' then
                            local spacing = tonumber(StrSub(argv[i],3,7))
                            if type(spacing) ~= 'number' then return -1 end
                            p['spacing'] = spacing
                            p[option] = true
                    else
                            return -1
                    end
                    i = i + 1
            end
            return 0
    end
     
    HasOptions = {
            b = false,
            s = false
    }
     
    local PapyrusScript = CollectArgs(argv,HasOptions)
     
    if PapyrusScript <= 0 then
            PrintUsage()
            OsExit(1)
    end
     
    local script = argv[PapyrusScript]
    local spacing = HasOptions['spacing'] or nil
     
    local TempFileName = os.tmpname()
    local PapyrusFile = io.open(script, "r")

    if PapyrusFile == nil then
            OsError('Could not open file for reading "'..script..'"')
    end
     
    local TempFile = io.open(TempFileName,"w")
    if TempFile == nil then
            OsError('Could not open file for reading "'..TempFileName..'"')
    end

    local Code = PapyrusFile:read("*a")
     
    if HasOptions.b then
            local BackupFile = io.open(script..'.backup',"w")
            if BackupFile == nil then
                    OsError('Could not open file for reading "'..script..'.backup'..'"')
            end
            BackupFile:write(Code)
            BackupFile:close()
    end
     
    local IndentedCode = IndentCode(Code, spacing)
     
    TempFile:write(IndentedCode)
    PapyrusFile:close()
    TempFile:close()
     
    os.remove(script)
    os.rename(TempFileName,script)
