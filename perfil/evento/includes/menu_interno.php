<?php
$con = bancoMysqli();
$pasta = "?perfil=evento&p=";

$eventoNovo = isset($_SESSION['idEvento']) ? false : true;

if (!$eventoNovo) {
    $idEvento = $_SESSION['idEvento'];
    $evento = recuperaDados("eventos", "id", $idEvento);
    $sqlAtracoes = "SELECT id FROM atracoes WHERE evento_id = '$idEvento' AND publicado = '1'";
    $nAtracoes = $con->query($sqlAtracoes)->num_rows;
    if (($evento['contratacao'] == 1 && $nAtracoes > 0) || ($evento['contratacao'] == 1 && $evento['tipo_evento_id'] == 2)) {
        $contratacao = true;
        $sqlPedido = "SELECT pessoa_tipo_id FROM pedidos WHERE origem_tipo_id = '1' AND origem_id = '$idEvento' AND publicado = '1'";
        $queryPedido = $con->query($sqlPedido);
        $nPedido = $queryPedido->num_rows;
            if ($nPedido > 0) {
                $tipoPedido = $queryPedido->fetch_assoc()['pessoa_tipo_id'];
            }
    } else {
        $contratacao = false;
    }
}
?>
<aside class="main-sidebar" xmlns:display="http://www.w3.org/1999/xhtml">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="http://<?=$_SERVER['HTTP_HOST']?>/siscontrat/inicio">
                    <i class="fa fa-home"></i>
                    <span>Home</span>
                </a>
            </li>

            <li class="header">EVENTO</li>

            <?php if($eventoNovo): ?>
                <li>
                    <a href="<?=$pasta?>evento_cadastro">
                        <i class="fa fa-circle-o text-green"></i>
                        <span>Evento</span>
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="<?= $pasta ?>evento_edita">
                        <i class="fa fa-circle-o text-green"></i>
                        <span>Evento</span>
                    </a>
                </li>
            <?php
                endif;

                if (!($eventoNovo)):
                    //atração
                    if($evento['tipo_evento_id'] == 1): ?>
                        <li>
                            <a href="<?=$pasta?>atracoes_lista">
                                <i class="fa fa-circle-o text-lime"></i>
                                <span>Atração</span>
                            </a>
                        </li>
                    <?php else: //filme ?>
                        <li>
                            <a href="<?=$pasta?>evento_cinema_lista">
                                <i class="fa fa-circle-o text-lime"></i>
                                <span>Filme</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li>
                        <a href="<?=$pasta?>arqs_com_prod">
                            <i class="fa fa-circle-o text-teal"></i>
                            <span>Anexos Comunicação/Produção</span>
                        </a>
                    </li>

                    <?php if($contratacao): ?>
                        <?php if ($nPedido == 0): ?>
                            <li>
                                <a href="<?= $pasta ?>pedido">
                                    <i class="fa fa-circle-o text-aqua"></i>
                                    <span>Pedido</span>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="treeview menu-open">
                                <a href="#">
                                    <i class="fa fa-circle-o text-aqua"></i>
                                    <span>Pedido</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu" style="display: block;">
                                    <li><a href="<?= $pasta ?>resumo_pedido"><i class="fa fa-circle-o"></i> Resumo do Pedido</a></li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Detalhes de Parcelas</a></li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Cadastro do Proponente</a></li>
                                    <?php if ($tipoPedido == 2): ?>
                                        <li><a href="#"><i class="fa fa-circle-o"></i> Líderes</a></li>
                                    <?php endif ?>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Parecer Artístico</a></li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Valor por Equipamento</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <li>
                        <a href="<?=$pasta?>finalizar">
                            <i class="fa fa-circle-o text-light-blue"></i>
                            <span>Finalizar</span>
                        </a>
                    </li>
                <?php endif; ?>

            <li>
                <a href="?perfil=evento">
                    <i class="fa fa-reply"></i>
                    <span>Voltar</span>
                </a>
            </li>
            <?php include "../perfil/includes/menu_mais.php"; ?>
        </ul>
    </section>
</aside>