<?php
require_once($PathLoc."/cls/mttascks.class.php");
$tpl=$_POST["tpl"];
if(!$tpl){echo "ER"; exit;}
if($tpl=="newtpl"){
    $name=$_POST['name'];
    $descr=$_POST['descr'];
    $tmpl=new mtTascksTPL();
    $tmpl->AddNew($name,$descr);
    echo "OK";
    exit;
}
if($tpl=="newpar"){
    $name=$_POST['name'];
    $key=$_POST['key'];
    $id=$_POST['id'];
    $tmpl=new mtTascksTPL();
    $tmpl->AddNewParam($id,$key,$name);
    echo "OK";
    exit;
}

?>
