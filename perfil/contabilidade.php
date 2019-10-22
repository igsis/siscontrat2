<?php
//include para contratos
include "../perfil/contabilidade/includes/menu.php";

if (isset($_GET['p'])) {
    if (isset($_GET['sp'])) {
        $p = $_GET['p'];
        $sp = $_GET['sp'];

        if (isset($_GET['spp'])) {
            $spp = $_GET['spp'];
            include "contabilidade/" . $p . "/" . $sp . "/" . $spp . ".php";
        } else {
            include "contabilidade/" . $p . "/" . $sp . ".php";
        }
    }
} else {
    $p = "index";
    include "contabilidade/" . $p . ".php";
}
?>
