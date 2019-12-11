<?php
$con = bancoMysqli();
$conn = bancoPDO();

if(isset($_POST['excluir'])){
    $verbas = $_POST['idVerbas'];
    $stmt = $conn->prepare("DELETE FROM `verbas` WHERE id= :id");
    $stmt->execute(['id' => $verbas]);
    $mensagem = mensagem("success","Verba excluida com sucesso!");
}

$sql = "SELECT * FROM verbas";
$query = mysqli_query($con,$sql);
?>

<div class="content-wrapper">
    <section class="content">

        <h3 class="box-title">Lista de Verbas</h3>
        <a href="?perfil=administrativo&p=verbas&sp=cadastro_verbas"
           class="text-right btn btn-success"
           style="float: right">Adicionar Verba</a>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                 <div class="box-header">
                    <h3 class="box-title">Listagem</h3>
                 </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem)){
                           echo $mensagem;
                        }; ?>
                    </div>

                    <div class="box-body">
                        <table id="tblVerbas" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <th width="95%">Verbas</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                                </tr>
                            </thead>
                            <?php
                            echo "<tbody>";
                            while ($verbas = mysqli_fetch_array($query)) {
                                echo "<tr>";
                                echo "<td>" . $verbas['verba'] . "</td>";
                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=administrativo&p=verbas&sp=edita_verbas\" role=\"form\">
                                    <input type='hidden' name='idVerbas' value='" . $verbas['id'] . "'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><span class='glyphicon glyphicon-eye-open'></span></button>
                                    </form>
                                </td>";
                                ?>
                                <td>
                                    <form method='POST' id='formExcluir'>
                                        <button type="button" class="btn btn-block btn-danger"
                                                id="excluiVerbas"
                                                data-toggle="modal" data-target="#exclusao" name="excluiVerbas"
                                                data-name="<?= $verbas['verba'] ?>"
                                                data-id="<?= $verbas['id'] ?>">
                                            <span class='glyphicon glyphicon-trash'></span></button>
                                    </form>
                                </td>
                                <?php
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Verbas</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"></button>
                        <h4 class="modal-title">Confirmação de Exclusão</h4>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir esta verba?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="?perfil=administrativo&p=verbas&sp=verbas_lista"
                              method="post">
                            <input type="hidden" name="idVerbas" id="idVerbas" value="">
                            <input type="hidden" name="apagar" id="apagar">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                            <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
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
    $(function () {
            $('#tblVerbas').DataTable({
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
    $('#exclusao').on('show.bs.modal',function (e) {
        let nome = $(e.relatedTarget).attr('data-name');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir a verba: ${nome} ?`);
        $(this).find('#idVerbas').attr('value',`${id}`);
    })
</script>