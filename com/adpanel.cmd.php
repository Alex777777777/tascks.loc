<?php
require_once("$PathLoc/cls/mttascks.class.php");
require_once("$PathLoc/cls/mtwkpanel.class.php");
require_once("$PathLoc/cls/mtwkstate.class.php");
if(!isset($_POST["tpl"])){echo "ER";exit;}
$tpl=$_POST["tpl"];
$tsk=new mtTascks();
switch($tpl){
    case "userinfo":
        $lid=$_POST["id"];
        $lusr=new mtUsers();
        $lusr->GetItem($lid);
        $usst=new mtUSState();
        $actty=$usst->GetActivity($lid);
        $wkp=new mtWKPanel();
        
?>
<div class="row">
<div class="col-md-6">
<div class="col_str"><div class="str_caps">Пользователь</div><div class="str_val"><?= $lusr->name ?></div></div>
<div class="col_str"><div class="str_caps">Группа</div><div class="str_val"><?= $lusr->GetParent() ?></div></div>
<div class="col_str"><div class="str_caps">Кол-во заданий</div><div class="str_val"><?= $wkp->GetCountTascks($lid)["num"] ?></div></div>
</div>
<div class="col-md-6">
<div class="col_str"><div class="str_caps">Активность сессии</div><div class="str_val"><?= round($actty["sess"]/60) ?>мин.</div></div>
<div class="col_str"><div class="str_caps">Активность день</div><div class="str_val"><?= round($actty["daily"]/60) ?>мин.</div></div>
<div class="col_str"><div class="str_caps">Активность неделю</div><div class="str_val"><?= round($actty["weakly"]/60) ?>мин.</div></div>
<div class="col_str"><div class="str_caps">Активность месяц</div><div class="str_val"><?= round($actty["moonly"]/60) ?>мин.</div></div>
</div> 
</div>
<div class="row">
<table class="table"> 
<thead><tr><th class="col1">#</th><th class="col2">Задание</th><th class="col3">Дата с</th><th class="col4">Дата т</th><th class="col5">Кол рес</th><th class="col6">Tools</th></tr></thead>
<tbody>
<?php
$arr=$wkp->GetTascksById($lid);
if(is_array($arr)){
    $i=0;
   foreach($arr as $val){
       $tsk->GetItem($val["tasck_id"]);
       $lt=$val["time"];
       $i++;
       $lstr="<tr><td>$i</td><td>".$tsk->name."</td><td>".$tsk->time."</td><td>".date("Y-m-d h:i:s",$val["time"])."</td><td>0</td><td class='col6' data-id='$tsk->id' data-wkp='".$val["id"]."'><span title='Отдать в распределение' class='glyphicon glyphicon-random tbl_btn' data-id='cicl'></span><span title='Снять с обработки' class='glyphicon glyphicon-remove tbl_btn' data-id='rem'></span></td></tr>";
       echo $lstr;
       
   } 
}

?>
</tbody>
</table>
</div>
<?php
    break;
}
?>
