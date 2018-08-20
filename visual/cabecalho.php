<?php
ini_set('session.gc_maxlifetime', 60*60); // 60 minutos
session_start();

if(!isset ($_SESSION['login']) == true) //verifica se há uma sessão, se não, volta para área de login
{
	unset($_SESSION['login']);
	header('location:../index.php');
}
else
{
	$logado = $_SESSION['login'];
}
?>

<html>
	<head>
		<title>SISCONTRAT- <?= date("Y") ?> - v1.0 - Secretaria Municipal de Cultural - São Paulo</title>
		<meta charset="utf-8" />
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- css -->
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/style.css" rel="stylesheet" media="screen">
		<link href="color/default.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
		<?php include "../include/script.php"; ?>
    </head>
	<body>
        <div id="bar">
                <div class="col-xs-2" style="padding: 10px">
                    <img src="images/logo_cultura_h.png">
                </div>
                <div class="col-md-2" style="padding: 13px">
                    <span style="color: #fff">SISCONTRAT</span>
                </div>
                <div class="col-md-offset-4 col-md-4" style="padding: 5px">
                    <span style="color: #fff">Suporte para o sistema:<br>sistema.igsis@gmail.com</span>
                </div>
        </div>
<?php
	# Menu progresso
	include_once '../visual/smart_wizard.php';
?>