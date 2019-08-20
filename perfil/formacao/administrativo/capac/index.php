<?php
$con = bancoMysqli();
$conn = bancoCapac();

// COLOCAR AQUI O SELECT DO CAPAC
$sql = "SELECT * FROM formacao_cargos WHERE publicado = 1";
// em vez de con é conn
$query = mysqli_query($con, $sql);
$num_arrow = mysqli_num_rows($query);
?>

<div class="content-wrapper">
    <section class="content">
        <h3 class="box-title">Lista de Inscritos no CAPAC</h3>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3
                    </div>
                    <div class="box-body">
                        <table id="tblCapac" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>PEI</th>
                                <th width="5%">Visualizar</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            if ($num_arrow == 0) {
                                ?>
                                <tr>
                                    <!--  //ADAPTAR O COLSPAN -->
                                    <th colspan="2"><p align="center">Não foram encontrados registros</p></th>
                                </tr>
                                <?php
                            } else {
                                while ($cargo = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <!-- // PUXAR AS INFORMAÇÕES AQUI -->
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>PEI</th>
                                <th width="5%">Visualizar</th>
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
    $(function () {
        $('#tblCapac').DataTable({
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



