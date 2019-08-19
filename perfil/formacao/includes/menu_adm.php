<?php
$pasta = "?perfil=formacao&p=administrativo&sp=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">Administrativo</li>
            <li><a href="<?= $pasta ?>cargo&spp=index"><i class="fa fa-circle-o"></i>
                    <span>Cargo</span></a></li>

            <li><a href="<?= $pasta ?>coordenadoria&spp=index"><i class="fa fa-circle-o"></i>
                    <span>Coordenadoria</span></a></li>

            <li><a href="<?= $pasta ?>programa&spp=index>"><i class="fa fa-circle-o"></i>
                    <span>Programa</span></a></li>

            <li><a href="<?= $pasta ?>linguagem&spp=index"><i class="fa fa-circle-o"></i>
                    <span>Linguagem</span></a></li>

            <li><a href="<?= $pasta ?>projeto&spp=index"><i class="fa fa-circle-o"></i>
                    <span>Projeto</span></a></li>

            <li><a href="#"><i class="fa fa-circle-o"></i>
                    <span>Subprefeitura</span></a></li>

            <li><a href="#"><i class="fa fa-circle-o"></i>
                    <span>Território</span></a></li>

            <li><a href="#"><i class="fa fa-circle-o"></i>
                    <span>Vigência</span></a></li>

            <li><a href="#"><i class="fa fa-circle-o"></i>
                    <span>Habilitar / Desabilitar CAPAC</span></a></li>

            <li><a href="#"><i class="fa fa-circle-o"></i>
                    <span>Inscritos no CAPAC</span></a></li>

            <li><a href="#"><i class="fa fa-circle-o"></i>
                    <span>Voltar ao menu principal</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>