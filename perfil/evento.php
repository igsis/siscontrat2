<?php
//include para contratos
if(isset($_GET['p']))
{
    $p = $_GET['p'];
}
else
{
    $p = "index";
}
include "evento/".$p.".php";