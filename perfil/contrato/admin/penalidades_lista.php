<?php
$con = bancoMysqli();
$conn = bancoPDO();

if (isset($_POST['excluir'])) {
    $pena1 = $_POST['idPenalidade'];
    $stmt = $conn->prepare("UPDATE `penalidades`SET publicado = 0 WHERE id = :id");
    $stmt->execute(['id' => $pena1]);
    $mensagem = mensagem("success", "Penalidade excluida com sucesso!");
}

$sql = "SELECT * FROM penalidades where publicado = 1";
$query = mysqli_query($con, $sql);

?>

<div class="content-wrapper">
    <section class="content">
        <h3 class="box-title">Lista de Penalidades</h3>
        <a href="?perfil=contrato&p=admin&sp=penalidades_cadastra">
            <button type="button" class="btn btn-success pull-right">Criar uma nova penalidade</button>
        </a>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <div class="box-body">
                        <table id="tblPenalidade" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Modelo</th>
                                <th width="10%">Editar</th>
                                <th width="10%">Excluir</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                                while ($penal = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $penal['modelo'] ?></td>
                                        <td>
                                            <form action="?perfil=contrato&p=admin&sp=penalidades_edita" method="POST">
                                                <input type="hidden" name="idPenalidade" id="idPenalidade"
                                                       value="<?= $penal['id'] ?>">
                                                <button type="submit" name="carregar" id="carregar"
                                                        class="btn btn-primary btn-block"><span class="glyphicon glyphicon-eye-open"></span>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method='POST' id='formExcliuir'>
                                                <input type="hidden" name='idPenalidade' value="<?= $penal['id'] ?>">
                                                <button type="button" class="btn btn-block btn-danger" id="excluirPenalidade"
                                                        data-toggle="modal" data-target="#exclusao" name="excluirPenalidade"
                                                        data-nome="<?= $penal['modelo'] ?>"
                                                        data-id="<?= $penal['id'] ?>"><span
                                                            class='glyphicon glyphicon-trash'></span></button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Modelo</th>
                                <th width="10%">Editar</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmação de Exclusão</h4>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir esta penalidade?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="?perfil=contrato&p=admin&sp=penalidades_lista" method="post">
                            <input type="hidden" name="idPenalidade" id="idPenalidade" value="">
                            <input type="hidden" name="apagar" id="apagar">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                            </button>
                            <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
                        </form>
                    </div>
                </div>

            </div>
        </div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblPenalidade').DataTable({
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
        let nome = $(e.relatedTarget).attr('data-nome');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir a penalidade: ${nome} ?`);
        $(this).find('#idPenalidade').attr('value', `${id}`);
    })
</script>

