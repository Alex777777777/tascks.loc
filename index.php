<?php
session_start();
$PathLoc=__DIR__;
require("cls/safemysql.class.php");
$mdb = new SafeMySQL(require("cls/db_param.php"));
require("cls/mtusers.class.php");
if(isset($_GET["logout"])){setcookie('v1');setcookie('p1');unset($_SESSION['user']);unset($_SESSION['pass']);header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);exit(1);}
if(isset($_GET["do"]))if($_GET["do"]=="auth"){require("tpl/auth.php");exit(1);}
$tuser="";$tpass="";
if(isset($_SESSION['user']))$tuser=$_SESSION['user'];
if(isset($_SESSION['pass']))$tpass=$_SESSION['pass'];
if(!$tuser){
    if(isset($_COOKIE['v1']))$tuser=@$_COOKIE['v1'];
    if(isset($_COOKIE['p1']))$tpass=@$_COOKIE['p1'];
    $_SESSION['user']=$tuser;
    $_SESSION['pass']=$tpass;
}
$user=new mtUsers();
$user->Open($tuser,$tpass);
$user->Validate($tpass);
if(!$user->valid){require("tpl/auth.php");}
else {
    require("tpl/loader.php");   
}
?>
