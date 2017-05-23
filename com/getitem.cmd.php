<?php
require_once($PathLoc."/cls/mttascks.class.php");
$lid=$_POST["id"];
$ppanel=array();
$tsk=new mtTascks();
$tsk->GetItem($lid);
$ppanel[0]="tascks:/";
if($lid!="0")$ppanel[0]=$tsk->GetPath();

$ttpl= new mtTascksTPL();
$tsk=new mtTascks();
$tsk->SelectFrom($lid);
$arr=$tsk->arr;
$ttpl->Select();
$tpla=$ttpl->arr;

for($i=0;$i<count($arr);$i++){
    $tsk->GetItem($arr[$i]["id"]);
    $ltpl=$tpla[$tsk->tpl];
    $par=array();
    $par["id"]=$tsk->id;
    $par["name"]=$tsk->name;
    $par["descr"]=$tsk->descr;
    $par["time"]=$tsk->time;
    $par["tpl"]=$tsk->tpl;
    $par["tpln"]=$tpla[$tsk->tpl];
    $par["isgroup"]=$tsk->isgroup;
    if($tsk->isgroup=="Y")$par["tpln"]="";
    $par["parent"]=$tsk->parent;
    $par["user"]=$tsk->user;
    $par["state"]=$tsk->state;
    $ppanel[]=json_encode($par);
}
echo json_encode($ppanel);  
?>
