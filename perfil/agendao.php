<?php
if(isset($_GET['p']))
{
    $p = $_GET['p'];
}
else
{
    $p = "inicio";
}
include "agendao/".$p.".php";