<?php
$con = bancoMysqli();

if (isset($_SESSION['idEvento'])) {
    $eventoNovo = false;
} else {
    $eventoNovo = true;
}

$pasta = "?perfil=evento&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="http://<?=$_SERVER['HTTP_HOST']?>/siscontrat/inicio"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">EVENTO</li>

            <?php if($eventoNovo) { ?>
                <li><a href="<?=$pasta?>evento_cadastro"><i class="fa fa-circle-o text-green"></i> <span>Evento</span></a></li>
            <?php } else { ?>
                <li><a href="<?=$pasta?>evento_edita"><i class="fa fa-circle-o text-green"></i> <span>Evento</span></a></li>
            <?php }

            if (!($eventoNovo)) {
                $idEvento = $_SESSION['idEvento'];
                $evento = recuperaDados("eventos", "id",$idEvento);

                $atracoes = "SELECT * FROM atracoes WHERE evento_id = '$idEvento' AND publicado = '1'";
                $queryAtracoes = mysqli_query($con, $atracoes);
                $nAtracoes = mysqli_num_rows($queryAtracoes);

                if($evento['tipo_evento_id'] == 1) { //atração
                    echo "<li><a href=\"".$pasta."atracoes_lista\"><i class=\"fa fa-circle-o text-lime\"></i> <span>Atração</span></a></li>";
                } else { //filme
                    echo "<li><a href=\"".$pasta."evento_cinema_lista\"><i class=\"fa fa-circle-o text-lime\"></i> <span>Filme</span></a></li>";
                }
                
                ?>
                <li><a href="<?=$pasta?>arqs_com_prod"><i class="fa fa-circle-o text-teal"></i> <span>Anexos Comunicação/Produção</span></a></li>
                <?php
                if($evento['contratacao'] == 1 && $nAtracoes > 0) {
                    echo "<li><a href=\"".$pasta."pedido\"><i class=\"fa fa-circle-o text-aqua\"></i> <span>Pedido</span></a></li>";
                } elseif ($evento['contratacao'] == 1 && $evento['tipo_evento_id'] == 2) {
                    echo "<li><a href=\"".$pasta."pedido\"><i class=\"fa fa-circle-o text-aqua\"></i> <span>Pedido</span></a></li>";
                }
                echo "<li><a href=\"".$pasta."finalizar\"><i class=\"fa fa-circle-o text-light-blue\"></i> <span>Finalizar</span></a></li>";
            }
            
            ?>
            <li><a href="?perfil=evento"><i class="fa fa-reply"></i> <span>Voltar</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
            <!--
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
            -->
        </ul>
    </section>
</aside>