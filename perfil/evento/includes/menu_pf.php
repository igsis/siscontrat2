<?php
$pasta = "?perfil=evento&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="http://<?=$_SERVER['HTTP_HOST']?>/siscontrat/inicio"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">Pedido Pessoa Física</li>
            <?php
            if(isset($_SESSION['idEvento'])){
                $idEvento = $_SESSION['idEvento'];
                $evento = recuperaDados("eventos", "id",$idEvento);
                if($evento['contratacao'] == 1){
                    // echo "<li><a href=\"".$pasta."pedido\"><i class=\"fa fa-circle-o\"></i> <span>Pedido</span></a></li>";
                }

                echo "<li><a href=\"".$pasta."pf_cadastro_pesquisa\"><i class=\"fa fa-circle-o\"></i> <span>Pesquisar</span></a></li>";
                if (isset($_SESSION['idPj_pedido'])) {
                    echo "<li><a href=\"".$pasta."pf_edita\"><i class=\"fa fa-circle-o\"></i> <span>Editar</span></a></li>";
                }
            }
            ?>
            <li><a href="?perfil=evento&p=pedido"><i class="fa fa-circle-o"></i> <span>Voltar</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>