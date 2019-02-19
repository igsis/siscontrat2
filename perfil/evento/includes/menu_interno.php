<?php
$pasta = "?perfil=evento&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="?secao=perfil"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">EVENTO</li>
            <?php
            if(isset($_SESSION['idEvento'])){
                echo "<li><a href=\"".$pasta."evento_edita\"><i class=\"fa fa-circle-o\"></i> <span>Evento</span></a></li>";
            }
            else{
                echo "<li><a href=\"".$pasta."evento_cadastro\"><i class=\"fa fa-circle-o\"></i> <span>Evento</span></a></li>";
            }
            if(isset($_SESSION['idEvento'])){
                $idEvento = $_SESSION['idEvento'];
                $evento = recuperaDados("eventos", "id",$idEvento);
                if($evento['tipo_evento_id'] == 1){ //atração
                    echo "<li><a href=\"".$pasta."atracoes_lista\"><i class=\"fa fa-circle-o\"></i> <span>Atração</span></a></li>";
                }
                else{ //filme
                    echo "<li><a href=\"".$pasta."evento_cinema_lista\"><i class=\"fa fa-circle-o\"></i> <span>Filme</span></a></li>";
                }
                if($evento['contratacao'] == 1){
                    echo "<li><a href=\"".$pasta."pedido\"><i class=\"fa fa-circle-o\"></i> <span>Pedido</span></a></li>";
                }
            }
            ?>
            <li><a href="?perfil=evento"><i class="fa fa-circle-o"></i> <span>Voltar</span></a></li>
            <li class="header">MAIS</li>
            <li><a href="?perfil=usuario/minha_conta"><i class="fa fa-user"></i><span>Minha Conta</span></a></li>
            <li><a href="../includes/ajuda.php"><i class="fa fa-question "></i><span>Ajuda</span></a></li>
            <li><a href="../include/logoff.php"><i class="fa fa-sign-out"></i><span>Sair</span></a></li>
        </ul>
    </section>
</aside>