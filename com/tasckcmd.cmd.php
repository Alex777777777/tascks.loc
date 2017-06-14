<?php
require_once("cls/mttascks.class.php");
if(!isset($_POST["tpl"])){echo "err";exit;}
$tpl=$_POST["tpl"];
$tsk=new mtTascks();
switch($tpl){
    case "ng":
        $lpath=0+@$_POST["from"];
        $lname=@$_POST["name"];
        $ldescr=@$_POST["descr"];
        if(!$ldescr)$ldescr=$lname;
        $tsk->name=$lname;
        $tsk->descr=$ldescr;
        $tsk->user=$user->id;
        $tsk->isgroup="Y";
        $tsk->parent=$lpath;
        $tsk->NewGroup();
        if($tsk->id)echo "OK";
        else echo "ER";
        exit;
    break;
    case "dl":
        $lid=0;
        $lid=0+@$_POST["id"];
        if($lid){
            $tsk->id=$lid;
            $ret=$tsk->Delete();
            if(!$ret) echo "OK";
            else echo "Группа не пуста. Удаление невозможно.";
        } else echo "ER";
        exit;
    break;
    case "mv":
        $lid=0;
        $lid=0+@$_POST["id"];
        $lp=$_GET["lp"];
        $rp=$_GET["rp"];
        if($lp==$rp){
            echo "Папки одинаковы!";
            exit;
        }
        if($lid){
            $tsk->GetItem($lid);
            if($tsk->parent!=$lp)$tsk->moveto($lp);
            else $tsk->moveto($rp);
            echo "OK";
        } else echo "ER";
        exit;
    break;
    case "cp":
        $lid=0;
        $lid=0+@$_POST["id"];
        $lp=$_GET["lp"];
        $rp=$_GET["rp"];
        if($lp==$rp){
            echo "Папки одинаковы!";
            exit;
        }
        if($lid){
            $tsk->GetItem($lid);
            $tsk->id=0;
            if($tsk->parent!=$lp)$tsk->parent=$lp;
            else $tsk->parent=$rp;
            $tsk->NewGroup();
            $tsk->cpParam($lid);
            echo "OK";
        } else echo "ER";
        exit;
    break;
    case "viewes":
        $_SESSION["viewes"]=@$_POST["viewes"];
        exit;
    break;
    case "do":
        require_once("cls/mtwkstate.class.php");
        $param= json_decode($_POST["param"],true);
        /*"id":lid,
        "state":st,
        "grp":grp*/
        $togrp=$_POST["togrp"];
        $tousr=$_POST["tousr"];
        $tsk=new mtTascks();
        $tsk->GetItem($param["id"]);
        if($tsk->id==0){echo "ER";exit;}
        $wst=new mtWKState();
        if($tsk->isgroup=="N"){
            if($tsk->state!=0){echo "ER";exit;}
            else{
                $tsk->StartDo();
                $wst->tasck_id=$tsk->id;
                $wst->ugrp=$togrp;
                $wst->user=$tousr;
                $wst->CreateJob();
            }
        }else{
            $tsk->SelectFrom($tsk->id,0);
            $arr=$tsk->arr;
            foreach($arr as $val){
                $wst->id=0;
                if($val["isgroup"]=="N"){
                $tsk->GetItem($val["id"]);
                if($tsk->state==0){
                    $tsk->StartDo();
                    $wst->tasck_id=$tsk->id;
                    $wst->ugrp=$togrp;
                    $wst->user=$tousr;
                    $wst->CreateJob();
                }}
            }
        }
        echo "OK";
        exit;
    break;
}
?>
