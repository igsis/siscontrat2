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
                echo "<li><a href=\"<?= $pasta ?>evento_edita\"><i class=\"fa fa-circle-o\"></i> <span>Evento</span></a></li>";
            }
            else{
                echo "<li><a href=\"<?= $pasta ?>evento_cadastro\"><i class=\"fa fa-circle-o\"></i> <span>Evento</span></a></li>";
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

                if($evento['tipo_evento_id'] == 1){//atração
                    $atracao_eventos = recuperaDados("atracao_eventos","evento_id",$idEvento);
                    $atracao = recuperaDados("atracoes","id",$atracao_eventos['atracao_id']);
                    $idCategoriaAtracao = $atracao['categoria_atracao_id'];

                    $array_teatro = array(3,7,23,24);
                    if(in_array($idCategoriaAtracao, $array_teatro)){
                        echo "<li><a href=\"".$pasta."especificidade_teatro_lista\"><i class=\"fa fa-circle-o\"></i> <span>Especificidade</span></a></li>";
                    }
                    $array_musica = array(10,11,15,17);
                    if(in_array($idCategoriaAtracao, $array_musica)){
                        echo "<li><a href=\"".$pasta."especificidade_musica_lista\"><i class=\"fa fa-circle-o\"></i> <span>Especificidade</span></a></li>";
                    }
                    if($idCategoriaAtracao == 2){
                        echo "<li><a href=\"".$pasta."especificidade_exposicao_lista\"><i class=\"fa fa-circle-o\"></i> <span>Especificidade</span></a></li>";
                    }
                    if($idCategoriaAtracao == 4 || $idCategoriaAtracao == 5){
                        echo "<li><a href=\"".$pasta."especificidade_oficinas_lista\"><i class=\"fa fa-circle-o\"></i> <span>Especificidade</span></a></li>";
                    }

                }
            }
            ?>
            <li><a href="#"><i class="fa fa-circle-o"></i> <span>Produtor</span></a></li>
            <li><a href="<?= $pasta ?>ocorrencia_lista"><i class="fa fa-circle-o"></i> <span>Ocorrência</span></a></li>
            <li><a href="<?= $pasta ?>pedido_cadastro"><i class="fa fa-circle-o"></i> <span>Pedido</span></a></li>
            <li><a href="?perfil=evento"><i class="fa fa-circle-o"></i> <span>Voltar</span></a></li>
            <li class="header">MAIS</li>
            <li><a href="../includes/ajuda.php"><i class="fa fa-user"></i><span>Minha Conta</span></a></li>
            <li><a href="../includes/ajuda.php"><i class="fa fa-question "></i><span>Ajuda</span></a></li>
            <li><a href="../../include/logoff.php"><i class="fa fa-sign-out"></i><span>Sair</span></a></li>
        </ul>
    </section>
</aside>