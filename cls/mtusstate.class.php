<?php
class mtUSState{
var $id;
private $user;
var $time_s;
var $time_t;
var $status;
private $tbl;
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
}
function __destruct() {
}
function Open($lusr){
    global $mdb;
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
    if($this->time_t-time()>1800)$this->NewState();    
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
function UpdateState(){
    global $mdb;
    if(!$this->id)return;
    $lsql="UPDATE ?n SET time_t=?i WHERE id=?i";
    $mdb->query($lsql,$this->tbl,time(),$this->id);
}
function ViewState($lusr){
    global $mdb;
    if(!$this->id)return(-1);
    $lsql="SELECT id,time_s,time_t FROM ?n WHERE user=?i AND status=0";
    $rows=$mdb->getAll($lsql,$this->tbl,$lusr);
    $ret=-1;
    if(count($rows)){
        $ret=$rows[0];
        
    };
    return($ret);
}
}
?>
