<?php
$pasta = "?perfil=juridico&p=";
?>

<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/siscontrat/inicio"><i
                            class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">JURÍDICO</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i>
                    <span>Administrativo</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="?perfil=juridico&p=admin&sp=lista_modelo"><i class="fa fa-circle-o"></i> Modelos </a>
                    </li>

                </ul>
            </li>
            <li><a href="<?= $pasta ?>filtrar_evento&sp=busca_evento">
                    <i class="fa fa-circle-o"></i><span>Buscar por Evento</span>
                </a></li>
            <li><a href="<?= $pasta ?>filtrar_formacao&sp=pesquisa_formacao">
                    <i class="fa fa-circle-o"></i><span>Buscar Formação</span>
                </a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>
