<?php
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$con = bancoMysqli();

$sql = "SELECT * FROM pessoa_juridicas";
$query = mysqli_query($con,$sql);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Pessoas Jurídicas</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblPj" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Razão Social</th>
                                <th>CNPJ</th>
                                <th>Última atualização</th>
                                <th>Carregar</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($pj = mysqli_fetch_array($query)){
                                echo "<tr>";
                                echo "<td>".$pj['razao_social']."</td>";
                                echo "<td>".$pj['cnpj']."</td>";
                                echo "<td>".exibirDataBr(retornaDataSemHora($pj['ultima_atualizacao']))."</td>";
                                echo "<td>
                                    <form method='POST' action='?perfil=contrato&p=pj&sp=edita' role='form'>
                                    <input type='hidden' name='idPessoaJuridica' value='".$pj['id']."'>
                                    <button type='submit' name='carregar' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-edit'></span></button>
                                    </form>
                                </td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Razão Social</th>
                                <th>CNPJ</th>
                                <th>Última atualização</th>
                                <th>Carregar</th>
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
        $('#tblPj').DataTable({
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

