<?php
//geram o insert pro framework da igsis
$pasta = "?perfil=agendao&p=";
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">AGENDÃO</li>
            <li><a href="<?= $pasta ?>evento_cadastro"><i class="fa fa-circle-o"></i> <span>Cadastra evento</span></a></li>
            <li><a href="<?= $pasta ?>listagem"><i class="fa fa-circle-o"></i> <span>Lista evento</span></a></li>
            <li><a href="<?= $pasta ?>pesquisa_exporta"><i class="fa fa-circle-o"></i> <span>Exporta Excel</span></a></li>
            <li><a href="<?= $pasta ?>adicionar_local"><i class="fa fa-circle-o"></i> <span>Adição de local</span></a></li>
            <li><a href="<?= $pasta ?>adicionar_espaco"><i class="fa fa-circle-o"></i> <span>Adição de espaço</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>