<?php
require_once("$PathLoc/cls/mttascks.class.php");
require_once("$PathLoc/cls/mtwkpanel.class.php");
if(!isset($_POST["tpl"])){echo "err";exit;}
$tpl=$_POST["tpl"];
$tsk=new mtTascks();
switch($tpl){
    case "addresume":
        $ldescr=$_POST["descr"];
        $lresume=$_POST["resume"];
        $lid=$_POST["id"];
        $wkp=new mtWKPanel();
        $ret=$wkp->GetItem($lid);
        $wkp->Delete($lid);
        $tsk=new mtTascks();
        $tsk->GetItem($ret["tasck_id"]);
        $tskms=new mtTascksMS();
        $tskms->Select($tsk->id);
        $tskms->AddNew();
        $tskms->descr=$ldescr;
        $tskms->reason=$lresume;
        $tskms->Save();
    break;
}
?>
