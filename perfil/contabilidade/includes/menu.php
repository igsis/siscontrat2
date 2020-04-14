<?php
$pasta = "?perfil=contabilidade&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="http://<?=$_SERVER['HTTP_HOST']?>/siscontrat/inicio"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">CONTABILIDADE</li>
            <li><a href="<?= $pasta ?>eventos&sp=pesquisa"><i class="fa fa-circle-o"></i> <span>Buscar Eventos</span></a></li>
            <li><a href="<?= $pasta ?>formacao&sp=pesquisa"><i class="fa fa-circle-o"></i> <span>Buscar Formação</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>