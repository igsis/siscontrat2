<?php
$pasta = "?perfil=producao&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">PRODUÇÃO</li>
            <li><a href="<?= $pasta ?>eventos_novos_producao#"><i class="fa fa-circle-o"></i><span> Novos</span></a></li>
            <li><a href="<?= $pasta ?>eventos_verificados_producao#"><i class="fa fa-circle-o"></i><span> Visualizados</span></a></li>
            <li class="header">AGENDÃO</li>
            <li><a href="<?=$pasta?>novos_agendao_producao"><i class="fa fa-circle-o"></i><span> Novos</span></a></li>
            <li><a href="<?=$pasta?>visualizados_agendao_producao"><i class="fa fa-circle-o"></i><span> Visualizados</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>