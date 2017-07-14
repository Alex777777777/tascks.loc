<?php
class mtUSState{
var $id;
private $user;
var $time_s;
var $time_t;
var $status;

private $tbl;
var $param_timeout;
private function _clean(){
    $this->id=0;
    $this->user=0;
    $this->time_s=0;
    $this->time_t=0;
    $this->status=0;
}
function __construct(){
    $this->_clean();
    $this->tbl="mtUSState";
    $this->param_timeout=30*60;
}
function __destruct() {
}
function Open($lusr){
    global $mdb;
    if($lusr->role!=9)return;
    $lsql="SELECT * FROM ?n WHERE user=?i AND status=0";
    $rows=$mdb->getAll($lsql,$this->tbl,$lusr->id);
    if(!count($rows))$this->NewState();
    else{
        $row=$rows[0];
        $this->id=$row["id"];
        $this->user=$row["user"];
        $this->time_s=$row["time_s"];
        $this->time_t=$row["time_t"];
        $this->status=$row["status"];
    }
    if(time()-$this->time_t>1800)$this->NewState();    
}
private function NewState(){
    global $user;
    global $mdb;
    $lsql="UPDATE ?n SET status=100 WHERE user=?i AND status=0";
    $mdb->query($lsql,$this->tbl,$user->id);
    $this->_clean();
    $this->user=$user->id;
    $this->time_s=time();
    $this->time_t=$this->time_s;
    $this->status=0;
    $lsql="INSERT INTO ?n (user,time_s,time_t) VALUES(?i,?i,?i)";
    $ldt=time();
    if($mdb->query($lsql,$this->tbl,$user->id,$ldt,$ldt))$this->id=$mdb->insertId();
}
private function CloseState(){
    global $mdb;
    if(!$this->id)return;
    $lsql="UPDATE ?n SET status=10 WHERE id=?i";
    if($mdb->query($lsql,$this->tbl,$this->id))$this->_clean;
}
function GetActivity($lusr){
    global $mdb;
    if(!$lusr)return;
    $ret=["sess"=>0,"daily"=>0,"weakly"=>0,"moonly"=>0];
    $dt=time();
    $dtarr=getdate(date($dt));
    $lmoon=$dtarr["mon"];
    $nmoon=mktime(0,0,0,$lmoon,1);
    $nweak=mktime(0,0,0,$lmoon,$dtarr["mday"]-$dtarr["wday"]);
    $nday=mktime(0,0,0,$lmoon,$dtarr["mday"]);
    
    $lsql="SELECT * FROM ?n WHERE user=$lusr AND time_t>=$nmoon ORDER BY time_s";
    $arr = $mdb->getAll($lsql,$this->tbl);
    foreach($arr as $key){        
        if($key["status"]==0){
            $ltm=$dt-$key["time_s"];
            $ret["sess"]=$ltm;
            $ret["moonly"]+=$ltm;
            $ret["weakly"]+=$ltm;
            $ret["daily"]+=$ltm;
        }else{
            if($key["time_t"]>$nmoon){
                $tmp=$key["time_s"];
                if($tmp<$nmoon)$tmp=$nmoon;
                $ret["moonly"]+=$key["time_t"]-$tmp;
            }
            if($key["time_t"]>$nweak){
                $tmp=$key["time_s"];
                if($tmp<$nweak)$tmp=$nweak;
                $ret["weakly"]+=$key["time_t"]-$tmp;
            }
            if($key["time_t"]>$nday){
                $tmp=$key["time_s"];
                if($tmp<$nday)$tmp=$nday;
                $ret["daily"]+=$key["time_t"]-$tmp;
            }
        }
        
    }
    
    return($ret);
}
function UpdateState(){
    global $mdb;
    if(!$this->id)return;
    $lsql="UPDATE ?n SET time_t=?i WHERE id=?i";
    $mdb->query($lsql,$this->tbl,time(),$this->id);
}
function ViewState($lusr){
    global $mdb;
    $lsql="SELECT id,time_s,time_t FROM ?n WHERE user=?i AND status=0";
    $rows=$mdb->getAll($lsql,$this->tbl,$lusr);
    $ret=-1;
    if(count($rows)){
        $ret=$rows[0];
        
    };
    return($ret);
}
function ViewState2($lusr){
    global $mdb;
    $lsql="SELECT id,time_s,time_t,status FROM ?n WHERE user=?i ORDER BY time_t DESC";
    $rows=$mdb->getAll($lsql,$this->tbl,$lusr);
    $ret=-1;
    if(count($rows)){
        $ret=$rows[0];
        
    };
    return($ret);
}
function CloseUserByTimeOut(){
    global $mdb;
    $dt=time();
    $lsql="SELECT id FROM  ?n WHERE (status=0)AND($dt-time_t>?i)";
    $ret=$mdb->getAll($lsql,$this->tbl,$this->param_timeout);
    $lsql="UPDATE ?n SET status=100 WHERE (status=0)AND($dt-time_t>?i)";
    $mdb->query($lsql,$this->tbl,$this->param_timeout);
    return($ret);
}

function GetUserByGroup($lgrp=0){
    global $mdb;
    $lsql="SELECT mtUSState.id, mtUSState.user "; 
    $lsql.="FROM mtUSState LEFT OUTER JOIN  mtUsers ON mtUsers.id=mtUSState.user ";
    $lsql.="WHERE mtUSState.status=0 AND mtUsers.parent=$lgrp";
    if($lgrp==0)$lsql="SELECT mtUSState.id, mtUSState.user FROM mtUSState WHERE mtUSState.status=0";
    $arr=$mdb->getAll($lsql);
    $wkp=new mtWKPanel();
    for($i=0;$i<count($arr);$i++){
        $lid=$arr[$i]["user"];
        $ret=$wkp->GetCountTascks($lid);
        $arr[$i]["count"]=$ret["num"];
    }
    return($arr);
}
}
?>
