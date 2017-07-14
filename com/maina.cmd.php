<?php
require_once("cls/mttascks.class.php");
require_once("cls/mtwkpanel.class.php");
require_once("cls/mtwkstate.class.php");
if(!isset($_POST["type"])){echo "ER";exit;}
$tpl=$_POST["type"];
$lid=$_POST["id"];
$wkp_id=$_POST["wkp"];
$tsk=new mtTascks();
$tsk->GetItem($lid);
$wks=new mtWKState();
$wkp=new mtWKPanel();

switch($tpl){
    case "rem":
        $wkp->Delete($wkp_id);
        $tsk->StopDo();
        $wks->Delete($lid);
    break;
    case "cicl":
        $wkp->Delete($wkp_id);
        $wks->Update3($lid);
    break;
}
?>
