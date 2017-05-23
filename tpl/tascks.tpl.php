<?php
require_once($PathLoc."/cls/mttascks.class.php");
if($user->role!="1"){
    echo "<div class='page-header'><h1>Недостаточно прав</h1></div>";
    exit;
}
$ttpl= new mtTascksTPL();
$tsk=new mtTascks();
$tsk->SelectFrom();
$arr=$tsk->arr;
$ttpl->Select();
$tpla=$ttpl->arr;
$lpanel=array();
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
$tabs=array(
    "Стандарт"=>0,
    "В процессе"=>1,
    "Скрытые"=>2,
    "Удаленные"=>8,
    "ВСЕ"=>9,
);

?>
<script>
var tsys;
tsys=<?= json_encode($lpanel) ?>;
var lpanel;
var ppanel;
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
<div id="submenu"></div>
<?php    
  
?>
