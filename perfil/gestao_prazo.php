<?php
include "../perfil/gestao_prazo/includes/menu.php";

if (isset($_GET['p'])) {
    $p = $_GET['p'];
} else {
    $p = "index";
}
include "gestao_prazo/" . $p . ".php";
