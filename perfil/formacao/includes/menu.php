<?php
$pasta = "?perfil=formacao&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">FORMAÇÃO</li>
            <li><a href="<?= $pasta ?>administrativo&sp=index"><i class="fa fa-circle-o"></i>
                    <span>Acesso administrativo</span></a></li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i>
                    <span>Pessoa Física</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= $pasta ?>pessoa_fisica&sp=pesquisa"><i class="fa fa-circle-o"></i> Cadastrar</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Importar do CAPAC</a></li>
                    <li><a href="<?= $pasta ?>pessoa_fisica&sp=lista"><i class="fa fa-circle-o"></i> Listar</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i>
                    <span>Dados para contratação</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= $pasta ?>dados_contratacao&sp=cadastro"><i class="fa fa-circle-o"></i> Cadastrar </a></li>
                    <li><a href="<?= $pasta ?>dados_contratacao&sp=listagem"><i class="fa fa-circle-o"></i> Lista </a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i>
                    <span>Pedido de contratação</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Cadastrar</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Listar</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Exportar para Excel</a></li>
                </ul>
            </li>

            <li><a href="<?= $pasta ?>pagamento&sp=index"><i class="fa fa-circle-o"></i>
                    <span>Pagamento</span></a></li>

            <li><a href="#"><i class="fa fa-circle-o"></i>
                    <span>Concluir processo</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>