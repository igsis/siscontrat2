<?php
$pasta = "?perfil=contrato&p=";
$idUsuarioDoMenu = $_SESSION['usuario_id_s'];
$con = bancoMysqli();
$sqlUsuario = $con->query("SELECT nivel_acesso FROM usuario_contratos WHERE usuario_id = $idUsuarioDoMenu")->num_rows;
if($sqlUsuario > 0){
    $nivelUsuario = recuperaDados('usuario_contratos', 'usuario_id', $idUsuarioDoMenu)['nivel_acesso'];
}else{
    $nivelUsuario = null;
}
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="http://<?=$_SERVER['HTTP_HOST']?>/siscontrat/inicio"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">CONTRATOS</li>
            <li><a href="<?= $pasta ?>index"><i class="fa fa-tachometer"></i>
                <span>Início</span></a>
            </li>
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
                    <li><a href="<?= $pasta ?>admin&sp=nivel_usuario"><i class="fa fa-circle-o"></i> Usuários </a></li>
                    <li><a href="<?= $pasta ?>admin&sp=penalidades_lista"><i class="fa fa-circle-o"></i> Penalidades </a></li>
                </ul>
            </li>

                <?php
            }
            ?>
            <li><a href="<?= $pasta ?>pesquisa_contratos"><i class="fa fa-circle-o"></i>
                <span>Filtrar Contratos</span></a>
            </li>
            <?php
            if ($nivelUsuario != null) {
                ?>
                <li><a href="<?= $pasta ?>pesquisa_periodo"><i class="fa fa-circle-o"></i>
                    <span>Filtro Período/Operador</span></a>
                </li>
                <li><a href="<?= $pasta ?>sem_reenvio"><i class="fa fa-circle-o"></i>
                    <span>Eventos sem Reenvio</span></a>
                </li>
                <?php
            }
            ?>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>
