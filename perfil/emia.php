<?php
//include para contratos
include "../perfil/emia/includes/menu.php";

if (isset($_GET['p'])) {
    if (isset($_GET['sp'])) {
        $p = $_GET['p'];
        $sp = $_GET['sp'];

        if (isset($_GET['spp'])) {
            $spp = $_GET['spp'];
            include "emia/" . $p . "/" . $sp . "/" . $spp . ".php";
        } else {
            include "emia/" . $p . "/" . $sp . ".php";
        }
    }
} else {
    $p = "index";
    include "emia/" . $p . ".php";
}