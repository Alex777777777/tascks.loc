<?php

class mtWKState{
var $id;
var $tasck_id;
var $date;
var $ugrp;
var $user;
var $sdate;
var $status;

private $tbl;
private function _clean(){
    $this->id=0;
    $this->tasck_id=0;
    $this->date=0;
    $this->ugrp=0;
    $this->user=0;
    $this->sdate=0;
    $this->status=0;
}
function __construct(){
    $this->_clean();
    $this->tbl="mtWKState";
}
function __destruct() {
}
function CreateJob(){
    global $mdb;
    $lsql="INSERT INTO ?n(tasck_id,date,ugrp,user,sdate) VALUES(?i,?i,?i,?i,?i)";
    $lt=time();
    $ret=$mdb->query($lsql,$this->tbl,$this->tasck_id,$lt,$this->ugrp,$this->user,$lt);
    if($ret){
        $this->id=$mdb->insertId();
    }
}
function DoUsersTasck(){
    global $mdb;
    $dt=time();
    $lsql="SELECT id,tasck_id,user FROM ?n WHERE (sdate<=?i)AND(user!=0)AND(status<10)";
    $arr=$mdb->getAll($lsql,$this->tbl,$dt);
    $ret=array();
    if(is_array($arr)){
        $wkp=new mtWKPanel();
        foreach($arr as $val){
            $lusr=$val["user"];
            $ltasck=$val["tasck_id"];
            $lid=$val["id"];
            $wkp->AddTask($ltasck,$lusr);
            $ret[]=[$ltasck,$lusr];
            $this->UpdateStatus($lid,10);
        }
    }
    return($ret);
}
function DoGroupsTasck(){
    global $mdb;
    $dt=time();
    $lsql="SELECT DISTINCT ugrp FROM ?n WHERE (sdate<=?i)AND(status<10)AND(ugrp!=0)AND(user=0)";
    $arr=$mdb->getAll($lsql,$this->tbl,$dt);
    $ret=array();
    if(is_array($arr)){
        $usst=new mtUSState();
        foreach($arr as $val){
            $grp=$val["ugrp"];
            $ltascks=$this->GetTascksByGroup($grp);
            $kvo_t=count($ltascks);
            $lusers=$usst->GetUserByGroup($grp);
            $kvo_u=0;
            for($i=count($lusers);$i>0;$i--){
                $lusers[$i-1]["count"]=3-$lusers[$i-1]["count"];
                if($lusers[$i-1]["count"]<=0)unset($lusers[$i-1]);
                else $kvo_u+=$lusers[$i-1]["count"];
            };
            $wkp=new mtWKPanel();
            while(($kvo_t>0)&($kvo_u>0)){
                $kvo_t--;
                $ltsk=each($ltascks);
                usort($lusers,"cmp");
                $lusr=$lusers[0];
                $lusers[0]["count"]--;
                $kvo_u--;
                $wkp->AddTask($ltsk["1"]["tasck_id"],$lusr["user"]);
                $ret[]=[$ltsk["1"]["tasck_id"],$lusr["user"]];
                $this->UpdateStatus($ltsk["1"]["id"],10);
            };
        };   
    }
    return($ret);
}
private function GetTascksByGroup($grp=0){
    global $mdb;
    $dt=time();
    $lsql="SELECT id,tasck_id FROM ?n  WHERE (sdate<=?i)AND(status<10)AND(ugrp=$grp)";
    $arr=$mdb->getAll($lsql,$this->tbl,$dt);
    return($arr);
}
function DoOtherTasck(){
        global $mdb;    
    $usst=new mtUSState();
    $ltascks=$this->GetTascksByGroup();
    $kvo_t=count($ltascks);
    $lusers=$usst->GetUserByGroup();
    $kvo_u=0;
    $wkp=new mtWKPanel();
    $ret=array();
    for($i=count($lusers);$i>0;$i--){
        $tmp=$wkp->GetCountTascks($lusers[$i-1]["user"]);
        $lusers[$i-1]["count"]=3-$tmp["num"];
        if($lusers[$i-1]["count"]<=0)unset($lusers[$i-1]);
        else $kvo_u+=$lusers[$i-1]["count"];
    };
    while(($kvo_t>0)&($kvo_u>0)){
        $kvo_t--;
        $ltsk=each($ltascks);
        usort($lusers,"cmp");
        $lusr=$lusers[0];
        $lusers[0]["count"]--;
        $kvo_u--;
        $wkp->AddTask($ltsk["1"]["tasck_id"],$lusr["user"]);
        $ret[]=[$ltsk["1"]["tasck_id"],$lusr["user"]];
        $this->UpdateStatus($ltsk["1"]["id"],10);
    };
    return($ret);
}
function Delete($lid){
    global $mdb;
    $lsql="DELETE FROM ?n WHERE tasck_id=?i";
    $mdb->query($lsql,$this->tbl,$lid);
}
function UpdateStatus($lid,$lst){
    global $mdb;
    $dt=time();
    $lsql="UPDATE ?n SET status=?i ,sdate=$dt WHERE id=?i";
    $mdb->query($lsql,$this->tbl,$lst,$lid);
}
function Update3($lid){
    global $mdb;
    $lsql="UPDATE ?n SET user=0, status=0 WHERE tasck_id=?i";
    $mdb->query($lsql,$this->tbl,$lid);
}
function UpdateStatus2($ltsk,$lst){
    global $mdb;
    global $user;
    $dt=time();
    switch($lst){
        case 0:
            $dt+=60*15;
        break;
        case 1:
            $dt+=60*60;
        break;
        case 2:
            $dt+=60*60*2;
        break;
        case 3:
            $dt+=60*60*3;
        break;
        case 4:
            $dt+=60*60*6;
        break;
        case 5:
            $dt+=60*60*9;
        break;
        case 6:
            $dt+=60*60*12;
        break;
        case 7:
            $dt+=60*60*24;
        break;
        case 8:
            $dt+=60*60*24*2;
        break;
        case 9:
            $dt+=60*60*24*3;
        break;
        default:
            $lst=20;
        break;
    }
    if($lst==20){
        $lsql="DELETE FROM ?n WHERE tasck_id=?i";
        $mdb->query($lsql,$this->tbl,$ltsk);
    }else{
        $lsql="UPDATE ?n SET status=?i,sdate=$dt,user=?i WHERE tasck_id=?i";
        $mdb->query($lsql,$this->tbl,$lst,$user->id,$ltsk);
    };
}
}
function cmp($a, $b) { 
        if ($a["count"] == $b["count"]) {return 0;}
        return ($a["count"] < $b["count"]) ? 1 : -1;
}
?>
