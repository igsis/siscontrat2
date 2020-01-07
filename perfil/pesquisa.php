<?php
include "../perfil/pesquisa/includes/menu.php";
if (isset($_GET['p'])) {
    $p = $_GET['p'];
} else {
    $p = "index";
}
include "pesquisa/" . $p . ".php";

