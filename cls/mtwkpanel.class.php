<?php
require_once("$PathLoc/cls/mttascks.class.php");
class mtWKPanel{
var $tasck;
private $tbl;

function __construct(){
    $this->tasck=0;
    $this->tbl="mtWKPanel";
}
function __destruct() {
}
function Init(){
    global $mdb;
    global $user;
    $lsql="SELECT * FROM ?n WHERE user=".$user->id;
    $rows=$mdb->getAll($lsql,$this->tbl);
    $this->tasck=array();
    if(count($rows)){
        for($i=0;$i<count($rows);$i++){
            $row=$rows[$i];
            $this->tasck[$i]["id"]=$row["id"];
            $this->tasck[$i]["tasck"]=new mtTascks();
            $obj=$this->tasck[$i]["tasck"];
            $obj->GetItem($row["tasck_id"]);
            $this->tasck[$i]["time"]=$row["time"];
        }
    }
}
function GetItem($lid){
    global $mdb;
    global $user;
    $lsql="SELECT * FROM ?n WHERE id=$lid";
    return($mdb->getRow($lsql,$this->tbl));    
}
function Delete($lid){
    global $mdb;
    $lsql="DELETE FROM ?n WHERE id=$lid";
    return($mdb->query($lsql,$this->tbl));    
}
}
?>
