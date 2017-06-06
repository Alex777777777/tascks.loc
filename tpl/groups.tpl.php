<?php
    $cur_gp=0;
    if(isset($_SESSION["cur_ugroups"]))$cur_gp=$_SESSION["cur_ugroups"];
?>
<script>
var cur_gp = <?= $cur_gp ?>;
</script>
<div class="page-header">
  <h1>Группы системы</h1>
</div>

 <div class="container-fluid"> 
 <div class="row">
 <div class="col-md-4 sidebar">
 <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Группы пользоваиелей</h3>
  </div>
  <div class="panel-body">
  <ul class='gnav nav-sidebar'>
  <?php
      $lusr=new mtUsers();
      $arr=$lusr->SelectGp();
      foreach($arr as $key => $val){
          $lchk="";
          if($key==$cur_gp) $lchk="class='active2'";
          echo "<li $lchk data-id='$key'> $val </li>";
      }
  ?>
  </ul>
  <button class="btn-mini add_grp">Добавить группу</button>
  </div>
</div>
 </div>
 <div class="col-md-8 main">
  <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Участники группы</h3>
      </div>
      <div class="panel-body gpusers">
      </div>
  </div>
 </div>
 </div>
 </div>
<div class="sd_ft"></div>
<div class="panel panel-default frm_add">
<div class="panel-heading">
        <h3 class="panel-title">Новая группа</h3>
      </div>
      <div class="panel-body">
        <div class="frm_str"><span>Наименование</span><input id="frm_name" placeholder="Наименование группы"></div>
        <div class="frm_str"><button class="button btn-mini frm_cls">Закрыть</button><button class="button btn-mini frm_save">Сохранить</button></div>
      </div>
</div>