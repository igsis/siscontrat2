<?php
$pasta = "?perfil=juridico&p=";
?>

<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">JURÍDICO</li>
            <li><a href="<?= $pasta ?>filtrar_evento&sp=pesquisa_evento">
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