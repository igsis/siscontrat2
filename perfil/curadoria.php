<?php
include "../perfil/curadoria/includes/menu.php";
if (isset($_GET['p'])) {
    $p = $_GET['p'];
} else {
    $p = "index";
}
include "curadoria/" . $p . ".php";
