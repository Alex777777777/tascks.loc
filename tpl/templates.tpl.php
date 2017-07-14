<?php
if($user->role!=1){
    echo '<div class="page-header">
            <h1>Недостаточно прав пользователя.</h1>
            </div>';
            exit;
}
require_once("$PathLoc/cls/mttascks.class.php"); 
$tpl=new mtTascksTPL();
$tpl->Select();
?>

<div class="page-header">
  <h1>Управление шаблонами</h1>
</div>

 <div class="container-fluid"> 
 <div class="row">
 <div class="col-md-2 ">
 <div class="panel panel-default lpanel">
  <div class="panel-heading">
    <h3 class="panel-title">Шаблоны</h3>
  </div>
  <div class="panel-body sidebar">
 <ul class="nav nav-sidebar">
 <?php
 $ext="class='active2'";
 $arr=$tpl->arr;
 foreach($arr as $id=>$name){
     echo "<li data-id='".$id."' $ext>".$name."</li>";
     $ext="";
 }    
 ?>
 </ul>
 <button class="btn btn-default new">Новый Шаблон</button>
 </div>
 </div>
 </div>
 <div class="col-md-8 templ">
 
 </div>
 </div>
 </div>
<div class="panel panel-default ngfrm">
  <div class="panel-heading"><h3 class="panel-title">Создание шаблона</h3></div>
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
<div class="panel panel-default npfrm">
  <div class="panel-heading"><h3 class="panel-title">Создание параметра</h3></div>
  <div class="panel-body">
    <div class="frm_str">
    <span>Ключ:</span>
    <input id="frm_key" placeholder="Ключ">
    </div> 
    <div class="frm_str">
    <span>Наименование:</span>
    <input id="frm_name" placeholder="Наименование">
    </div> 
    
    <div class="frm_str" style="display:flex;">
    <button class='btn-mini frm' title='Закрыть'  data-id="fcl">Закрыть</button>
    <button class='btn-mini frm' title='Записать'  data-id="pwr">Записать</button>
    </div>
  </div>
</div>