<?php
include "pagamento/includes/menu.php";
//include para pagamentos
if (isset($_GET['p'])) {
    $p = $_GET['p'];
    if (isset($_GET['sp'])) {
        $sp = $_GET['sp'];
        include "pagamento/" . $p . "/" . $sp . ".php";
    } else {
        include "pagamento/" . $p . ".php";
    }
} else {
    $p = "index";
    include "pagamento/" . $p . ".php";
}