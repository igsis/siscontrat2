<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">MÓDULO ADMINISTRATIVO</li>
            <li><a href="?perfil=administrativo&p=perfil&sp=perfil_lista"><i class="fa fa-circle-o text-purple"></i><span>Perfil</span></a></li>
            <li><a href="?perfil=administrativo&p=usuario&sp=usuario_lista"><i class="fa fa-circle-o text-red"></i><span>Usuários</span></a></li>
            <li><a href="?perfil=administrativo&p=projeto_especial&sp=projeto_especial_lista"><i class="fa fa-circle-o text-blue"></i><span>Projeto Especial</span></a></li>
            <li><a href="?perfil=administrativo&p=modulos&sp=modulos_lista"><i class="fa fa-circle-o text-aqua"></i><span>Gerenciar modulos</span></a></li>
            <li><a href="?perfil=administrativo&p=categoria&sp=categoria_lista"><i class="fa fa-circle-o text-orange"></i><span>Categorias</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>