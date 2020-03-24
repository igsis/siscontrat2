<?php
$pasta = "?perfil=pagamento&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="http://<?=$_SERVER['HTTP_HOST']?>/siscontrat/inicio"><i class="fa fa-home"></i><span>Home</span></a></li>

            <li class="header">PAGAMENTOS</li>

            <li><a href="<?= $pasta ?>pesquisa_geral"><i class="fa fa-circle-o"></i> <span>Buscar</span></a></li>

            <li><a href="<?= $pasta ?>pesquisa_periodo"><i class="fa fa-circle-o"></i> <span>Buscar por período</span></a></li>

            <li><a href="<?= $pasta ?>pesquisa_operador"><i class="fa fa-circle-o"></i> <span>Buscar por data kit / operador</span></a></li>

            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>
