<?php
$pasta = "?perfil=producao&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">PRODUÇÃO</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i>
                    <span>Eventos</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= $pasta ?>eventos&sp=novos"><i class="fa fa-circle-o"></i> Novos
                        </a></li>
                    <li><a href="<?= $pasta ?>eventos&sp=verificados"><i class="fa fa-circle-o"></i> Verificados </a>
                    </li>
                    <li><a href="<?= $pasta ?>eventos&sp=pesquisa"><i class="fa fa-circle-o"></i> Exportar
                        </a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i>
                    <span>Agendões</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= $pasta ?>agendoes&sp=novos"><i class="fa fa-circle-o"></i> Novos
                        </a></li>
                    <li><a href="<?= $pasta ?>agendoes&sp=verificados"><i class="fa fa-circle-o"></i> Verificados </a>
                    </li>
                    <li><a href="<?= $pasta ?>agendoes&sp=pesquisa"><i class="fa fa-circle-o"></i> Exportar
                        </a></li>
                </ul>
            </li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>
