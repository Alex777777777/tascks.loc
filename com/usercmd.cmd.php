<?php
if($user->role!=1){
    echo "0";
    exit;
}
if(!isset($_POST["tpl"])){echo "err";exit;}
$tpl=$_POST["tpl"];
$obj=new mtUsers();
switch($tpl){
    case "adduser":
        $obj->id=$_POST["id"];
        $obj->name=$_POST["name"];
        $obj->jabber=$_POST["jabb"];
        $obj->role=$_POST["role"];
        $obj->lang=$_POST["lang"];
        if($_POST["ispass"])$obj->pass=$_POST["pass"];
        $obj->Save();
    break;  
    case "deluser":
        $obj->Delete($_POST["id"]);
        echo 0;
    break;
    case "frozuser":
        $obj->Frozen($_POST["id"]);
        echo 0;
    break;
}

?>
