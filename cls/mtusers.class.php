<?php
class mtUsers{
var $id;
var $name;
var $pass;
var $role;
var $valid;
var $jabber;
var $isgroup;

var $LastErr;
var $Err_Descr;
var $arr;
var $authing;
var $tbl;
function __construct(){
    $this->id=0;
    $this->pass="";
    $this->name="";
    $this->role=0;
    $this->valid=0;
    $this->jabber="";
    $this->isgroup="N";
    $this->LastErr=0;
    $this->authing=0;
    $this->tbl="mtUsers";
}
function __destruct() {
}
function GetRole(){
    $ret="";
    switch($this->role){
        case 0:
            $ret="Заморожен";
        break;
        case 1:
            $ret="Администратор";
        break;
        case 9:
            $ret="Пользователь";
        break;
        default:
            $ret="Не активен";
    }
    return($ret);
}
function Select(){
    global $mdb;
    $this->arr=$mdb->getAll("SELECT id FROM ?n WHERE isgroup='N'",$this->tbl);
    return(count($this->arr));
}
function Open($luser, $lpass){
    global $mdb;
    $this->id=0;
    $this->pass="";
    $this->name="";
    $this->role=0;
    $this->valid=0;
    $this->jabber="";
    $this->isgroup="N";
    $this->LastErr=0;
    $luser=strtolower($luser);
    $lsql="SELECT * FROM ?n WHERE name=?s AND isgroup='N'";
    $ret=$mdb->getAll($lsql,$this->tbl,$luser);
    if(count($ret)){
        $this->id=$ret[0]["id"];
        $this->pass=$ret[0]["pass"];
        $this->name=$ret[0]["name"];
        $this->role=$ret[0]["role"];
        $this->jabber=$ret[0]["jabber"];
        $this->isgroup=$ret[0]["isgroup"];
        $ppass=$lpass;
        if($this->authing)$ppass=md5($lpass);
        if($this->isgroup="N"){
            if($this->pass==$ppass){
                $this->valid=1;
            }
        }
        $this->pass="".crc32($this->pass);
        if(!$this->valid){$this->LastErr=100;return;}
        $this->LastErr=0;
    }else $this->LastErr=2;    
}
function Frozen($lid){
    global $mdb;
    $this->LastErr=1;
    $lsql="UPDATE ".$this->tbl." SET role=0 WHERE id=".$lid;
    $mdb->do_query($lsql);
    if($mdb->LastErr)$this->LastErr=0;
}
function Delete($lid){
    global $mdb;
    $this->LastErr=1;
    $lsql="DELETE FROM ".$this->tbl." WHERE id=".$lid;
    $mdb->do_query($lsql);
    if($mdb->LastErr)$this->LastErr=0;
}
function GetItem($lid){
    global $mdb;
    $this->id=0;
    $this->pass="";
    $this->name="";
    $this->role=0;
    $this->valid=0;
    $this->jabber="";
    $this->LastErr=0;
    $lsql="SELECT * FROM ?n WHERE id=?i";
    $ret=$mdb->getRow($lsql,$this->tbl,$lid);
    if($ret){
        $this->id=$ret["id"];
        $this->name=$ret["name"];
        $this->role=$ret["role"];
        $this->jabber=$ret["jabber"];
        $this->isgroup=$ret["isgroup"];
        $this->LastErr=0;
    }else $this->LastErr=2;    
    
}
function Save(){
    global $mdb;
    $this->LastErr=1;
    $this->login=strtolower($this->login);
    if(!$this->id){$this->Insert();return;}
    $lstr= "name='".$this->name."'";
    $lstr.= ",jabber='".$this->jabber."'";
    if($this->pass)$lstr.= ",pass='".md5($this->pass)."'";
    $lstr.= ",role=".$this->role;
    $lsql="UPDATE ".$this->tbl." SET ".$lstr." WHERE id=".$this->id;
    $mdb->do_query($lsql);
    if($mdb->LastErr)$this->LastErr=0;
}
function Insert(){
    global $mdb;
    $this->LastErr=1;
    $lcol="name,jabber,pass,role";
    $lstr= "'".$this->name."'";
    $lstr.= ",'".$this->jabber."'";
    if($this->pass)$lstr.= ",'".md5($this->pass)."'";
    $lstr.= ",".$this->role;
    $lsql="INSERT INTO ".$this->tbl."(".$lcol.") VALUES(".$lstr.")";
    $mdb->do_query($lsql);
    if($mdb->LastErr){
        $this->LastErr=0;
        $this->id=mysqli_insert_id($mdb->lCon);
    }
}
function Validate($par){
    if(!$par)return;
    if($this->pass==$par){
        $this->valid=1;
        $this->LastErr=0;
    }else {
        $this->valid=0;
        $this->LastErr=100;
    }
}
}
?>
