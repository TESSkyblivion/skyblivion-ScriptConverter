<?php
/**
 * Created by PhpStorm.
 * User: Ormin
 */

namespace Ormin\OBSLexicalParser\TES4\Lexer;

use Dissect\Lexer\StatefulLexer;

class OBScriptLexer extends StatefulLexer
{

#    const FUNCTION_REGEX = "#^(?P<called_upon>[a-zA-Z0-9:]*\.\]?)?(?P<function_name>[a-zA-Z0-9:]*) ?(?P<function_arg_1>(\"['.,! a-zA-Z0-9:-]*\")|([a-zA-Z0-9:-]*))?,? ?(?P<function_arg_2>(\"['.,! a-zA-Z0-9:-]*\")|([a-zA-Z0-9:-]*))?,? ?(?P<function_arg_3>(\"['.,! a-zA-Z0-9:-]*\")|([a-zA-Z0-9:-]*))?,? ?(?P<function_arg_4>(\"['.,! a-zA-Z0-9:-]*\")|([a-zA-Z0-9:-]*)?)?#i";

    const FUNCTION_REGEX = '/^(activate|addachievement|additem|addscriptpackage|addspell|addtopic|autosave|cast|clearownership|closecurrentobliviongate|closeobliviongate|completequest|createfullactorcopy|deletefullactorcopy|disablelinkedpathpoints|disableplayercontrols|disable|dispel|dropme|drop|duplicateallitems|enablefasttravel|enablelinkedpathpoints|enableplayercontrols|enable|equipitem|essentialdeathreload|evp|evaluatepackage|forceactorvalue|forceav|forcecloseobliviongate|forceweather|fw|getactionref|getav|getactorvalue|getamountsoldstolen|getangle|getarmorrating|getattacked|getbaseav|getbaseactorvalue|getbuttonpressed|getclothingvalue|getcombattarget|getcontainer|getcrimegold|getcrimeknown|getcrime|getcurrentaipackage|getcurrentaiprocedure|getcurrenttime|getdayofweek|getdeadcount|getdead|getdestroyed|getdetected|getdetectionlevel|getdisabled|getdisposition|getdistance|getequipped|getfactionrank|getforcesneak|getgamesetting|getgold|getgs|getheadingangle|getincell|getinfaction|getinsamecell|getinworldspace|getisalerted|getiscreature|getiscurrentpackage|getiscurrentweather|getisid|getisplayablerace|getisplayerbirthsign|getisrace|getisreference|getissex|getitemcount|getknockedstate|getlevel|getlocked|getlos|getopenstate|getparentref|getpcexpelled|getpcfactionmurder|getpcfactionattack|getpcfactionsteal|getpcfame|getpcinfamy|getpcisrace|getpcissex|getpcmiscstat|getplayercontrolsdisabled|getplayerinseworld|getpos|getquestrunning|getrandompercent|getrestrained|getsecondspassed|this|getself|getshouldattack|getsitting|getsleeping|getstagedone|getstage|getstartingangle|getstartingpos|gettalkedtopc|getweaponanimtype|gotojail|hasmagiceffect|hasvampirefed|isactionref|isactordetected|isactorusingatorch|isactor|isanimplaying|isessential|isguard|isidleplaying|isincombat|isindangerouswater|isininterior|isowner|ispcamurderer|ispcsleeping|isplayerinjail|israining|isridinghorse|issneaking|isspelltarget|isswimming|istalking|istimepassing|isweaponout|isxbox|kill|lock|look|menumode|messagebox|message|modactorvalue|modav|modcrimegold|moddisposition|modfactionreaction|modpcfame|modpcinfamy|modpcmiscstat|modpcskill|movetomarker|moveto|payfine|pickidle|placeatme|playgroup|playmagiceffectvisuals|pme|playmagicshadervisuals|pms|playsound3d|playsound|purgecellbuffers|pcb|pushactoraway|refreshtopiclist|releaseweatheroverride|removeallitems|removeitem|removeme|removescriptpackage|removespell|reset3dstate|resetfalldamagetimer|resethealth|resetinterior|resurrect|rotate|sayto|say|scripteffectelapsedseconds|sendtrespassalarm|setactoralpha|saa|setactorfullname|setactorrefraction|setactorsai|setactorvalue|setav|setalert|setallreachable|setallvisible|setangle|setcellpublicflag|setclass|setcombatstyle|setcrimegoldnonviolent|setcrimegold|setdestroyed|setdoordefaultopen|setessential|setfactionrank|setfactionreaction|setforcerun|setforcesneak|setghost|setignorefriendlyhits|setinvestmentgold|setitemvalue|setnoavoidance|setnorumors|setopenstate|setownership|setpackduration|setpcexpelled|setpcfactionattack|setpcfactionmurder|setpcfactionsteal|setpcfame|setpcinfamy|setplayerinseworld|setpos|setquestobject|setrestrained|setrigidbodymass|setscale|setsceneiscomplex|setshowquestitems|setstage|setunconscious|setweather|showbirthsignmenu|showclassmenu|showdialogsubtitles|showenchantment|showmap|showracemenu|showspellmaking|sme|startcombat|startconversation|startquest|stopcombatalarmonactor|stopcombat|scaonactor|stoplook|stopmagiceffectvisuals|stopmagicshadervisuals|stopwaiting|sms|stopquest|sw|trapupdate|triggerhitshader|unequipitem|unlock|wait|wakeuppc|yield)/i';

    private function addCommentsRecognition() {
        $this
        ->regex('Comment','/^;.*/')
        ->token('(')
        ->token(')')
        ->token(',')
        ->token('TimerDescending') //idk wtf is that.
        ->skip('WSP','WSPEOL','Comment', "",'to ','(',")",",","TimerDescending","NotNeededTrash");
    }

    
    protected function buildObscriptLexer() {
        //Global scope.
        $this->state('globalScope');
        $this->regex('WSP', "/^[ \r\n\t]+/");
        $this->regex('ScriptHeaderToken', '/^(scn|scriptName)/i')->action('ScriptHeaderScope');
        $this->regex('VariableDeclarationType','/^(ref|short|long|float|int)/i')->action('VariableDeclarationScope');
        $this->regex('BlockStart','/^Begin/i')->action('BlockStartNameScope');
        $this->addCommentsRecognition();

        $this->state('ExpressionScope')
            ->regex('WSP', "/^[ \t]+/")
            ->regex('NWL','/^[\r\n]+/')->action('BlockScope')
            ->regex('FunctionCallToken',self::FUNCTION_REGEX)->action('FunctionScope')
            ->regex('Boolean','#^(true|false)#i')
            ->regex('ReferenceToken',"#^[a-z][a-zA-Z0-9]*#i")
            ->regex('Float','#^(-)?([0-9]*)\.[0-9]+#i')
            ->regex('Integer','#^(-)?(0|[1-9][0-9]*)#i')
            ->regex('String','#^"((?:(?<=\\\\)["]|[^"])*)"#i')
            ->regex('TokenDelimiter',"#\.#i")
            ->token('+')
            ->token('-')
            ->token('*')
            ->token('/')
            ->token('==')
            ->token('>=')
            ->token('<=')
            ->token('>')
            ->token('<')
            ->token('!=')
            ->token('&&')
            ->token('||')
            ->skip('NWL');

        $this->addCommentsRecognition();

        $this->state('BlockScope')
            ->regex('WSP','/^[ \t\r\n]+/')
            ->regex('BlockEnd','/^end( [a-zA-Z]+)?/i')->action('BlockEndScope')
            ->regex('BranchElseifToken','/^else[ ]?if(\()?[ \r\n\t]+/i')->action('ExpressionScope')
            ->regex('BranchStartToken','/^if(\()?[ \r\n\t]+/i')->action('ExpressionScope')
            ->regex('BranchElseToken','/^else/i')
            ->regex('BranchEndToken','/^endif/i')
            ->regex('SetInitialization','/^set[ \t]+/i')->action('SetScope')
            ->regex('ReturnToken','/^return/i')
            ->regex('Float','#^(-)?([0-9]*)\.[0-9]+#i')
            ->regex('Integer','#^(-)?(0|[1-9][0-9]*)#i')
            ->regex('String','#^"((?:(?<=\\\\)["]|[^"])*)"#i')
            ->regex('Boolean','#^(true|false)#i')
            ->regex('FunctionCallToken',self::FUNCTION_REGEX)->action('FunctionScope')
            ->regex('LocalVariableDeclarationType','/^(ref|short|long|float|int)/i')->action('VariableDeclarationScope')
            ->regex('ReferenceToken',"#^[a-z][a-zA-Z0-9]*#i")
            ->regex('TokenDelimiter',"#^\.#i");

        $this->addCommentsRecognition();

        $this->state('FunctionScope')
            ->regex('WSP','/^[ \t]+/')
            ->regex('NWL','/^[\r\n]+/')->action('BlockScope')
            ->regex('ReturnToken','/^return/i')
            ->regex('Float','#^(-)?([0-9]*)\.[0-9]+#i')
            ->regex('Integer','#^(-)?(0|[1-9][0-9]*)#i')
            ->regex('String','#^"((?:(?<=\\\\)["]|[^"])*)"#i')
            ->regex('Boolean','#^(true|false)#i')
            ->regex('FunctionCallToken',self::FUNCTION_REGEX)
            ->regex('ReferenceToken',"#^[a-z][a-zA-Z0-9]*#i")
            ->regex('TokenDelimiter',"#^\.#i")
            ->token('+')->action(StatefulLexer::POP_STATE)
            ->token('-')->action(StatefulLexer::POP_STATE)
            ->token('*')->action(StatefulLexer::POP_STATE)
            ->token('/')->action(StatefulLexer::POP_STATE)
            ->token('==')->action(StatefulLexer::POP_STATE)
            ->token('>=')->action(StatefulLexer::POP_STATE)
            ->token('<=')->action(StatefulLexer::POP_STATE)
            ->token('>')->action(StatefulLexer::POP_STATE)
            ->token('<')->action(StatefulLexer::POP_STATE)
            ->token('!=')->action(StatefulLexer::POP_STATE)
            ->token('&&')->action(StatefulLexer::POP_STATE)
            ->token('||')->action(StatefulLexer::POP_STATE);


           $this->addCommentsRecognition();


        $this->state('SetScope')
             ->regex('ReferenceToken',"#^[a-z][a-zA-Z0-9]*#i")
             ->regex('TokenDelimiter',"#\.#i")
             ->token('To ')->action('ExpressionScope')
             ->token('to ')->action('ExpressionScope')
             ->regex('WSP', "/^[ \t]+/")
             ->regex('NWL','/^[\r\n]+/')->action(StatefulLexer::POP_STATE);
        $this->addCommentsRecognition();

        $this->state('BlockEndScope')
            ->regex('WSP', "/^[ \t]+/")
#            ->regex('NotNeededTrash','/^([a-zA-Z0-9_-]+)/i') I kinda forgot why it is here..
            ->regex('WSPEOL', "/^[\r\n]+/")->action('globalScope');
        $this->addCommentsRecognition();

        $this->state('BlockStartNameScope')
            ->regex('WSP', "/^[ \t]+/")
            ->regex('BlockType','/^([a-zA-Z0-9_-]+)/i')->action('BlockStartParameterScope');
        $this->addCommentsRecognition();

        $this->state('BlockStartParameterScope')
            ->regex('WSP', "/^[ \t]+/")
            ->regex('BlockParameterToken', "#[a-zA-Z0-9_-]+#i")
            ->regex('WSPEOL', "/^[\r\n]+/")->action('BlockScope');
        $this->addCommentsRecognition();

        $this->state('ScriptHeaderScope')
            ->regex('WSP', "/^[ \r\n\t]+/")
            ->regex('ScriptName','/^([a-zA-Z0-9_-]+)/i')->action(StatefulLexer::POP_STATE);
        $this->addCommentsRecognition();

        $this->state('VariableDeclarationScope')
            ->regex('WSP', "/^[ \r\n\t]+/")
            ->regex('VariableName','/^([a-zA-Z0-9_-]+)/i')->action(StatefulLexer::POP_STATE);
        $this->addCommentsRecognition();


    }
}