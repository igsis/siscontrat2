<?php
//geram o insert pro framework da igsis
$pasta = "?perfil=evento&p=";
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">EVENTO</li>
            <li><a href="<?= $pasta ?>evento_cadastro"><i class="fa fa-circle-o"></i> <span>Evento</span></a></li>
            <?php
            if(isset($_SESSION['idEvento'])){
                $idEvento = $_SESSION['idEvento'];
                $evento = recuperaDados("eventos", "id",$idEvento);
                if($evento['tipo_evento_id'] == 1){//atração
                    echo "<li><a href=\"".$pasta."atracoes_lista\"><i class=\"fa fa-circle-o\"></i> <span>Atração</span></a></li>";
                }
                elseif($evento['tipo_evento_id'] == 2){//oficina
                    echo "<li><a href=\"".$pasta."oficina_cadastro\"><i class=\"fa fa-circle-o\"></i> <span>Oficina</span></a></li>";
                }
                else{
                    echo "<li><a href=\"".$pasta."filme_cadastro\"><i class=\"fa fa-circle-o\"></i> <span>Filme</span></a></li>";
                }
            }
            ?>
            <li><a href="<?= $pasta ?>pedido_cadastro"><i class="fa fa-circle-o"></i> <span>Pedido</span></a></li>
            <li><a href="?perfil=evento"><i class="fa fa-circle-o"></i> <span>Voltar</span></a></li>
            <li class="header">MAIS</li>
            <li><a href="../includes/ajuda.php"><i class="fa fa-user"></i><span>Minha Conta</span></a></li>
            <li><a href="../includes/ajuda.php"><i class="fa fa-question "></i><span>Ajuda</span></a></li>
            <li><a href="../../include/logoff.php"><i class="fa fa-sign-out"></i><span>Sair</span></a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>