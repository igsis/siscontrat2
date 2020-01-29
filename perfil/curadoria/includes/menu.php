<?php
$pasta = "?perfil=curadoria&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">CURADORIA</li>
            <li><a href="<?= $pasta ?>buscar"><i class="fa fa-circle-o"></i> <span>Buscar</span></a></li>
            <li><a href="<?= $pasta ?>area_impressao"><i class="fa fa-circle-o"></i> <span>Relatórios</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>
