<?php
include "../perfil/producao/includes/menu.php";

if (isset($_GET['p'])) {
    if (isset($_GET['sp'])) {
        $p = $_GET['p'];
        $sp = $_GET['sp'];

        if (isset($_GET['spp'])) {
            $spp = $_GET['spp'];
            include "producao/" . $p . "/" . $sp . "/" . $spp . ".php";
        } else {
            include "producao/" . $p . "/" . $sp . ".php";
        }
    }
} else {
    $p = "index";
    include "producao/" . $p . ".php";
}