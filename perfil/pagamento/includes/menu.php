<?php
$pasta = "?perfil=pagamento&p=";
$idUsuarioDoMenu = $_SESSION['usuario_id_s'];
$con = bancoMysqli();

$consultaNivelUsuario = $con->query("SELECT * FROM usuario_pagamentos WHERE usuario_id = $idUsuarioDoMenu");
$nivelUsuario = NULL;
if($consultaNivelUsuario->num_rows > 0){
    $nivelUsuario = mysqli_fetch_array($consultaNivelUsuario)['nivel_acesso'];
}
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="http://<?=$_SERVER['HTTP_HOST']?>/siscontrat/inicio"><i class="fa fa-home"></i><span>Home</span></a></li>

            <li class="header">PAGAMENTOS</li>
            <?php
            if ($nivelUsuario != null && $nivelUsuario == 1) {
                ?>
                <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i>
                    <span>Administrativo</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="?perfil=pagamento&p=admin&sp=lista_operadores"><i class="fa fa-circle-o"></i> Operadores </a></li>
                </ul>
            </li>

                <?php
            }
            ?>
            <li><a href="<?= $pasta ?>pesquisa_geral"><i class="fa fa-circle-o"></i> <span>Buscar</span></a></li>
            <li><a href="<?= $pasta ?>pesquisa_periodo"><i class="fa fa-circle-o"></i> <span>Buscar por período</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>
