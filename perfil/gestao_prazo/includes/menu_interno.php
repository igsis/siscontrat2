<?php
$pasta = "?perfil=producao&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">Produção</li>
            <li><a href="<?= $pasta ?>busca_gestao"><i class="fa fa-circle-o"></i> <span>Buscar</span></a></li>
            <li><a href="<?= $pasta ?>"><i class="fa fa-reply"></i> <span>Voltar</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>
