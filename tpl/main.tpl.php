<?php
if($user->role==1){
    require("$PathLoc/tpl/maina.tpl.php");
       
}else{
    require_once("$PathLoc/cls/mtwkpanel.class.php");
    $mpnl=new mtWKPanel();
    $mpnl->Init();
    $arr = $mpnl->tasck;
    if(is_array($arr))$kvo=count($arr);
    else $kvo=0;
    $v=$usr_state->ViewState($user->id);
    if($v==(-1))$vsec="N/A";
    else $vsec=time() - $v["time_t"];
    
    
?>
<script>
tasck_count=<?= $kvo ?>;
hdarr=0;
</script>
<div class="page-header">
  <h1>Рабочая страница пользователя.</h1>
</div>
<div class="container-fluid">
<div class="row">
<div class="cell u_info">
  <div class="info_str"><label>Пользователь:</label><span><?= $user->name;?></span></div>
  <div class="info_str"><label>Группа:</label><span><?= $user->GetParent();?></span></div>
  <div class="info_str"><label>Время:</label><span><?= $vsec ?> секунд.</span></div>
</div>
<div class="cell t_info">
<div class="info_str"><label>Заданий в очереди:</label><span><?= count($arr)?></span></div>
<div class="info_str"><label>Выбрать задание:</label><select><?php
$issel="selected";
foreach($arr as $val){
    $lid=$val["id"];
    $obj=$val["tasck"];
    echo "<option $issel value='$lid' >№ ".$obj->id.";".$obj->name."</option>";
    $issel="";
}
?></select></div>
<div class="info_str"><button class="btn btn-default" id="bt_load">Загрузить</button></div>
</div>
</div>
<?php
    if(!$kvo){
?>
    <br><br><h3> Очередь заданий пуста!</h3>
    <div class="descr">Оставайтесь в включенными в сеть. Менеджер распределения заданий обрабатывает Ваш запрос на получение заданий.</div>
    
<?php
            
    }else{
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title" id="caps_txt">Содержание задания</h3>
  </div>
  <div class="panel-body" id="deskbord">
    
  </div>
</div>

<?php            
    }
?>
</div>

<div class="panel panel-default addform">
  <div class="panel-heading">
    <h3 class="panel-title" id="caps_txt">Новое сообщение</h3>
  </div>
  <div class="panel-body">
    <div class="frm_str"><span>Описание:</span><input class="frm_descr" type="text"></div>
    <div class="frm_str"><span>Оценка:</span><select class="frm_resume"><?php
    $tms=new mtTascksMS();
    $arr=$tms->ReasonTxt;
    $issel="selected";
    foreach($arr as $key=>$val){
        $lid=$key;
        $obj=$val;
        echo "<option $issel value='$lid'>$obj</option>";
        $issel="";
    }
?></select></div>
<div class="frm_str"><button class="button btn" id="frm_net">Отмена</button> <button class="button btn" id="frm_save">Записать</button></div>
  </div>
</div>
<?php    
}  
?>