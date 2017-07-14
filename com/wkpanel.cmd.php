<?php
require_once("$PathLoc/cls/mttascks.class.php");
require_once("$PathLoc/cls/mtwkpanel.class.php");
require_once("$PathLoc/cls/mtwkstate.class.php");
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
        $tsk=new mtTascks();
        $tsk->GetItem($ret["tasck_id"]);
        $tskms=new mtTascksMS();
        $tskms->Select($tsk->id);
        $tskms->AddNew();
        $tskms->descr=$ldescr;
        $tskms->reason=$lresume;
        $tskms->Save();
        $wks=new mtWKState();
        $lst=$lresume;
        if($lst>=10)$lst=20;
        $wks->UpdateStatus2($tsk->id,$lst);
        $wkp->Delete($lid);
        if($lst==20)$tsk->StopDo();
    break;
}
?>
