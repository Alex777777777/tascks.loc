<?php
require_once($PathLoc."/cls/mttascks.class.php");
if($user->role!="1"){
    echo "<div class='page-header'><h1>Недостаточно прав</h1></div>";
    exit;
}
/*$tabs=array(
    "ВСЕ"=>"*",
    "Стандарт"=>"0",
    "В процессе"=>"1",
    "Закрытые"=>"11"    
);*/
$cur_gp=@$_GET["grp"];
if(!$cur_gp)$cur_gp=0;
$mts=new mtTascks();
$mts->SelectGrp();
$arr=$mts->arr;
$ret=json_encode($arr);
?>
<script>
cur_grp=<?= $cur_gp ?>;
grp_arr=<?= $ret ?>;
tsk_arr=[];
tit_arr=[];
</script>
<div class="page-header">
  <h1>Таск - задания</h1>
</div>
<div class="container-fluid">
<div class="row">
<div class="col sidebar projbar">
<div class="pb_header" data-id="0"  data-name="grp"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;<span class="pbh_text">Стандарт</span>&nbsp;&nbsp;<span>&#9660;</span>
<ul class="dropdown-menu view_menu">
<li data-id="*">Показывать Все</li>
<li data-id="0">Стандарт</li>
<li data-id="20">Скрытые</li>
</ul></div>
<div class="pb_caps"><span class="glyphicon glyphicon-list icon"></span><span class="glyphicon glyphicon-plus extdo">
<ul class="dropdown-menu proj_menu">
<li data-id="gnew">Новый проэкт</li>
<li data-id="gview">Показать</li>
<li data-id="ghide">Скрыть</li>
<li data-id="gdel">Удалить</li>
<li class="divider"></li>
<li data-id="gdowrk">В разработку</li>
</ul>
</span>
<span>Проэкты</span></div>
<div class="dobar dosel"><a href="#" data-id="1">Выбрать все</a><a href="#" data-id="0">Снять выделение</a></div>
<ul class="nav nav-sidebar">

</ul>
</div>
<div class="col tasckbar">
<div class="pb_header" data-id="*" data-name="tsk"><span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;<span class="pbh_text">Показывать Все</span>&nbsp;&nbsp;<span>&#9660;</span>
<ul class="dropdown-menu view_menu">
<li data-id="*">Показывать Все</li>
<li data-id="0">Новые задачи</li>
<li data-id="10">В раздаче</li>
<li data-id="11">Закрытые задачи</li>
<li data-id="20">Скрытые задачи</li>
</ul></div>
<div class="pb_caps"><span class="glyphicon glyphicon-indent-left icon"></span><span class="glyphicon glyphicon-plus extdo">
<ul class="dropdown-menu proj_menu">
<li data-id="tnew">Новое задание</li>
<li data-id="tedit">Правка</li>
<li data-id="tdel">Удалить</li>
<li class="divider"></li>
<li data-id="tdowrk">В разработку</li>
</ul>
</span><span>Задания</span></div>
<div class="dobar dosel"><a href="#" data-id="1">Выбрать все</a><a href="#" data-id="0">Снять выделение</a></div>
<ul class="nav nav-sidebar">

</ul>
</div>
<div class="col itembar">
<div class="pb_header" data-id="*" data-name="tsk"><span class="glyphicon glyphicon-ok"></span><span></span></div>
<div class="pb_caps"><span class="glyphicon glyphicon-briefcase icon"></span><span>Задание не выбрано</span></div>
<div class="dobar dosel"><span></span></div>
<div class="nav nav-sidebar">

</div>
</div>
</div>
</div>

<div class="panel panel-default ngfrm">
  <div class="panel-heading"><h3 class="panel-title">Создание группы</h3></div>
  <div class="panel-body">
  
    <div class="frm_str">
    <span>Наименование:</span>
    <input id="frm_name" placeholder="Наименование">
    </div> 
    <div class="frm_str">
    <span>Описание:</span>
    <textarea id="frm_descr" placeholder="Описание"></textarea>
    </div>
    <div class="frm_str" style="display:flex;">
    <button class='btn-mini frm' title='Закрыть'  data-id="fcl">Закрыть</button>
    <button class='btn-mini frm' title='Записать'  data-id="fwr">Записать</button>
    </div>
  </div>
</div>
<div class="panel panel-default dofrm">
  <div class="panel-heading"><h3 class="panel-title">Запуск задания</h3></div>
  <div class="panel-body">
  
    <div class="frm_str">
    <span>Задача:</span>
    <div></div>
    </div> 
    <div class="frm_str">
    <span>Привязка к группе:</span>
    <select id="togrp">
        <option selected value="0">Нет привязки</option>
    <?php
        $lusr=new mtUsers();
        $arr=$lusr->SelectGp();
        foreach($arr as $key=>$val){
            echo "<option value=\"$key\">$val</option>";
        }
    ?>
    </select>
    </div>
    <div class="frm_str">
    <span>Привязка к пользователю:</span>
    <select id="tousr">
        <option selected value="0">Нет привязки</option>
    <?php
        $lusr=new mtUsers();
        $lusr->Select();
        $arr=$lusr->arr;
        foreach($arr as $valr){
            $key=$valr["id"];
            $name=$valr["name"];
            $lusr->GetItem($key);
            if($lusr->role!=9)continue;
            echo "<option value=\"$key\">$name</option>";
        }
    ?>
    </select>
    </div>
    <div class="frm_str" style="display:flex;">
    <button class='btn-mini frm' title='Закрыть'  data-id="docl">Закрыть</button>
    <button class='btn-mini frm' title='Записать'  data-id="dowr">Запустить</button>
    </div>
</div>
</div>

<?php    
  
?>
