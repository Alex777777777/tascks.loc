<?php
class mtUsers{
var $id;
var $name;
var $pass;
var $role;
var $valid;
var $jabber;
var $isgroup;
var $parent;

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
    $this->parent=0;
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
    $this->arr=$mdb->getAll("SELECT id,name FROM ?n WHERE isgroup='N'",$this->tbl);
    return(count($this->arr));
}
function SelectUserGp($lid){
    global $mdb;
    $lstr="SELECT id,name,parent FROM ?n WHERE isgroup='N' AND (parent=$lid OR parent=0) AND role=9";
    $this->arr=$mdb->getAll($lstr,$this->tbl);
    return(count($this->arr));
}
function SelectUserGp2($lid){
    global $mdb;
    $lstr="SELECT id,name,parent FROM ?n WHERE isgroup='N' AND parent=$lid AND role=9";
    $this->arr=$mdb->getAll($lstr,$this->tbl);
    return(count($this->arr));
}
function SelectGp(){
    global $mdb;
    $lsql="SELECT id,name FROM ?n WHERE isgroup='Y' ORDER BY name ASC";
    $arr=$mdb->getAll($lsql,$this->tbl);
    $ret=array();
    for($i=0;$i<count($arr);$i++){
        $ret[$arr[$i]["id"]]=$arr[$i]["name"];
    }
    return($ret);
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
    $mdb->query($lsql);
}
function Delete($lid){
    global $mdb;
    $this->LastErr=1;
    $lsql="DELETE FROM ".$this->tbl." WHERE id=".$lid;
    $mdb->query($lsql);
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
        $this->parent=$ret["parent"];
        $this->LastErr=0;
    }else $this->LastErr=2;    
    
}
function SetParent(){
    global $mdb;
    $lsql="UPDATE ?n SET parent=?i WHERE id=?i";
    return($mdb->query($lsql,$this->tbl,$this->parent,$this->id));
}
function GetParent(){
    global $mdb;
    $lsql="SELECT name FROM ?n WHERE id=?i";
    $row=$mdb->getRow($lsql,$this->tbl,$this->parent);
    $ret="Без Группы";
    if(isset($row["name"])) $ret=$row["name"];
    return($ret);
}
function NewGroup($lname){
    global $mdb;
    $this->LastErr=1;
    $lsql="INSERT INTO ?n(name,isgroup) VALUES(?s,'Y')";
    if($mdb->query($lsql,$this->tbl,$lname))return "OK";
    else return "ER";
}
function Save(){
    global $mdb;
    $this->LastErr=1;
    $this->name=strtolower($this->name);
    if(!$this->id){$this->Insert();return;}
    $lsql="UPDATE ?n SET name=?s,pass=?s,role=?i,jabber=?s WHERE id=?i";
    $ret=$mdb->query($lsql,$this->tbl,$this->name,md5($this->pass),$this->role,$this->jabber,$this->id);
}
function Insert(){
    global $mdb;
    $this->LastErr=1;
    $lsql="INSERT INTO ?n(name,pass,role,jabber) VALUES(?s,?s,?i,?s)";
    $ret=$mdb->query($lsql,$this->tbl,$this->name,md5($this->pass),$this->role,$this->jabber);
    if(!$ret){
        $this->LastErr=0;
        $this->id=$mdb->insertId();
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
