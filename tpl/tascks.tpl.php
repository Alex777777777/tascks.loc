<?php
require_once($PathLoc."/cls/mttascks.class.php");
if($user->role!="1"){
    echo "<div class='page-header'><h1>Недостаточно прав</h1></div>";
    exit;
}
if(isset($_GET["lp"]))$_SESSION["lp"]=$_GET["lp"];
if(isset($_GET["rp"]))$_SESSION["rp"]=$_GET["rp"];
$slp=0;$srp=0;
if(isset($_SESSION["lp"]))$slp=$_SESSION["lp"];
if(isset($_SESSION["rp"]))$srp=$_SESSION["rp"];
$viewes="*";
if(isset($_SESSION["viewes"]))$viewes=$_SESSION["viewes"];
$tabs=array(
    "ВСЕ"=>"*",
    "Стандарт"=>"0",
    "В процессе"=>"1",
    "Закрытые"=>"11",
    "Архивные"=>"12",
    "Удаленные"=>"100"    
);
?>
<script>
var viewes="<?= $viewes ?>";
var lpanel="";
var rpanel="";
var lp_curdir=<?= $slp ?>;
var rp_curdir=<?= $srp ?>;
</script>
<div class="page-header">
  <h1>Таск - задания</h1>
</div>
<div class="container-fluid">
<div class="panel panel-default cl1">
  <div class="panel-heading tabs">
    <?php
        foreach($tabs as $lname=>$lkey) {
    ?>
            <div class="tab" data-id="<?= $lkey ?>"><?= $lname ?></div>
    <?php
        }
    ?>
  </div>
  <div class="row tables">
  <div class="panel lpanel">
  <div class="path" data-id="lpanel">tascks:/</div>
      <table class="table" id="lpanel">
      <thead><tr><th class="col1">#</th><th class="col2">Наименование</th><th class="col3">Дата</th><th class="col4">Шаблон</th></tr></thead>
      <tbody>
      <?php
          for($i=0;$i<15;$i++){
      ?>
      <tr data-id=''><td class="col1"></td><td class="col2"></td><td class="col3"></td><td class="col4"></td></tr>
      <?php
              }
      ?>
      </tbody>
      </table>
  </div>
  <div class="panel rpanel">
      <div class="path" data-id="rpanel">tascks:/</div>
      <table class="table" id="rpanel">
      <thead><tr><th class="col1">#</th><th class="col2">Наименование</th><th class="col3">Дата</th><th class="col4">Шаблон</th></tr></thead>
      <tbody>
      <?php
          for($i=0;$i<15;$i++){
      ?>
      <tr data-id=''><td class="col1"></td><td class="col2"></td><td class="col3"></td><td class="col4"></td></tr>
      <?php
              }
      ?>
      </tbody>
      </table>
  </div>
  </div>
</div>

</div>
<input id='edcell'>
<div id="submenu">
<div class="sm_l"><button class='btn-mini pnl' title='Запустить на исполнение' data-id="do">Запуск</button></div>
<div class="sm_r">
<button class='btn-mini pnl' title='Новое Задание' data-id="nz">НЗ</button>
<button class='btn-mini pnl' title='Новое Группа' data-id="ng">НГ</button>
<button class='btn-mini pnl' title='Перенести' data-id="mv">Перенос</button>
<button class='btn-mini pnl' title='Копировать' data-id="cp">Копир</button>
<button class='btn-mini pnl' title='Удалить'  data-id="dl">Удалить</button>
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
    <button class='btn-mini frm' title='Закрыть'  data-id="fcl">Закрыть</button>
    <button class='btn-mini frm' title='Записать'  data-id="dwr">Запустить</button>
    </div>
</div>
</div>
<?php    
  
?>
