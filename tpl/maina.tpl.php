<?php

if($user->role!=1){
    echo '<div class="page-header">
            <h1>Недостаточно прав пользователя.</h1>
            </div>';
            exit;
}else{
     require_once("$PathLoc/cls/mtusstate.class.php"); 
       
?>

<div class="page-header">
    <h1>Рабочая страница администратора.</h1>
</div>
<div class="container-fluid">
<div class="row">
<div class="sidebar">
<?php
    $usst=new mtUSState();
    $lusr=new mtUsers();
    $arr=$lusr->SelectGp();
    foreach($arr as $key=>$val){
?>
<div class="panel panel-default gpb">
  <div class="panel-heading">
    <h3 class="panel-title gpb_caps">Группа "<?= $val?>"</h3>
  </div>
  <div class="panel-body gpb_body" data-id="<?= $key?>">
    <ul>
    <?php
        $lusr->SelectUserGp2($key);
        foreach($lusr->arr as $val){
            $lusr->GetItem($val["id"]);
            $lname=$lusr->name;
            $state=$usst->ViewState2($lusr->id);
            if($state==-1)$st="100";
            else $st=$state["status"];
            echo "<li><span class='stat$st'></span>$lname</li>";
        }
                
    ?>
    
    
    </ul>
  </div>
</div>
<?php
            
    }
?>
</div>
</div>
</div>
<?php
}
?>