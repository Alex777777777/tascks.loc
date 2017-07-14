<?php
set_time_limit(0);
error_reporting(E_ALL);
include("Jabber/SendMessage.php");
function JabberNotify($user, $message){OverviewMessage($message, "", "", $user);}
function SendMsgList($arr){
    global $Log;
    if(count($arr)){
        $ltsk=new mtTascks();
        $lusr= new mtUsers();
        $lmsg="Вам назначено задание №%u";
        $llog="Add task #%u for user '%s'";    
        foreach($arr as $val){
            $idtsk=$val[0];
            $idusr=$val[1];
            $lusr->GetItem($idusr);
            if($lusr->jabber!=""){
                JabberNotify($lusr->jabber, sprintf ( $lmsg ,$idtsk));
            }
            $Log->Append(sprintf ( $llog ,$idtsk,$lusr->name));
        }
    }
}
function LogCloseUserList($arr){
    global $Log;
    if(count($arr)){
        $lusr= new mtUsers();
        $llog="Close User '%s' by timeout.";    
        foreach($arr as $val){
            $idusr=$val[0];
            $lusr->GetItem($idusr);
            $Log->Append(sprintf ( $llog ,$idtsk,$lusr->name));
        }
    }
}
$PathLoc=__DIR__;
$logDir="$PathLoc/log";
$logFile=$logDir."/robot.log";
$exFile=$logDir."/ex.log";
$signal=$logDir."/signal";

require_once("$PathLoc/cls/safemysql.class.php");
$mdb = new SafeMySQL(require("cls/db_param.php"));
require_once("$PathLoc/cls/mtusers.class.php");
require_once("$PathLoc/cls/mttascks.class.php");
require_once("$PathLoc/cls/mtusstate.class.php");
require_once("$PathLoc/cls/mtwkpanel.class.php");
require_once("$PathLoc/cls/mtwkstate.class.php");
require_once("$PathLoc/cls/log.class.php");

$Log=new mtLog($logFile);
$param=require("$PathLoc/cls/jabber.param.php");

while(!file_exists($signal)){
    $curtime=time();
    file_put_contents($exFile,$curtime);
    $usState=new mtUSState();
    $arr=$usState->CloseUserByTimeOut();
    LogCloseUserList($arr);
    $wks=new mtWKState();
    $arr=$wks->DoUsersTasck();
    SendMsgList($arr);
    $arr=$wks->DoGroupsTasck();
    SendMsgList($arr);
    $arr=$wks->DoOtherTasck();
    SendMsgList($arr);
    sleep(60);
}
$lstr="SHUTDOWN at SIGNAL";
$Log->Append($lstr);
unlink($signal);
unlink($exFile);
exit(0);

?>
