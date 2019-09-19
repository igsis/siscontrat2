<?php
$pasta = "?perfil=emia&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">EMIA</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i>
                    <span>Vigências</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=$pasta?>vigencia&sp=cadastra"><i class="fa fa-circle-o"></i> Cadastrar</a></li>
                    <li><a href="<?=$pasta?>vigencia&sp=listagem"><i class="fa fa-circle-o"></i> Listar</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i>
                    <span>Cargos</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=$pasta?>cargo&sp=cadastra"><i class="fa fa-circle-o"></i> Cadastrar</a></li>
                    <li><a href="<?=$pasta?>cargo&sp=listagem"><i class="fa fa-circle-o"></i> Listar</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i>
                    <span>Pessoa Física</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=$pasta?>pessoa_fisica&sp=pesquisa"><i class="fa fa-circle-o"></i> Cadastrar</a></li>
                    <li><a href="<?=$pasta?>pessoa_fisica&sp=listagem"><i class="fa fa-circle-o"></i> Listar</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i>
                    <span>Dados para Contratação</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=$pasta?>dados_contratacao&sp=cadastra"><i class="fa fa-circle-o"></i> Cadastrar</a></li>
                    <li><a href="<?=$pasta?>dados_contratacao&sp=listagem"><i class="fa fa-circle-o"></i> Listar</a></li>
                </ul>
            </li>

            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>
