<?php
require_once($PathLoc."/cls/mttascks.class.php");
$lid=$_POST["id"];

$tmpl=new mtTascksTPL();
$tmpl->GetItem($lid);  
?>
<div class="panel panel-default rpanel">
  <div class="panel-heading">
    <h3 class="panel-title">Шаблон №<?= $tmpl->id; ?>; <?= $tmpl->name ?> - <?= $tmpl->descr ?></h3>
  </div>
  <div class="panel-body">
    <table class="table">
    <thead>
        <tr><th>Ключ</th><th>Наименование</th></tr>
    </thead>
    <tbody>
    <?php
        $par=$tmpl->params;
        foreach($par as $key => $val){
            echo "<tr><td>".$val[0]."</td><td>".$val[1]."</td></tr>";
        }
    ?>
    </tbody>
    </table>
    <?php
        if($lid!="0"){
    ?>
    <button class="btn btn-default newp">Новый параметр</button>
    <?php
        }
    ?>
  </div>
</div>