<?php
require_once($PathLoc."/cls/mttascks.class.php");
if(isset($_POST["lp"]))$_SESSION['lp']=$_POST["lp"];
if(isset($_POST["rp"]))$_SESSION['rp']=$_POST["rp"];
$lid=@$_POST["id"];
$ltpl=@$_POST["ltpl"];
$lgrp=@$_POST["lgrp"];
$name=@$_POST["name"];
$descr=@$_POST["descr"];
$lpar=@$_POST["param"];

$lts=new mtTascks();

if($lid=="0"){
    $lts->descr=$descr;
    $lts->name=$name;
    $lts->parent=$lgrp;
    $lts->tpl=$ltpl;
    $lts->user=$user->id;
    $lts->NewTasck();
    echo $lts->id;
    exit;
}else{
    $lts->GetItem($lid);
    $lts->descr=$descr;
    $lts->name=$name;
   $lts->Update();
   $ppar=json_decode($lpar);
   $obj = new mtTascksParam();
   $obj->tasck_id=$lts->id;
   for($i=0;$i<count($ppar);$i++){
       $bpar=$ppar[$i][0];
       $bval=$ppar[$i][1];
       $obj->SetParam($bpar,$bval);
   }
   echo "tasck";
   exit; 
}

echo "0";
?>
