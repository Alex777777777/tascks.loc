<?php
require_once("cls/mttascks.class.php");
if(isset($_GET["lp"]))$_SESSION["lp"] = $_GET["lp"];
if(isset($_GET["rp"]))$_SESSION["rp"] = $_GET["rp"];
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
        if($_GET['gp']!="0"){
        $gp->GetItem($_GET['gp']);
        $grp_txt=$gp->name;
        };
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
        echo "<input class='static_tpl' value='$val' data-id='$mts->tpl' readonly>" ;
    };
?>
</div>
<div class="frm_str"><label for="tsk_name">Группа:</label><input id="tsk_grp" type="text" readonly value="<?= $grp_txt ?>" data-id="<?= $gp->id ?>"></div>
<div class="frm_str"><label for="tsk_name">Наименование:</label><input id="tsk_name" autocomplete="off" type="text" placeholder="Название" required value="<?= $mts->name;?>"></div>
<div class="frm_str"><label for="tsk_descr">Описание:</label><textarea id="tsk_descr" placeholder="Описание"><?php echo $mts->descr;?></textarea></div>
<?php
    if($mts->id!=0){
        echo '<div class="panel panel-default" id="epanel">';
        echo '<div>Дополнительные параметры</div>';
        $pobj=new mtTascksParam();
        $pobj->tasck_id=$mts->id;
        foreach($mtpl->params as $val){
            $lname=$val[0];
            $ldescr=$val[1];
            if($ldescr=="")$ldescr=$lname;
            echo '<div class="frm_str"><label>'.$ldescr.':</label><input autocomplete="off" type="text" data-id="param" data-param="'.$lname.'" value="'.$pobj->GetParam($lname).'"></div>';
        }
        echo '</div>';

    }
?>
<div class="frm_str"><div class='button' data-id='close'>Закрыть</div><div class='button'  data-id='save'>Сохранить</div></div>
</form>
</div>
<div class="sd_ft"></div>