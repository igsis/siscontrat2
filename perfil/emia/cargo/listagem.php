<?php
$con = bancoMysqli();
if (isset($_POST['excluir'])) {
    $idEC = $_POST['idECDelete'];

    $sqlDelete = "UPDATE emia_cargos SET publicado = 0 WHERE id = '$idEC'";
    if (mysqli_query($con, $sqlDelete)) {
        $mensagem = mensagem("success", "Cargo excluido com sucesso");
    } else {
        $mensagem = mensagem("danger", "Erro ao excluir o cargo! Tente novamente!");
    }
}

if (isset($_POST['cadastra'])) {
    $cargo = $_POST['cargo'];
    $justificativa = $_POST['justificativa'];

    $sqlInsert = "INSERT INTO emia_cargos
                            (cargo, justificativa)
                            VALUES
                            ('$cargo', '$justificativa')";
    if (mysqli_query($con, $sqlInsert)) {
        $mensagem = mensagem("success", "Cadastrado com sucesso!");
    } else {
        $mensagem = mensagem("danger", "Erro ao cadastrar! Tente novamente.");
    }
}

$sql = "SELECT * FROM emia_cargos WHERE publicado = 1";
$query = mysqli_query($con, $sql);
?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2>Listagem de Cargos</h2>
        </div>
        <div class="box box-primary">
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <div class="box-header">
                <h4 class="box-title">Listagem</h4>
            </div>
            <div class="box-body">
                <table id="tblEmiaCargos" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Cargo</th>
                        <th>Justificativa</th>
                        <th width="5%">Editar</th>
                        <th width="5%">Excluir</th>
                    </tr>
                    </thead>
                    <?php
                    echo "<tbody>";
                    while ($ec = mysqli_fetch_array($query)) {
                        echo "<tr>";
                        echo "<td>" . $ec['cargo'] . "</td>";
                        echo "<td>" . $ec['justificativa'] . "</td>";
                        echo "<td>
                        <form action='?perfil=emia&p=cargo&sp=edita' method='POST'>
                        <input type='hidden' name='idECEdit' id='idECEdit' value='" . $ec['id'] . "'>
                        <button type='submit' name='edit' id='edit'  class='btn btn-block btn-primary'><span class='glyphicon glyphicon-edit'></span></button>
                        </form>
                        </td>";
                        echo "<td>
                        <form action='?perfil=emia&p=cargo&sp=listagem' method='POST'>
                        <input type='hidden' name='idECDelete' id='idECDelete' value='" . $ec['id'] . "'>
                        <button type='button' name='excluir' id='excluir' class='btn btn-block btn-danger' 
                        data-target='#exclusao' data-toggle='modal' data-id='" . $ec['id'] . "'>
                        <span class='glyphicon glyphicon-trash'></span></button>
                        </form>
                        </td>";
                    }
                    echo "</tbody>";
                    ?>
                    <tfoot>
                    <tr>
                        <th>Cargo</th>
                        <th>Justificativa</th>
                        <th>Editar</th>
                        <th>Excluir</th>
                    </tr>
                    </tfoot>
                </table>
                <div class="box-footer">
                    <a href="?perfil=emia">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <a href="?perfil=emia&p=cargo&sp=cadastra">
                        <button type="button" class="btn btn-primary pull-right"> Cadastrar um novo cargo </button>
                    </a>
                </div>
            </div>
        </div>
        <div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <form action="?perfil=emia&p=cargo&sp=listagem" method="POST">
                            <h4 class="modal-title">Confirmação de Exclusão</h4>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir o cargo?</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="idECDelete" id="idECDelete" value="<?= $ec['id'] ?>">
                        <input type="hidden" name="excluir" id="excluir">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                        </button>
                        <input type="submit" class="btn btn-danger btn-outline" name="exclui" value="Excluir">
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('#idECDelete').attr('value', `${id}`);
    })
</script>

<script type="text/javascript">
    $(function () {
        $('#tblEmiaCargos').DataTable({
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
