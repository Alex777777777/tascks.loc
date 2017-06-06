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
/*$ttpl= new mtTascksTPL();
$tsk=new mtTascks();
$tsk->SelectFrom($slp);
$arr=$tsk->arr;
$ttpl->Select();
$tpla=$ttpl->arr;
$lpanel=array();
$lpanel[0]="tascks:/";
if($slp!="0")$lpanel[0]=$tsk->GetPath();
for($i=0;$i<count($arr);$i++){
    $tsk->GetItem($arr[$i]["id"]);
    $ltpl=$tpla[$tsk->tpl];
    $par=array();
    $par["id"]=$tsk->id;
    $par["name"]=$tsk->name;
    $par["descr"]=$tsk->descr;
    $par["time"]=$tsk->time;
    $par["tpl"]=$tsk->tpl;
    if($tsk->tpl!="0") $par["tpln"]=$tpla[$tsk->tpl];
    else $par["tpln"]="";
    $par["isgroup"]=$tsk->isgroup;
    $par["parent"]=$tsk->parent;
    $par["user"]=$tsk->user;
    $par["state"]=$tsk->state;
    $lpanel[]=json_encode($par);
}
$rpanel=array();

if($slp!=$srp){
    $tsk->SelectFrom($srp);
    $rpanel[0]="tascks:/";
    if($srp!="0")$rpanel[0]=$tsk->GetPath();
    $arr=$tsk->arr;
    for($i=0;$i<count($arr);$i++){
        $tsk->GetItem($arr[$i]["id"]);
        $ltpl=$tpla[$tsk->tpl];
        $par=array();
        $par["id"]=$tsk->id;
        $par["name"]=$tsk->name;
        $par["descr"]=$tsk->descr;
        $par["time"]=$tsk->time;
        $par["tpl"]=$tsk->tpl;
        if($tsk->tpl!="0") $par["tpln"]=$tpla[$tsk->tpl];
        else $par["tpln"]="";
        $par["isgroup"]=$tsk->isgroup;
        $par["parent"]=$tsk->parent;
        $par["user"]=$tsk->user;
        $par["state"]=$tsk->state;
        $rpanel[]=json_encode($par);
    }
}else $rpanel=$lpanel;  */

$tabs=array(
    "Стандарт"=>0,
    "В процессе"=>1,
    "Скрытые"=>2,
    "Удаленные"=>8,
    "ВСЕ"=>9,
);


?>
<script>
/*var lpanel=<= json_encode($lpanel) ?>;
var rpanel=<= json_encode($rpanel) ?>;*/
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
        $lclass="active";
        foreach($tabs as $lname=>$lkey) {
            
    ?>
            
            <div class="tab <?= $lclass ?>" data-id="<?= $lkey ?>"><?= $lname ?></div>
    <?php
            $lclass="";
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
<div>
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
<?php    
  
?>
