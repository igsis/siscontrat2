<?php
include "juridico/includes/menu.php";
//include para contrato
if (isset($_GET['p'])) {
    $p = $_GET['p'];
    if (isset($_GET['sp'])) {
        $sp = $_GET['sp'];
        include "juridico/" . $p . "/" . $sp . ".php";
    } else {
        include "juridico/" . $p . ".php";
    }
} else {
    $p = "index";
    include "juridico/" . $p . ".php";
}

