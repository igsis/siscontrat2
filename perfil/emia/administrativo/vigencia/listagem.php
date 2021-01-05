<?php
$con = bancoMysqli();
if (isset($_POST['excluir'])) {
    $idEV = $_POST['idEVDelete'];

    $deleteParcelas = $con->query("UPDATE emia_parcelas SET publicado = 0 WHERE emia_vigencia_id = $idEV");

    $sqlDelete = "UPDATE emia_vigencias SET publicado = 0 WHERE id = '$idEV'";
    if (mysqli_query($con, $sqlDelete)) {
        $mensagem = mensagem("success", "Vigência excluida com sucesso");
    } else {
        $mensagem = mensagem("danger", "Erro ao excluir a vigência! Tente novamente!");
    }
}

$sql = "SELECT * FROM emia_vigencias WHERE publicado = 1";
$query = mysqli_query($con, $sql);
?>
<div class="content-wrapper">
    <section class="content">
        <h3 class="box-title">EMIA - Vigências</h3>
        <a href="?perfil=emia&p=administrativo&sp=vigencia&spp=cadastra" class="text-right btn btn-success"
           style="float: right">Cadastrar uma nova vigência</a>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="box">
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <div class="box-header">
                <h4 class="box-title">Listagem</h4>
            </div>
            <div class="box-body">
                <table id="tblEmiaVigencias" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Ano</th>
                        <th>Descrição</th>
                        <th width="5%">Editar</th>
                        <th width="5%">Excluir</th>
                    </tr>
                    </thead>
                    <?php
                    echo "<tbody>";
                    while ($ev = mysqli_fetch_array($query)) {
                        echo "<tr>";
                        echo "<td>" . $ev['ano'] . "</td>";
                        echo "<td>" . $ev['descricao'] . "</td>";
                        echo "<td>
                        <form action='?perfil=emia&p=administrativo&sp=vigencia&spp=edita' method='POST'>
                        <input type='hidden' name='idEVEdit' id='idEVEdit' value='" . $ev['id'] . "'>
                        <button type='submit' name='edit' id='edit'  class='btn btn-block btn-primary'><span class='glyphicon glyphicon-edit'></span></button>
                        </form>
                        </td>";
                        echo "<td>
                        <button type='button' name='excluir' id='excluir' class='btn btn-block btn-danger' 
                        data-target='#exclusao' data-toggle='modal' data-id='" . $ev['id'] . "'>
                        <span class='glyphicon glyphicon-trash'></span></button>
                        </form>
                        </td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    ?>
                    <tfoot>
                    <tr>
                        <th>Ano</th>
                        <th>Descrição</th>
                        <th>Editar</th>
                        <th>Excluir</th>
                    </tr>
                    </tfoot>
                </table>
                <div class="box-footer">
                    <a href="?perfil=emia&p=administrativo&sp=index">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                </div>
            </div>
        </div>
        <div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <form action="?perfil=emia&p=administrativo&sp=vigencia&spp=listagem" method="POST">
                            <h4 class="modal-title">Confirmação de Exclusão</h4>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir a vigência?</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="idEVDelete" id="idEVDelete" value="">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                        </button>
                        <input type="submit" class="btn btn-danger btn-outline" name="excluir" value="Excluir">
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

        $(this).find('#idEVDelete').attr('value', `${id}`);
    })
</script>

<script type="text/javascript">
    $(function () {
        $('#tblEmiaVigencias').DataTable({
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