<?php
$pasta = "?perfil=contrato&p=";
$idUsuarioDoMenu = $_SESSION['idUser'];
$nivelUsuario = recuperaDados('usuario_contratos', 'usuario_id', $idUsuarioDoMenu)['nivel_acesso'];
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">CONTRATOS</li>
            <li><a href="<?= $pasta ?>index"><i class="fa fa-tachometer"></i>
                <span>Início</span></a>
            </li>
            <?php
            if ($nivelUsuario != null && $nivelUsuario == 1) {
                ?>
                <li><a href="<?= $pasta ?>admin&sp=nivel_usuario">
                    <i class="fa fa-circle-o"></i><span>Administrativo</span></a>
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
                <li><a href="<?= $pasta ?>pesquisa_sem_operador"><i class="fa fa-circle-o"></i>
                    <span>Filtro sem Operador</span></a>
                </li>
                <li><a href="<?= $pasta ?>pesquisa_operador"><i class="fa fa-circle-o"></i>
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
