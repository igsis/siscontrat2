<?php
$pasta = "?perfil=evento&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="http://<?=$_SERVER['HTTP_HOST']?>/siscontrat/inicio"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">Pedido Pessoa Jurídica</li>
            <?php
            if(isset($_SESSION['idEvento'])){
                $idEvento = $_SESSION['idEvento'];
                $evento = recuperaDados("eventos", "id",$idEvento);
                if($evento['contratacao'] == 1){
                    // echo "<li><a href=\"".$pasta."pedido\"><i class=\"fa fa-circle-o\"></i> <span>Pedido</span></a></li>";
                    
                }
                echo "<li><a href=\"".$pasta."pj_pesquisa\"><i class=\"fa fa-circle-o\"></i> <span>Pesquisar</span></a></li>";
                if (isset($_SESSION['idPj_pedido'])) {
                    echo "<li><a href=\"".$pasta."pj_edita\"><i class=\"fa fa-circle-o\"></i> <span>Editar</span></a></li>";
                }
            }
            ?>
            <li><a href="?perfil=evento&p=pedido"><i class="fa fa-circle-o"></i> <span>Voltar</span></a></li>
            <li class="header">MAIS</li>
            <li><a href="?perfil=usuario/minha_conta"><i class="fa fa-user"></i><span>Minha Conta</span></a></li>
            <li><a href="http://smcsistemas.prefeitura.sp.gov.br/manual/siscontrat/" target="_blank"><i class="fa fa-question "></i><span>Ajuda</span></a></li>
            <li><a href="../include/logoff.php"><i class="fa fa-sign-out"></i><span>Sair</span></a></li>
        </ul>
    </section>
</aside>