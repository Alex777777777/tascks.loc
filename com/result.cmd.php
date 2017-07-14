<?php
require_once("$PathLoc/cls/result.class.php");
require_once("$PathLoc/cls/mttascks.class.php");
$ldt=date("Y-m-d",strtotime($_POST["dts"]));
$rdt=date("Y-m-d",strtotime($_POST["dtk"]));
$_SESSION["dts"]=$ldt;
$_SESSION["dtk"]=$rdt;
$ltsk=new mtTascks();
$olduser="";
$curuser="";  
$resume_list=array(
            "0"=>"Не определено",
            "1"=>"Перенос 1ч.",
            "2"=>"Перенос 2ч.",
            "3"=>"Перенос 3ч.",
            "4"=>"Перенос 6ч.",
            "5"=>"Перенос 9ч.",
            "6"=>"Перенос 12ч.",
            "7"=>"Перенос 24ч.",
            "8"=>"Перенос 48ч.",
            "9"=>"Перенос 72ч.",
            "10"=>"Удачно",
            "11"=>"Хорошо",
            "12"=>"Нормально",
            "13"=>"Посредственно",
            "14"=>"Не Удачно",
            "15"=>"Плохо",
            "16"=>"Ужасно",
            "17"=>"Катастрофа");
$obj=new mtResult();
$obj->Select($ldt,$rdt);

$sgrp=$obj->groups;
foreach($sgrp as $cgrp){
    
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title" data-id="<?= $cgrp["id"] ?>"><?= $cgrp["name"] ?></h3>
  </div>
  <div class="panel-body">
    <table class="table">
    <thead>
    <tr><th class="col1">Юзер</th><th class="col2">Задание</th><th class="col3">Дата</th><th class="col4">Описание</th><th class="col5">Выводы</th></tr>
    </thead>
    <tbody>
<?php
$uarr=$obj->users[$cgrp["id"]];
foreach($uarr as $cuser){
    $tarr=$obj->results[$cuser["id"]];
    $kvo_t=0;
    foreach($tarr as $ctasck){
        $ltsk->GetItem($ctasck["tasck_id"]);
        if($cuser["name"]==$olduser)$curuser="";
        else{
            if($kvo_t){
?>
<tr class="tb_it"><td class="col1"></td><td>Количество заданий</td><td></td><td></td><td><?= $kvo_t?></td></tr>
<?php
            }
            $kvo_t=0;
            $curuser=$cuser["name"];
            $olduser=$cuser["name"];}
        $kvo_t++;
?>
    <tr><td class="col1"><?= $curuser ?></td><td class="col2" data-id="<?= $ltsk->id ?>">Задание # <?= $ltsk->id ?>; <?= $ltsk->name ?> </td><td class="col3"><?= $ctasck["time"] ?></td><td class="col4"><?= $ctasck["descr"] ?></td><td class="col5"><?= $resume_list[$ctasck["reason"]] ?></td></tr>
<?php
}
if($kvo_t){
?>
<tr class="tb_it"><td class="col1"></td><td>Количество заданий</td><td></td><td></td><td><?= $kvo_t?></td></tr>
<?php        
}
}
?>
    </tbody>
    </table>
  </div>
</div>
<?php
}
?>