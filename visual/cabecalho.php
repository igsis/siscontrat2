<?php
ini_set('session.gc_maxlifetime', 60*60); // 60 minutos
session_start();
date_default_timezone_set('GMT');
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
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SisContrat</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- DataTables -->
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
      <!-- fullCalendar -->
      <link rel="stylesheet" href="bower_components/fullcalendar/packages/core/main.css">
      <link rel="stylesheet" href="bower_components/fullcalendar/packages/list/main.css">
      <link rel="stylesheet" href="bower_components/fullcalendar/packages/daygrid/main.css">
      <link rel="stylesheet" href="bower_components/fullcalendar/packages/timegrid/main.css">
      <!-- Toastr -->
      <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- JQUEY Mask -->
    <script src="dist/js/jquery-1.12.4.min.js"></script>
    <script src="dist/js/jquery.mask.js"></script>
    <script src="dist/js/scripts.js"></script>

    <!-- jQuery 3 -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
      <!-- Bootstrap 3.3.7 -->
      <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
      <script src="dist/js/handlebars-v4.1.0.js"></script>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <!-- fullCalendar -->
      <script src="bower_components/fullcalendar/packages/core/main.js"></script>
      <script src="bower_components/fullcalendar/packages/interaction/main.js"></script>
      <script src="bower_components/fullcalendar/packages/daygrid/main.js"></script>
      <script src="bower_components/fullcalendar/packages/timegrid/main.js"></script>
      <script src="bower_components/fullcalendar/packages/list/main.js"></script>
      <script src="bower_components/fullcalendar/packages/core/locales/pt-br.js"></script>
      <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <!-- Toastr -->
      <script src="plugins/toastr/toastr.min.js"></script>
      <style>
          .ui-menu-item{
              text-align: center !important;
          }
          .stepper .nav-tabs {
              position: relative;
          }
          .stepper .nav-tabs > li {
              position: relative;
          }
          .stepper .nav-tabs > li:after {
              content: '';
              position: absolute;
              background: #f1f1f1;
              display: block;
              width: 100%;
              height: 5px;
              top: 30px;
              left: 50%;
              z-index: 1;
          }
          .stepper .nav-tabs > li.completed::after {
              background: #34bc9b;
          }
          .stepper .nav-tabs > li:last-child::after {
              background: transparent;
          }
          .stepper .nav-tabs > li.active:last-child .round-tab {
              background: #34bc9b;
          }
          .stepper .nav-tabs > li.active:last-child .round-tab::after {
              content: '✔';
              color: #fff;
              position: absolute;
              left: 0;
              right: 0;
              margin: 0 auto;
              top: 0;
              display: block;
          }
          .stepper .nav-tabs [data-toggle='tab'] {
              width: 25px;
              height: 25px;
              margin: 20px auto;
              border-radius: 100%;
              border: none;
              padding: 0;
              color: #f1f1f1;
          }
          .stepper .nav-tabs [data-toggle='tab']:hover {
              background: transparent;
              border: none;
          }
          .stepper .nav-tabs > .active > [data-toggle='tab'], .stepper .nav-tabs > .active > [data-toggle='tab']:hover, .stepper .nav-tabs > .active > [data-toggle='tab']:focus {
              color: #34bc9b;
              cursor: default;
              border: none;
          }
          .stepper .tab-pane {
              position: relative;
              padding-top: 50px;
          }
          .stepper .round-tab {
              width: 25px;
              height: 25px;
              line-height: 22px;
              display: inline-block;
              border-radius: 25px;
              background: #fff;
              border: 2px solid #34bc9b;
              color: #34bc9b;
              z-index: 2;
              position: absolute;
              left: 0;
              text-align: center;
              font-size: 14px;
          }
          .stepper .completed .round-tab {
              background: #34bc9b;
          }
          .stepper .completed .round-tab::after {
              content: '✔';
              color: #fff;
              position: absolute;
              left: 0;
              right: 0;
              margin: 0 auto;
              top: 0;
              display: block;
          }
          .stepper .active .round-tab {
              background: #fff;
              border: 2px solid #34bc9b;
          }
          .stepper .active .round-tab:hover {
              background: #fff;
              border: 2px solid #34bc9b;
          }
          .stepper .active .round-tab::after {
              display: none;
          }
          .stepper .disabled .round-tab {
              background: #fff;
              color: #f1f1f1;
              border-color: #f1f1f1;
          }
          .stepper .disabled .round-tab:hover {
              color: #4dd3b6;
              border: 2px solid #a6dfd3;
          }
          .stepper .disabled .round-tab::after {
              display: none;
          }

      </style>
  </head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>SC</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>S</b>is<b>C</b>ontrat</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        </nav>
    </header>