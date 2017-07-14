<?php
class mtResult{
    var $groups;
    var $users;
    var $results;
    function __construct(){
        $this->_clean();
    }
    function __destruct() {
    }
    private function _clean(){
        $this->groups=array();
        $this->users=array();
        $this->results=array();
    }
    function Select($ldt="",$rdt=""){
        global $mdb;
        if($ldt==="")$ldt=date("Y-m-d",time());
        if($rdt==="")$rdt=date("Y-m-d",time());
        $dtn=date("Y-m-d",strtotime($ldt));
        $dtk=date("Y-m-d",strtotime($rdt)+86400);
        if(count($tmp)!=3)
        $lsql="SELECT DISTINCT pu.parent AS id, uu.name AS name FROM mtUsers pu LEFT JOIN mtUsers uu ON pu.parent=uu.id WHERE pu.parent<>0 ORDER by name";
        $this->groups=$mdb->getAll($lsql);
        for($i=0;$i<=count($this->groups);$i++){
            $grp=$this->groups[$i]["id"];
            $grpn=$this->groups[$i]["name"];
            $lsql="SELECT id,name FROM mtUsers WHERE parent=?i AND role=9 AND isgroup='N' ORDER BY name";
            $arr=$mdb->getAll($lsql,$grp);
            $this->users[$grp]=$arr;
            foreach($arr as $val){
                $lid=$val["id"];
                $lsql="SELECT tasck_id, time , descr , reason FROM mtTasckMS WHERE user=?i AND reason>10 AND time>=?s AND time<=?s";
                $this->results[$lid]=$mdb->getAll($lsql,$lid,$dtn,$dtk);
            }
        }
    }
}
?>
