<?php
include "includes/menu_principal.php";

$url = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_chamados.php';

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$con = bancoMysqli();
$conn = bancoPDO();

if (isset($_POST['excluir'])) {
    $evento = $_POST['idEvent'];
    $stmt = $conn->prepare("UPDATE eventos SET publicado = 0 WHERE id = :id");
    $stmt->execute(['id' => $evento]);
    $mensagem = mensagem("success", "Evento excluído com sucesso!");

    $sqlDeletaAtracao = "UPDATE atracoes SET publicado = 0 WHERE evento_id = '$evento'";
    mysqli_query($con, $sqlDeletaAtracao);

    $sqlAtracoesDeletadas = "SELECT * FROM atracoes WHERE publicado = 0 AND evento_id = '$evento'";
    $queryAtracoesDeletadas = mysqli_query($con, $sqlAtracoesDeletadas);

    while ($atracaoDeletada = mysqli_fetch_array($queryAtracoesDeletadas)) {
        $idAtracao = $atracaoDeletada['id'];

        $sqlDeletaOcorrencia = "UPDATE ocorrencias SET publicado = 0 WHERE origem_ocorrencia_id = '$idAtracao'";
        mysqli_query($con, $sqlDeletaOcorrencia);
    }

    $sqlDeletaPedido = "UPDATE pedidos SET publicado = 0 WHERE origem_tipo_id = 1 AND origem_id = '$evento'";
    mysqli_query($con, $sqlDeletaPedido);

    gravarLog($sqlDeletaPedido);
    //gravarLog($sqlDeletaOcorrencia); A TABELA LOG NÃO TA NA MODELAGEM
    gravarLog($sqlDeletaAtracao);
}

$idUser = $_SESSION['usuario_id_s'];
$sql = "SELECT ev.id AS idEvento, ev.nome_evento, te.tipo_evento, es.status, ev.usuario_id, ev.usuario_id, ev.fiscal_id, ev.suplente_id 
        FROM eventos AS ev
        INNER JOIN tipo_eventos AS te on ev.tipo_evento_id = te.id
        INNER JOIN evento_status es on ev.evento_status_id = es.id
        WHERE publicado = 1 AND (usuario_id = '$idUser' OR fiscal_id = '$idUser' OR suplente_id = '$idUser') AND evento_status_id IN (1, 2, 5,6)";
$query = mysqli_query($con, $sql);

$num = numeroChamados(4);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <?= $num ?>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblEvento" class="table table-bordered table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>Nome do evento</th>
                                <th>Tipo do evento</th>
                                <th>Vínculo</th>
                                <th>Status</th>
                                <th>Chamados</th>
                                <th>Editar</th>
                                <th>Apagar</th>
                            </tr>
                            </thead>
                            <?php
                            echo "<tbody>";
                            while ($evento = mysqli_fetch_array($query)) {
                                $vinculo = '';

                                if ($evento['usuario_id'] == $idUser)
                                    $vinculo = 'Usuário';
                                else if ($evento['fiscal_id'] == $idUser)
                                    $vinculo = 'Fiscal';
                                else
                                    $vinculo = 'Suplente';

                                echo "<tr>";
                                echo "<td>" . $evento['nome_evento'] . "</td>";
                                echo "<td>" . $evento['tipo_evento'] . "</td>";
                                echo "<td>" . $vinculo . "</td>";
                                $disabled = '';
                                if ($evento['status'] == "Não aprovado") { ?>
                                    <td>
                                        <button type="button" class="btn-link" id="exibirMotivo"
                                                data-toggle="modal" data-target="#exibicao"
                                                data-id="<?= $evento['idEvento'] ?>"
                                                data-name="<?= $evento['nome_evento'] ?>"
                                                name="exibirMotivo">
                                            <p class="text-danger"><?= $evento['status'] ?></p>
                                        </button>
                                    </td>
                                <?php } else {
                                    if ($evento['status'] == "Aguardando")
                                    $disabled = '';

                                    echo "<td>" . $evento['status'] . "</td>";
                                }
                                echo "<td class='text-center'>";
                                if ($result = numeroChamados($evento['idEvento']) > 0){
                                    echo "<button class='btn bg-orange' type='button' 
                                            onclick='exibirChamados({$evento['idEvento']})'>
                                            {$result}
                                          </button>";
                                }else{
                                    echo "Sem chamados";
                                }
                                echo"</td>";
                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento&p=evento_edita\" role=\"form\">
                                    <input type='hidden' name='idEvento' value='" . $evento['idEvento'] . "'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\" " . $disabled . "><span class='glyphicon glyphicon-eye-open'></span></button>
                                    </form>
                                </td>";
                                ?>
                                <td>
                                    <form method="post" id="formExcluir">
                                        <input type="hidden" name="idEvento" value="<?= $evento['idEvento'] ?>">
                                        <button <?= $disabled ?> type="button" class="btn btn-block btn-danger"
                                                                 id="excluiEvento"
                                                                 data-toggle="modal" data-target="#exclusao"
                                                                 name="excluiEvento"
                                                                 data-name="<?= $evento['nome_evento'] ?>"
                                                                 data-id="<?= $evento['idEvento'] ?>"><span
                                                    class="glyphicon glyphicon-trash"></span></button>
                                    </form>
                                </td>
                                <?php
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Nome do evento</th>
                                <th>Tipo do evento</th>
                                <th>Vínculo</th>
                                <th>Status</th>
                                <th>Chamados</th>
                                <th>Editar</th>
                                <th>Apagar</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

        <!--.modal-->
        <div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
            <div class="modal-dialog">
                <!--Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmação de Exclusão</h4>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este evento?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="?perfil=evento&p=evento_lista" method="post">
                            <input type="hidden" name="idEvent" id="idEvent" value="">
                            <input type="hidden" name="apagar" id="apagar">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                            </button>
                            <input class=" btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="exibicao" class="modal modal fade in" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Motivo do Cancelamento</h4>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nome do Evento:</strong></p>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Motivo</th>
                                <th width="20%">Operador de Contratos</th>
                                <th width="15%">Data</th>
                            </tr>
                            </thead>
                            <tbody id="conteudoModal">

                            </tbody>
                        </table>
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
        $('#tblEvento').DataTable({
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

<!--<script>-->
<!--    $('#exibirMotivo').click(function () {-->
<!--        $('#exibicao').modal('show');-->
<!--        let nome = $(this).attr('data-name');-->
<!---->
<!--        console.log(nome);-->
<!--    })-->
<!--</script>-->

<script>
    const url = `<?=$url?>`;

    $('#exibicao').on('show.bs.modal', function (e) {
        let nome = $(e.relatedTarget).attr('data-name');
        let id = $(e.relatedTarget).attr('data-id');
        $(this).find('p').html(`<strong>Nome do Evento:</strong> ${nome}`);

        $('#exibicao').find('#conteudoModal').empty();

        // @TODO: Melhorar esse código
        $.getJSON(url + "?idEvento=" + id, function (data) {
            $.each(data, function (key, value) {
                $.each(value, function (key, valor) {
                    $('#exibicao').find('#conteudoModal').append(`<td>${valor}</td>`);
                    console.log(key + ": " + valor);
                })
            })
        })

        //let operador = <?//=$chamado['nome_completo']?>//;
        //let data = <?//=$chamado['data']?>//;

        // $(this).find('#conteudoModal').append(`<td>${motivo}</td>`);
    })
</script>

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let evento = $(e.relatedTarget).attr('data-name');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir o evento ${evento} ?`);
        $(this).find('#idEvent').attr('value', `${id}`);
    })
</script>