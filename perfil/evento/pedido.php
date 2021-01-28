<?php

unset($_SESSION['idPj']);
unset($_SESSION['idPedido']);

$con = bancoMysqli();
$conn = bancoPDO();

if (isset($_POST['excluir'])) {
    $pedido = $_POST['idPedido'];
    $sql = "UPDATE `pedidos` SET publicado = 0 WHERE id = '$pedido'";
    $queryDelLider = "DELETE FROM lideres WHERE pedido_id = '$pedido'";

    if (mysqli_query($con, $queryDelLider)) {
        if (mysqli_query($con, $sql)) {
            unset($_SESSION['idPedido']);
            $mensagem = mensagem("success", "Pedido excluido com sucesso!");
            gravarLog($queryDelLider);
            gravarLog($sql);
        } else {
            $mensagem = mensagem("danger", "Erro ao excluir pedido! Tente novamente mais tarde.");
        }
    } else {
        $mensagem = mensagem("danger", "Erro ao excluir pedido! Tente novamente mais tarde.");
    }
}

$idEvento = $_SESSION['idEvento'];
$evento = recuperaDados("eventos", "id", $idEvento);
$sql = "SELECT * FROM pedidos WHERE origem_tipo_id = '1' AND origem_id = '$idEvento' AND publicado = '1'";
$query = $con->query($sql);
$pedido = $query->fetch_assoc();
$num = mysqli_num_rows($query);

if($evento['tipo_evento_id']==1) {
    $sql_atracao = "SELECT id, nome_atracao FROM atracoes WHERE evento_id = '$idEvento' AND publicado = 1";
    $query_atracao = mysqli_query($con, $sql_atracao);
    $nome_atracao = "";
    $num_atracao = mysqli_num_rows($query_atracao);
    while ($arr = mysqli_fetch_array($query_atracao)) {
        $nome_atracao = $nome_atracao . $arr['nome_atracao'] . " <br> ";
    }
    $nome_atracao = substr($nome_atracao, 0, -3);
} else{
    $sql_filmes = $con->query("SELECT f.titulo FROM eventos AS eve INNER JOIN filme_eventos fe on eve.id = fe.evento_id INNER JOIN filmes f on fe.filme_id = f.id WHERE eve.id = '$idEvento'");
    $nome_filme = "";
    while ($arr = mysqli_fetch_array($sql_filmes)){
        $nome_filme = $nome_filme . $arr['titulo'] . " <br> ";
    }
    $nome_atracao = $nome_filme = substr($nome_filme,0,-3);
}

if ($pedido != null) {
    if ($pedido['pessoa_tipo_id'] == 2) {
        $pj = recuperaDados("pessoa_juridicas", "id", $pedido['pessoa_juridica_id']);
        $nomeProponente = $pj['razao_social'];
        $idProponente = $pj['id'];
    } else {
        $pf = recuperaDados("pessoa_fisicas", "id", $pedido['pessoa_fisica_id']);
        $nomeProponente = $pf['nome'];
        $idProponente = $pf['id'];
    }
}
include "includes/menu_interno.php";
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Pedido de Contratação</h2>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Escolha um tipo de pessoa</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <?php
                        /*
                         * Caso não haja pedido de contratação registrado
                         */
                        if ($num == 0){
                            unset($_SESSION['idPedido']);
                            ?>
                            <div class="row">
                                <?php if($num_atracao == 1){ ?>
                                <div class="form-group col-md-offset-3 col-md-3">
                                    <form method="POST" action="?perfil=evento&p=pf_pesquisa" role="form">
                                        <button type="submit" name="pessoa_fisica"
                                                class="btn btn-block btn-primary btn-lg">Pessoa Física
                                        </button>
                                    </form>
                                </div>
                                <?php } ?>
                                <div class="form-group col-md-3 <?= $num_atracao > 1 ? 'col-md-offset-4' : '' ?>">
                                    <form method="POST" action="?perfil=evento&p=pj_pesquisa" role="form">
                                        <button type="submit" name="pesquisar_pessoa_juridica"
                                                class="btn btn-block btn-primary btn-lg">Pessoa Jurídica
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <?php
                        } else {
                        /*
                         * Caso haja pedido de contratração
                         */
                        ?>
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <th>Proponente</th>
                                <?php
                                if($evento['tipo_evento_id']==1){
                                    echo "<th>Atração</th>";
                                } else{
                                    echo "<th>Filme</th>";
                                }
                                ?>
                                <th width="15%">Ação</th>
                                <th width="5%">Excluir</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?=$nomeProponente?></td>
                                    <input type="hidden" name="idPessoa" value="<?=$idProponente?>">
                                    <td><?=$nome_atracao?></td>
                                    <td>
                                        <form method="POST" action="?perfil=evento&p=pedido_edita" role="form">
                                            <input type="hidden" name="idPedido" value="<?=$pedido['id']?>">
                                            <input type="hidden" name="idProponente" value="<?=$idProponente?>">
                                            <input type="hidden" name="tipoPessoa" value="<?=$pedido['pessoa_tipo_id']?>">
                                            <button type="submit" name='carregar' class="btn btn-primary btn-block">Editar pedido</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method='POST' id='formExcliuir'>
                                            <input type="hidden" name='idPedido' value="<?= $pedido['id'] ?>">
                                            <button type="button" class="btn btn-danger btn-block" id="excluiPedido"
                                                    data-toggle="modal" data-target="#exclusao" name="excluiPedido"
                                                    data-id="<?= $pedido['id'] ?>"><span
                                                        class='glyphicon glyphicon-trash'></span></button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!--.modal-->
        <div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmação de Exclusão</h4>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este pedido?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="?perfil=evento&p=pedido" method="post">
                            <input type="hidden" name="idPedido" id="idPedido" value="">
                            <input type="hidden" name="apagar" id="apagar">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                            </button>
                            <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Excluir">
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </section>
    <!-- /.content -->
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblPedido').DataTable({
            "language": {
                "url": 'bower_components/datatables.net/Portuguese-Brasil.json'
            },
            "responsive": true,
            "dom": "<'row'<'col-sm-6'l><'col-sm-6 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>",
        });
    });
</script>


<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('#idPedido').attr('value', `${id}`);
    })
</script>