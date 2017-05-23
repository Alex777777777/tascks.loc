<?php
require_once("cls/mttascks.class.php");
    $mts=new mtTascks();
    $gp=new mtTascks();
    $mtpl= new mtTascksTPL();
    $mtpl->Select();
    $caps="Добавление нового задания";
    if(($user->role!=1)){
        $caps="Недостаточно прав";
        echo '<div class="sd_hd"><?php echo $caps;?></div>';
        exit;
    }
    if(isset($_GET['item'])){
        $mts->GetItem($_GET['item']);
        if($mts->isgroup=="Y"){
            $caps="Внутренняя Ошибка.";
            echo '<div class="sd_hd"><?php echo $caps;?></div>';
            exit;
        }
        if($mts->tpl)$mtpl->GetItem($mts->tpl);
        $caps="Просмотр задания ".$mts->name;
    }
    $grp_txt="Без группы";
    if(isset($_GET['gp'])){
        $gp->GetItem($_GET['gp']);
        $grp_txt=$gp->name;
    }
    
?>
<div class="sd_hd"><?= $caps;?></div>
<div class="panel panel-default" id='mpanel'>
<form autocomplete="off" data-id="<?= $mts->id;?>">
<div class="frm_str"><label for="tsk_tpl">Шаблон задания</label>
<?php 
if($mts->id==0){
?>
<select id="tsk_tpl" size=1>
<?php
$ext="selected"; 
  for($i=0;$i<count($mtpl->arr);$i++){
      
      $val=$mtpl->arr[$i];
      echo "<option $ext value='$i'>$val</option>";
      $ext="";
  }
?>
</select>
<?php
    }else{
        $val=$mtpl->arr[$mts->tpl]; 
        echo "<input class='static_tpl' value='$val' readonly>" ;
    };
?>
</div>
<div class="frm_str"><label for="tsk_name">Группа:</label><input id="tsk_grp" type="text" readonly value="<?= $grp_txt ?>" data-id="<?php $gp->id ?>"></div>
<div class="frm_str"><label for="tsk_name">Наименование:</label><input id="tsk_name" autocomplete="off" type="text" placeholder="Название" required value="<?php echo $mts->name;?>" <?php  if($mts->id) echo " readonly";?>></div>
<div class="frm_str"><label for="tsk_descr">Описание:</label><textarea id="tsk_descr" placeholder="Описание"><?php echo $mts->descr;?></textarea></div>
<?php
    if($mts->id!=0){
        echo '<div class="panel panel-default" id="epanel">';
        echo '<div>Дополнительные параметры</div>';
        foreach($mtpl->params as $val){
            $lname=$val[0];
            $ldescr=$val[1];
            if($ldescr=="")$ldescr=$lname;
            echo '<div class="frm_str"><label for="tsk_'.$lname.'">'.$ldescr.':</label><input id="tsk_'.$lname.'" autocomplete="off" type="text"></div>';
        }
        echo '</div>';

    }
?>
<div class="frm_str"><div class='button' data-id='close'>Закрыть</div><div class='button'  data-id='save'>Сохранить</div></div>
</form>
</div>
<div class="sd_ft"></div>