<?php
$pasta = "?perfil=agendao&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="http://<?=$_SERVER['HTTP_HOST']?>/siscontrat/inicio"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">TÍTULO</li>
            <li><a href="#"><i class="fa fa-circle-o"></i> <span>Item 1</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i> <span>Item 2</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i> <span>Item 3</span></a></li>
            <li><a href="#"><i class="fa fa-reply"></i> <span>Voltar</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>