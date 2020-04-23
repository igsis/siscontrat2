<?php
$con = bancoMysqli();

$sql = "SELECT * FROM modelo_juridicos";
$query = mysqli_query($con, $sql);
?>

<div class="content-wrapper">
    <section class="content">
        <h3 class="box-title">Lista de Modelos</h3>
        <a href="?perfil=juridico&p=admin&sp=cadastra_modelo">
            <button type="button" class="btn btn-success pull-right">Adicionar um novo modelo</button>
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
                        <table id="tblModelo" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Modelo</th>
                                <th>Finalização</th>
                                <th width="10%">Editar</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                    <td><?= $sql['nome'] ?></td>
                                        <td><?= $sql['amparo'] ?></td>
                                        <td><?= $sql['finalizacao'] ?></td>
                                        <td>
                                            <form action="?perfil=juridico&p=admin&sp=modelo_edita" method="POST">
                                                <input type="hidden" name="idModelo" id="idModelo"
                                                       value="<?= $sql['id'] ?>">
                                                <button type="submit" name="carregar" id="carregar"
                                                        class="btn btn-primary btn-block"><span class="glyphicon glyphicon-eye-open"></span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                            <th>Nome</th>
                                <th>Modelo</th>
                                <th>Finalização</th>
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

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('#idUsuario').attr('value', `${id}`);
    })
</script>

<script type="text/javascript">
    $(function () {
        $('#tblResultado').DataTable({
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