<?php
$pasta = "?perfil=gestao_prazo&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">Gestão de Prazos</li>
            <li><a href="<?= $pasta ?>busca_gestao#"><i class="fa fa-circle-o"></i> <span>Buscar</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i> <span>Item 2</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i> <span>Item 3</span></a></li>

            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>