<?php
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">COMUNICAÇÃO</li>
            <li><a href="?perfil=comunicacao&p=filtro"><i class="fa fa-circle-o"></i> <span>Filtrar</span></a></li>
<!--            <li><a href="?perfil=comunicacao&p=exportar_csv"><i class="fa fa-circle-o"></i> <span>Gerar Arquivo .csv</span></a></li>-->
<!--            <li><a href="#"><i class="fa fa-circle-o"></i> <span>Item 3</span></a></li>-->
<!--            <li><a href="#"><i class="fa fa-reply"></i> <span>Voltar</span></a></li>-->
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>