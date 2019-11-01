<?php
$pasta = "?perfil=emia&p=administrativo&sp=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">Administrativo</li>
            <li><a href="<?= $pasta ?>cargo&spp=listagem"><i class="fa fa-circle-o"></i>
                    <span>Cargo</span></a></li>

            <li><a href="<?= $pasta ?>vigencia&spp=listagem"><i class="fa fa-circle-o"></i>
                    <span>Vigência</span></a></li>

            <li><a href="?perfil=emia"><i class="fa fa-circle-o"></i>
                    <span>Voltar ao menu principal</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>

