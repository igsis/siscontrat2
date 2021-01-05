<?php
//geram o insert pro framework da igsis
$pasta = "?perfil=evento&p=";
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="http://<?=$_SERVER['HTTP_HOST']?>/siscontrat/inicio"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">EVENTO</li>
            <li><a href="<?= $pasta ?>evento_cadastro"><i class="fa fa-circle-o"></i> <span>Novo</span></a></li>
            <li><a href="<?= $pasta ?>evento_lista"><i class="fa fa-circle-o"></i> <span>Carregar evento gravado</span></a></li>
            <li><a href="<?= $pasta ?>lista_pedidos_enviados"><i class="fa fa-circle-o"></i> <span>Acompanhar pedidos enviados</span></a></li>
            <li><a href="<?= $pasta ?>lista_eventos_enviados"><i class="fa fa-circle-o"></i> <span>Acompanhar eventos enviados</span></a></li>
            <li><a href="<?= $pasta ?>buscar_capac"><i class="fa fa-circle-o"></i> <span>Buscar no CAPAC</span></a></li>
            <li><a href="http://smcsistemas.prefeitura.sp.gov.br/smcproducao/" target="_blank"><i class="fa fa-circle-o"></i> <span>Informações de produção</span></a></li>
            <li><a href="<?= $pasta ?>adicionar_local"><i class="fa fa-circle-o"></i> <span>Adição de local</span></a></li>
            <li><a href="<?= $pasta ?>adicionar_espaco"><i class="fa fa-circle-o"></i> <span>Adição de espaço</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>