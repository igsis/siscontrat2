<?php
$pasta = "?perfil=pesquisa&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">PESQUISA</li>
            <li><a href="<?= $pasta ?>pesquisa"><i class="fa fa-circle-o"></i> <span>Pesquisar</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>

