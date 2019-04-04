<?php
$pasta = "?perfil=administrativo&p=";
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">MÓDULO ADMINISTRATIVO</li>
            <li><a href="<?= $pasta ?>instituicao&sp=solicitacoes_local_espaco"><i class="fa fa-circle-o text-fuchsia"></i><span>Aprovar solicitações</span></a></li>
            <li><a href="<?= $pasta ?>atualizacoes&sp=atualizacoes_lista"><i class="fa fa-circle-o text-aqua"></i><span>Atualizações</span></a></li>
            <li><a href="<?= $pasta ?>categoria&sp=categoria_lista"><i class="fa fa-circle-o text-orange"></i><span>Categorias</span></a></li>
            <li><a href="<?= $pasta ?>modulos&sp=modulos_lista"><i class="fa fa-circle-o text-white"></i><span>Gerenciar modulos</span></a></li>
            <li><a href="<?= $pasta ?>instituicao&sp=instituicao_lista"><i class="fa fa-circle-o text-green"></i><span>Instituições</span></a></li>
            <li><a href="<?= $pasta ?>perfil&sp=perfil_lista"><i class="fa fa-circle-o text-purple"></i><span>Perfil</span></a></li>
            <li><a href="<?= $pasta ?>projeto_especial&sp=projeto_especial_lista"><i class="fa fa-circle-o text-blue"></i><span>Projeto Especial</span></a></li>
            <li><a href="<?= $pasta ?>relacao_juridica&sp=relacao_juridica_lista"><i class="fa fa-circle-o text-black"></i><span>Relação Jurídica</span></a></li>
            <li><a href="<?= $pasta ?>verbas&sp=verbas_lista"><i class="fa fa-circle-o text-maroon"></i><span>Verbas</span></a></li>
            <li><a href="<?= $pasta ?>usuario&sp=usuario_lista"><i class="fa fa-circle-o text-red"></i><span>Usuários</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>