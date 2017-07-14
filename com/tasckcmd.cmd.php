<?php
require_once("cls/mttascks.class.php");
if(!isset($_POST["tpl"])){echo "err";exit;}
$tpl=$_POST["tpl"];
$tsk=new mtTascks();
switch($tpl){
    case "itget":
        $lid=@$_POST["id"];
        if($lid){
            $tsk->GetItem($lid);
            $larr= array();
            $larr["id"]=$tsk->id;
            $larr["name"]=$tsk->name;
            $larr["descr"]=$tsk->descr;
            $larr["time"]=$tsk->time;
            $tpl=new mtTascksTPL();
            $tpl->GetItem($tsk->tpl);
            $larr["tpl"]=$tpl->name;
            $lusr=new mtUsers();
            $lusr->GetItem($tsk->user);
            $larr["user"]=$lusr->name;
            $lstr="";
            switch($tsk->state){
                case 0:
                    $lstr="Новое задание";
                break;
                case 10:
                    $lstr="В разработке";
                break;
                case 11:
                    $lstr="Закрыто";
                break;
                case 20:
                    $lstr="Скрыто";
                break;
            }
            $larr["state"]=$lstr;
            $ret=array();
            $ret["gen"]=$larr;
            $tpar=new mtTascksParam();
            $tpar->SelectTasck($tsk->id);
            $larr=array();
            $assoc=$tpl->GetAssoc();
            for($i=0;$i<count($tpar->arr);$i++){
                $lid=$tpar->arr[$i];
                $tpar->GetItem($lid);
                $parid= $tpar->param;
                $val=$tpar->val;
                $name=@$assoc[$parid];
                if(!$name)$name=$parid;
                $larr[]=[$parid,$val,$name];
            }
            $ret["par"]=$larr;
            $larr=array();
            $tms=new mtTascksMS();
            $tms->Select($tsk->id);
            for($i=1;$i<=count($tms->arr);$i++){
                $tms->GetRow($i);
                $lrow=array();
                $lrow["id"]=$tms->id;
                $lrow["date"]=$tms->time;
                $lrow["reason"]=$tms->GetReason($tms->reason);
                $lusr->GetItem($tms->user);
                $lrow["user"]=$lusr->name;
                $lrow["descr"]=$tms->descr;
                $lrow["cod"]=$tms->cod;
                $larr[$tms->cod]=$lrow;
            }
            $ret["ms"]=$larr;
            $ret1=json_encode($ret);
            echo $ret1;
        }else echo "ER";
    break;
    case "tget":
        $grp=@$_POST["grp"];
        if($grp){
            $tsk->SelectTskByGrp($grp);
            $arr=$tsk->arr;
            $ret=json_encode($arr);
            echo $ret;
        }else echo "ER";
    break;
    case "ng":
        $lname=@$_POST["name"];
        $ldescr=@$_POST["descr"];
        if(!$ldescr)$ldescr=$lname;
        $tsk->name=$lname;
        $tsk->descr=$ldescr;
        $tsk->user=0;
        $tsk->isgroup="Y";
        $tsk->parent=0;
        $tsk->NewGroup();
        if($tsk->id){
            $tsk->SelectGrp();
            $arr=$tsk->arr;
            $ret=json_encode($arr);
            echo $ret;
            
        }else echo "ER";
        exit;
    break;
    case "tdel":
        $grp=@$_POST["grp"];
        if(!$grp)$grp=0;
        $lid=0;
        $lid=preg_split("[,]", @$_POST["ids"], -1,PREG_SPLIT_NO_EMPTY);
        if(count($lid)){
            foreach($lid as $val){
                $tsk->id=$val;
                $ret=$tsk->Delete();
            }
            $tsk->SelectTskByGrp($grp);
            $arr=$tsk->arr;
            $ret=json_encode($arr);
            echo $ret;
        }else echo "ER";
        exit;
    break;
    case "gdel":
        $lid=0;
        $lid=preg_split("[,]", @$_POST["ids"], -1,PREG_SPLIT_NO_EMPTY);
        if(count($lid)){
            foreach($lid as $val){
                $tsk->id=$val;
                $ret=$tsk->Delete();
            }
            $tsk->SelectGrp();
            $arr=$tsk->arr;
            $ret=json_encode($arr);
            echo $ret;
        }else echo "ER";
        exit;
    break;
    case "ghide":
        $lid=preg_split("[,]", @$_POST["ids"], -1,PREG_SPLIT_NO_EMPTY);
        if(count($lid)){
            foreach($lid as $val){
                $tsk->GetItem($val);
                if($tsk->isgroup="Y")$tsk->SetState(20);
            }
            $tsk->SelectGrp();
            $arr=$tsk->arr;
            $ret=json_encode($arr);
            echo $ret;
        }else echo "ER";
        exit;
    break;
    /*
    case "tview":
        $grp=@$_POST["grp"];
        if(!$grp)$grp=0;
        $lid=preg_split("[,]", @$_POST["ids"], -1,PREG_SPLIT_NO_EMPTY);
        if(count($lid)){
            foreach($lid as $val){
                $tsk->GetItem($val);
                if($tsk->state!=10)$tsk->SetState(0);
                }
            $tsk->SelectTskByGrp($grp);
            $arr=$tsk->arr;
            $ret=json_encode($arr);
            echo $ret;
        }else echo "ER";
    break; */
    case "gview":
        $lid=preg_split("[,]", @$_POST["ids"], -1,PREG_SPLIT_NO_EMPTY);
        if(count($lid)){
            foreach($lid as $val){
                $tsk->GetItem($val);
                if($tsk->isgroup="Y")$tsk->SetState(0);
                }
            $tsk->SelectGrp();
            $arr=$tsk->arr;
            $ret=json_encode($arr);
            echo $ret;
        }else echo "ER";
    break;
    case "viewes":
        $_SESSION["viewes"]=@$_POST["viewes"];
        exit;
    break;
    case "do":
        require_once("cls/mtwkstate.class.php");
        $sps= preg_split("[,]", @$_POST["sps"], -1,PREG_SPLIT_NO_EMPTY);
        $togrp=$_POST["togrp"];
        $tousr=$_POST["tousr"];
        
        $tsk=new mtTascks();
        $wst=new mtWKState();
        foreach($sps as $val){
            $tsk->GetItem($val);
            if($tsk->isgroup=="N"){
                if($tsk->state==0){
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
        }
        echo "OK";
        exit;
    break;
}
?>
