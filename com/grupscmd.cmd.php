<?php
if(!isset($_POST["tpl"])){echo "err";exit;}
$tpl=$_POST["tpl"];
$lusr=new mtUsers();
switch($tpl){
    case "getgpuser":
        $lid=$_POST["id"];
        $lusr->SelectUserGp($lid);
        $ret="";
        for($i=0;$i<count($lusr->arr);$i++){
            $row=$lusr->arr[$i];
            $lid=$row['id'];
            $lname=$row['name'];
            $lpar=$row['parent'];
            $lchk="checked";
            if($lpar=="0")$lchk="";
            $ret.="<div class='user_item' data-id='$lid'><input type='checkbox' $lchk><span>$lname</span></div>";
        }
        echo $ret;
        exit;
    break;
    case "addgrp":
        $lname=$_POST["name"];
        echo $lusr->NewGroup($lname);
        exit;
    break;
    case "chggrp":
        $usrid=$_POST["user"];
        $fl=$_POST["fl"];
        $lgrp=$_POST["grp"];
        $lusr->GetItem($usrid);
        if($fl=="0")$lusr->parent="0";
        else $lusr->parent=$lgrp;
        if($lusr->SetParent())echo "OK";
        else echo "ER";
        exit;
    break;
}
?>
