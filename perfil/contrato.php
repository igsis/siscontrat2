<?php
//include para contrato
if(isset($_GET['p']))
{
    $p = $_GET['p'];
    if(isset($_GET['sp']))
    {
        $sp = $_GET['sp'];
        include "contrato/".$p."/".$sp.".php";
    }

    include "contrato/" .$p. ".php";
}
else
{
    $p = "index";
    include "contrato/".$p.".php";
}

include "contrato/includes/menu.php";