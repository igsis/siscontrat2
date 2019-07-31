<?php
$pasta = "?perfil=producao&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">EVENTOS - PRODUÇÃO</li>
            <li><a href="<?= $pasta ?>eventos_novos_producao#"><i class="fa fa-circle-o"></i><span> Novos</span></a></li>
            <li><a href="<?= $pasta ?>eventos_verificados_producao#"><i class="fa fa-circle-o"></i> <span> Visualizados</span></a></li>
            <li class="header">AGENDÃO</li>
            <li><a href="<?=$pasta?>agendoes_novos_producao"><i class="fa fa-circle-o"></i><span> Novos</span></a></li>
            <li><a href="<?=$pasta?>agendoes_visualizados_producao"><i class="fa fa-circle-o"></i><span> Visualizados</span></a></li>
            <li><a href="<?= $pasta ?>producao_&p=index#"><i class="fa fa-reply"></i> <span> Voltar</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>