<?php
set_time_limit(0);
error_reporting(E_ALL);
//include("Jabber/SendMessage.php");
//function JabberNotify($user, $message){OverviewMessage($message, "", "", $user);}
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

while(!file_exists($signal)){
    $curtime=time();
    file_put_contents($exFile,$curtime);
    $usState=new mtUSState();
    $usState->CloseUserByTimeOut();
    $wks=new mtWKState();
    $wks->DoUsersTasck();
    $wks->DoGroupsTasck();
    $wks->DoOtherTasck();
    sleep(60);
}
$lstr="SHUTDOWN at SIGNAL";
$Log->Append($lstr);
unlink($signal);
unlink($exFile);
exit(0);

?>
