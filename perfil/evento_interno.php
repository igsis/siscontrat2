<?php
//include para contratos
if(isset($_GET['p']))
{
    $p = $_GET['p'];
}
else
{
    $p = "inicio";
}
include "evento_interno/".$p.".php";