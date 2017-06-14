<?php
  if(isset($_GET["file"])){
      $fl=$_GET["file"];
      require($PathLoc."/com/downloadfile.cmd.php");
      exit;
  }
  if(isset($_GET["cmd"])){
      $cmd=$_GET["cmd"];
      require($PathLoc."/com/$cmd.cmd.php");
      exit;
  }
  $do="main";
  if(isset($_GET["do"]))if($_GET["do"])$_SESSION['do']=$_GET["do"];
  if(isset($_SESSION['do']))if($_SESSION['do']) $do=$_SESSION['do'];
  if(($do=="main") &&($user->role==1))$do="maina";
  
  require("header.tpl.php");
  require($do.".tpl.php");
  require("footer.tpl.php");
?>
