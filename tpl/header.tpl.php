<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Lang" content="ru">
<title>Tasck Explorer</title>
<link rel="shortcut icon" href="img/favicon32.png" type="image/png">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
<?php
    echo "<link rel='stylesheet' type='text/css' href='css/".$do.".css'>\n";
    echo "<script src='js/".$do.".js' type='text/javascript'></script>\n";
    switch ($do){
        case "result":
            echo '<script type="text/javascript" src="js/moment-with-locales.min.js"></script>\n';
            echo '<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>\n';
            echo '<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />\n';
        break;
        //default:
    }
?>
</head>
<body>
<div id="ext-wrp">

</div>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Tasck Explorer</a>
        </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="?do=main">Главная</a></li>
        <li><a href="?do=result">Результаты</a></li>
        <li><div class="dropdown" >            
                Админка<span class="caret"></span>
            <ul class="dropdown-menu">
                <li><a tabindex="-1" href="?do=tascks2">Таски</a></li>
                <li><a tabindex="-1" href="?do=templates">Шаблоны</a></li>
                
            </ul>
            </div>
        </li>
        <li><div class="dropdown" >            
                Безопассность<span class="caret"></span>
            <ul class="dropdown-menu">
                <li><a tabindex="-1" href="?do=users">Пользователи</a></li>
                <li><a tabindex="-1" href="?do=groups">Группы</a></li>
            </ul>
            </div>
        </li>
        <li><a href="?logout=true">Выход</a></li>
      </ul>
    </div>
    </div>
</div>