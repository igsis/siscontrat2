<?php
$pasta = "?perfil=contrato&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">CONTRATOS</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i>
                    <span>Cadastro de Pessoas</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=$pasta?>pf&sp=pesquisa"><i class="fa fa-circle-o"></i> Pessoa Física</a></li>

                    <li><a href="#"><i class="fa fa-circle-o"></i>Pessoa Jurídica</a></li>

                </ul>
            </li>

            <li><a href="<?= $pasta ?>filtrar_contratos&sp=pesquisa_contratos"><i class="fa fa-circle-o"></i>
                    <span>Filtrar Contratos</span></a>
            </li>

            <li><a href="#"><i class="fa fa-circle-o"></i>
                    <span>Filtro sem Operador</span></a>
            </li>

            <li><a href="#"><i class="fa fa-circle-o"></i>
                    <span>Eventos sem Reenvio</span></a>
            </li>

            <li><a href="#"><i class="fa fa-circle-o"></i>
                    <span>Filtro Período/Operador</span></a>
            </li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>
