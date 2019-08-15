<?php
//include para contratos
include "../perfil/formacao/includes/menu.php";

if (isset($_GET['p'])) {
    if (isset($_GET['sp'])) {
        $p = $_GET['p'];
        $sp = $_GET['sp'];


        if ($p == 'administrativo') {

            include "../perfil/formacao/includes/menu_adm.php";
        }

        if (isset($_GET['spp'])) {
            $spp = $_GET['spp'];
            include "formacao/" . $p . "/" . $sp . "/" . $spp . ".php";
        } else {
            include "formacao/" . $p . "/" . $sp . ".php";
        }
    }
} else {
    $p = "index";
    include "formacao/" . $p . ".php";
}
