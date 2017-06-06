<?php
require_once("cls/mttascks.class.php");
require_once("cls/mtwkpanel.class.php");
if(!isset($_POST["id"])){echo "ER";exit;}
$lid=$_POST["id"];
$pnl=new mtWKPanel();
$wts=$pnl->GetItem($lid);
$tsk=new mtTascks();
$tsk->GetItem($wts["tasck_id"]);
$tpar=new mtTascksParam();
$tpar->SelectTasck($tsk->id);
$tpl=new mtTascksTPL();
$tpl->GetItem($tsk->tpl);
$tpla= $tpl->GetAssoc();
$hd_txt["id"]=$tsk->id;
$hd_txt["name"]=$tsk->name;
$hd_txt["date"]=date("d-m-y H:i",strtotime($tsk->time));
$hd_txt["wpid"]=$wts["id"];
$hd_js=addslashes(json_encode($hd_txt));
?>
<script>
hdarr=JSON.parse("<?= $hd_js ?>");
</script>
<div class="row">
<div class="panel panel-default" style="margin-left: 15px;">
  <div class="panel-heading">
    <h3 class="panel-title">Параметры задания</h3>
  </div>
  <div class="panel-body" id="desk_param">
  <ul>
  <?php
  foreach($tpar->arr as $val){
      $tpar->GetItem($val);
      echo "<li><div class='par_name'>".$tpla[$tpar->param]."</div><div class='par_val'>".$tpar->val."</div></li>";
  }
  ?>
  </ul> 
  </div>
</div>
<div class="panel panel-default" style="margin-left: 15px;">
  <div class="panel-heading">
    <h3 class="panel-title">Описание задания</h3>
  </div>
  <div class="panel-body" id="desk_descr">
  <div class="d_text"><?= $tsk->descr?></div>
  <table class="table">
  <thead><tr><th class="col1">№</th><th class="col2">Дата</th><th class="col3">Юзер</th><th class="col4">Описание</th><th class="col5">Reason</th></tr></thead>
  <tbody>
  <?php
      $tms=new mtTascksMS();
      $tms->Select($tsk->id);
      $lusr=new mtUsers();
      for($i=1;$i<=count($tms->arr);$i++){
          $tms->GetRow($i);
          $lusr->GetItem($tms->user);
  ?>
  <tr><td><?= $tms->cod ?></td><td><?= $tms->time ?></td><td><?= $lusr->name ?></td><td><?= $tms->descr ?></td><td><?= $tms->GetReason()?></td></tr>
  <?php
      }
  ?>
  </tbody>
  </table>
  <div><button class="button btn" id="newmsg">Новое сообщение</button></div>
  </div>
</div>
</div>