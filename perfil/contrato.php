<?php
//include para contrato
if(isset($_GET['p']))
{
    if(isset($_GET['sp']))
    {
        $p = $_GET['p'];
        $sp = $_GET['sp'];
        include "contrato/".$p."/".$sp.".php";
    }
}
else
{
    $p = "index";
    include "contrato/".$p.".php";
}

include "../perfil/contrato/includes/menu.php";