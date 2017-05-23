<?php
class mtTascks{
var $id;
var $name;
var $descr;
var $time;
var $tpl;
var $parent;
var $isgroup;
var $user;
var $state;

var $arr;
var $tbl;
function __construct(){
    $this->_clean();
    $this->arr="";
    $this->tbl="mtTascks";
}
function __destruct() {
}
private function _clean(){
    $this->id=0;
    $this->name="";
    $this->descr="";
    $this->time="";
    $this->tpl="";
    $this->parent=0;
    $this->isgroup="N";
    $this->user=0;
    $this->state=0;
}

function SelectFrom($lpar=0){
    global $mdb;
    $this->arr=$mdb->getAll("SELECT id,isgroup FROM ?n WHERE parent=?i ORDER BY isgroup DESC, name ASC",$this->tbl,$lpar);
    return(count($this->arr));
}
function GetItem($lid){
    global $mdb;
    $ret=$mdb->getRow("SELECT * FROM ?n WHERE id=?i",$this->tbl,$lid);
    $this->_clean();
    if($ret){
        $this->id=$ret["id"];
        $this->name=$ret["name"];
        $this->descr=$ret["descr"];
        $this->time=$ret["time"];
        $this->tpl=$ret["tpl"];
        $this->parent=$ret["parent"];
        $this->isgroup=$ret["isgroup"];
        $this->user=$ret["user"];
        $this->state=$ret["state"];
    }
}
function GetPath(){
    $ret="";
    if($this->parent=="0")$ret="<span data-id='0' class='it_path'>tascks:/</span><span data-id='".$this->id."' class='it_path'>".$this->name."/</span>";
    else{
        $lts=new mtTascks();
        $lts->GetItem($this->parent);
        $ret=$lts->GetPath()."<span data-id='".$this->id."' class='it_path'>".$this->name."/</span>";
    };
    return($ret);
}
}
class mtTascksTPL{
    var $id;
    var $name;
    var $descr;
    var $params;
    
    var $arr;
    function __construct(){
        $this->_clean();
        $this->arr="";
        $this->tbl="mtTasckTPL";
        $this->tblp="mtTasckTPLMS";
    }
    function __destruct() {
    }
    private function _clean(){
        $this->id=0;
        $this->name="";
        $this->descr="";
        $this->params=array();
        $this->params[0]=["tel","Телефон"];
        $this->params[1]=["addr","Адрес"];
        $this->params[2]=["ext","Доп. Инфо."];
    }
    function Select(){
        global $mdb;
        $arr=$mdb->getAll("SELECT id,name FROM ?n",$this->tbl);
        $this->arr=array();
        $this->arr[0]="Default";
        foreach($arr as $val){
            $this->arr[$val["id"]]=$val["name"];
        };
        return($this->arr);    
    }
    function GetItem($lid){
        global $mdb;
        $this->_clean();
        if($lid==0){
            $this->id=0;
            $this->name="Default";
            $this->descr="Стандартный шаблон";
            return;
        };
        $arr=$mdb->getRow("SELECT * FROM ?n WHERE id=?i",$this->tbl,$lid);
        if($arr){
            $this->id=$arr["id"];
            $this->name=$arr["name"];
            $this->descr=$arr["descr"];
        }
        $arr=$mdb->getAll("SELECT * FROM ?n WHERE idTPL=?i",$this->tblp,$lid);
        if(count($arr)){
            foreach($arr as $val){
                $this->params[]=[$val["param"],$val["name"]];
            }
        }
    }
}
?>
