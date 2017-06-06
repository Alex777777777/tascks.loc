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
    $this->arr=$mdb->getAll("SELECT id,isgroup FROM ?n WHERE parent=?i AND state <> 100 ORDER BY isgroup DESC, name ASC",$this->tbl,$lpar);
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
function NewTasck(){
    global $mdb;
    $lsql="INSERT INTO ?n(name,descr,tpl,parent,user) VALUES(?s,?s,?i,?i,?i)";
    if($mdb->query($lsql,$this->tbl,$this->name,$this->descr,$this->tpl,$this->parent,$this->user)){
        $this->id=$mdb->insertId();
        $ltpl=new mtTascksTPL();
        $ltpl->GetItem($this->id);
        $pars=$ltpl->params;
        $params=new mtTascksParam();
        $params->tasck_id=$this->id;
        foreach($pars as $lval){
            $lpar=$lval[0];
            $lname=$lval[1];
            $params->SetParam($lpar,"");
        }
    }
}
function NewGroup(){
    global $mdb;
    $lsql="INSERT INTO ?n(name,descr,parent,user,isgroup) VALUES(?s,?s,?i,?i,?s)";
    if($mdb->query($lsql,$this->tbl,$this->name,$this->descr,$this->parent,$this->user,$this->isgroup)){
        $this->id=$mdb->insertId();
    }
    return($this->id);
}
function cpParam($lid){
    global $mdb;
    $lsql="INSERT INTO mtTasckParam (tasck_id,param,val) SELECT ?i,param,val FROM mtTasckParam WHERE tasck_id=?i";
    $mdb->query($lsql,$this->id,$lid);
}
function Update(){
    global $mdb;
    $lsql="UPDATE ?n SET name=?s,descr=?s WHERE id=?i";
    $mdb->query($lsql,$this->tbl,$this->name,$this->descr,$this->id);
}
function moveto($val){
    global $mdb;
    $lsql="UPDATE ?n SET parent=?i WHERE id=?i";
    $mdb->query($lsql,$this->tbl,$val,$this->id);
}
function Delete(){
    global $mdb;
    $this->GetItem($this->id);
    if($this->isgroup="Y"){
        $lsql="SELECT id FROM ?n WHERE parent=?i";
        $arr=$mdb->getAll($lsql,$this->tbl,$this->id);
        if(count($arr))return(1);
    }
    $lsql="UPDATE ?n SET state=100 WHERE id=?i";
    $mdb->query($lsql,$this->tbl,$this->id);
    return(0);
}
}
class mtTascksParam{
    var $id;
    var $tasck_id;
    var $param;
    var $val;

    var $arr;
    var $tbl;
    function __construct(){
        $this->_clean();
        $this->arr="";
        $this->tbl="mtTasckParam";
    }
    function __destruct() {
    }
    function _clean(){
        $this->id=0;
        $this->tasck_id=0;
        $this->param=0;
        $this->val="";
    }
    function SelectTasck($lid){
        global $mdb;
        $lsql="SELECT id FROM ?n WHERE tasck_id=?i";
        $ret=$mdb->getAll($lsql,$this->tbl,$lid);
        $this->arr=array();
        foreach($ret as $val){
            $this->arr[]=$val["id"];
        }
    }
    function GetItem($lid){
        global $mdb;
        $lsql="SELECT * FROM ?n WHERE id=?i";
        $row=$mdb->getRow($lsql,$this->tbl,$lid);
        if($row){
            $this->id=$row["id"];
            $this->tasck_id=$row["task_id"];
            $this->param=$row["param"];
            $this->val=$row["val"];
        }
    }
    function GetParam($par){
        global $mdb;
        $lsql="SELECT val FROM ?n WHERE tasck_id=?i AND param=?s";
        $row=$mdb->getRow($lsql,$this->tbl,$this->tasck_id,$par);
        if($row){
            return($row["val"]);
        }else return("");
    }
    function SetParam($par,$val){
        global $mdb;
        $lsql="SELECT id FROM ?n WHERE tasck_id=?i AND param=?s";
        $row=$mdb->getRow($lsql,$this->tbl,$this->tasck_id,$par);
        if(!$row)$this->InsertParam($par,$val);
        else $this->UpdateParam($par,$val);
    }
    private function InsertParam($par,$val){
        global $mdb;
        $lsql="INSERT INTO ?n(tasck_id,param,val) VALUES(?i,?s,?s)";
        if($mdb->query($lsql,$this->tbl,$this->tasck_id,$par,$val)){
            $this->id=$mdb->insertId();
        }
    }
    private function UpdateParam($par,$val){
        global $mdb;
        $lsql="UPDATE ?n SET val=?s WHERE tasck_id = ?i AND param = ?s ";
        $mdb->query($lsql,$this->tbl,$val,$this->tasck_id,$par);
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
    function GetAssoc(){
        $ret=array();
        foreach($this->params as $val){
            $ret[$val[0]]=$val[1];
        }
        return($ret);
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
class mtTascksMS{
    var $id;
    private $tasck_id;
    var $time;
    var $cod;
    var $user;
    var $descr;
    var $reason;
    
    var $arr;
    private function _clean(){
        $this->id=0;
        $this->time="";
        $this->cod=0;
        $this->user=0;
        $this->descr="";
        $this->reason=0;
    }
    function __construct(){
        $this->_clean();
        $this->tasck_id=0;
        $this->arr="";
        $this->tbl="mtTasckMS";
        $this->ReasonTxt=json_decode("{
            \"0\":\"Не определено\",
            \"1\":\"Перенос 1ч.\",
            \"2\":\"Перенос 2ч.\",
            \"3\":\"Перенос 3ч.\",
            \"4\":\"Перенос 6ч.\",
            \"5\":\"Перенос 9ч.\",
            \"6\":\"Перенос 12ч.\",
            \"7\":\"Перенос 24ч.\",
            \"8\":\"Перенос 48ч.\",
            \"9\":\"Перенос 72ч.\",
            \"10\":\"Удачно\",
            \"11\":\"Хорошо\",
            \"12\":\"Нормально\",
            \"13\":\"Посредственно\",
            \"14\":\"Не Удачно\",
            \"15\":\"Плохо\",
            \"16\":\"Ужасно\",
            \"17\":\"Катастрофа\"
        }");
    }
    function __destruct() {
    }
    function Select($task_id){
        global $mdb;
        $this->tasck_id=$task_id;
        $rows=$mdb->getAll("SELECT id,cod FROM ?n WHERE tasck_id=$task_id ORDER BY cod",$this->tbl);
        $this->arr=array();
        if($rows){
            if(count($rows)){
                for($i=0;$i<count($rows);$i++){
                    $row=$rows[$i];
                    $this->arr[$row["cod"]]=$row["id"];
                }
            }
        }
    }
    function GetRow($nrow){
        if(!isset($this->arr[$nrow]))return(0);
        $lid=$this->arr[$nrow];
        return($this->GetItem($lid));
        
    }
    function GetItem($lid){
        global $mdb;
        $this->_clean();
        $row=$mdb->getRow("SELECT * FROM ?n WHERE id=?i",$this->tbl,$lid);
        if($row){
            $this->id=$row["id"];
            $this->time=$row["time"];
            $this->cod=$row["cod"];
            $this->user=$row["user"];
            $this->descr=$row["descr"];
            $this->reason=$row["reason"];
            return(1);
        }else return(0);
    }
    function AddNew(){
        global $user;
        $this->_clean();
        $this->cod=$this->NetCod();
        $this->user=$user->id;
    }
    function Save(){
        global $mdb;
        $lsql="INSERT INTO ?n (tasck_id,cod,user,descr,reason) VALUES(?i,?i,?i,?s,?i)";
        $ret=$mdb->query($lsql,$this->tbl,$this->tasck_id,$this->cod,$this->user,$this->descr,$this->reason);
        if($ret){
            $this->id=$mdb->insertId();
        }
        return($this->id);
    }
    function NetCod(){
        if(!is_array($this->arr)) return(1);
        $kvo = count($this->arr);
        return($kvo+1);
    }
    function GetReason(){
        $ret="";
        $arr=$this->ReasonTxt;
        if(isset($arr->{$this->reason})){
            $ret=$arr->{$this->reason};
        }
        return($ret);
    }
}
?>
