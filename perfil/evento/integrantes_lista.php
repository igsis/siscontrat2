<?php
include "includes/menu_interno.php";

$con = bancoMysqli();
$conn = bancoPDO();

$atracao_id = $_POST['idAtracao'];

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    if (isset($_POST['cadastra'])) {
        //
    } elseif (isset($_POST['edita'])) {
        //
    }
}

$sqlIntegrantes = "SELECT i.*, ai.funcao FROM atracao_integrante AS ai
                    INNER JOIN integrantes AS i ON ai.integrante_id = i.id
                    WHERE atracao_id = '$atracao_id'";
$integrantes = $con->query($sqlIntegrantes);

?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Integrantes</h2>
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

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblIntegrantes" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>RG</th>
                                    <th>Função</th>
                                    <th>Editar</th>
                                    <th>Apagar</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Um Nome Teste</td>
                                    <td>123.123.123-33</td>
                                    <td>1234556789</td>
                                    <td>Técnico de Som</td>
                                    <td>Btn Editar</td>
                                    <td>Btn Apagar</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>RG</th>
                                    <th>Função</th>
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

    </section>
    <!-- /.content -->
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblIntegrantes').DataTable({
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
