<?php
$pasta = "?perfil=juridico&p=";
?>

<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="http://<?=$_SERVER['HTTP_HOST']?>/siscontrat/inicio"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">JURÍDICO</li>
            <li><a href="#"><i class="fa fa-circle-o"></i><span>Administrativo</span></a> </li>
            <li><a href="<?= $pasta ?>filtrar_evento&sp=busca_evento">
                    <i class="fa fa-circle-o"></i><span>Buscar por Evento</span>
                </a></li>
            <li><a href="<?= $pasta ?>filtrar_formacao&sp=pesquisa_formacao"><i class="fa fa-circle-o"></i>
                    <span>Buscar Formação</span></a>
            </li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>
