<?php
$con = bancoMysqli();
$conn = bancoPDO();

if(isset($_POST['aceitarLocal'])){
    $idLocal = $_POST['idLocal'];

    $sql = "UPDATE locais SET publicado = 1 WHERE id = '$idLocal'";

    if (mysqli_query($con, $sql)) {
        gravarLog($sql);
        $mensagem = mensagem("success", "Local aceito com sucesso!");
    } else {
        $mensagem = mensagem("danger", "Erro ao aceitar o cadastro de local! Tente novamente.");
    }
}else if(isset($_POST['recusarLocal'])){
    $idLocal = $_POST['idLocal'];

    $sql = "UPDATE locais SET publicado = 0 WHERE id = '$idLocal'";

    if (mysqli_query($con, $sql)) {
        gravarLog($sql);
        $mensagem = mensagem("success", "Local recusado com sucesso!");
    } else {
        $mensagem = mensagem("danger", "Erro ao recusar o local! Tente novamente.");
    }
}

if(isset($_POST['aceitarEspaco'])){
    $idEspaco = $_POST['idEspaco'];

    $sql = "UPDATE espacos SET publicado = 1 WHERE id = '$idEspaco'";

    if (mysqli_query($con, $sql)) {
        gravarLog($sql);
        $mensagem2 = mensagem("success", "Espaço aceito com sucesso!");
    } else {
        $mensagem2 = mensagem("danger", "Erro ao aceitar o cadastro de espaço! Tente novamente.");
    }
}else if(isset($_POST['recusarEspaco'])){
    $idEspaco = $_POST['idEspaco'];

    $sql = "UPDATE espacos SET publicado = 0 WHERE id = '$idEspaco'";

    if (mysqli_query($con, $sql)) {
        gravarLog($sql);
        $mensagem2 = mensagem("success", "Local recusado com sucesso!");
    } else {
        $mensagem2 = mensagem("danger", "Erro ao recusar o local! Tente novamente.");
    }
}

$sql_local = "SELECT * FROM locais WHERE publicado = 2";
$sql_espaco = "SELECT * FROM espacos WHERE publicado = 2";

$query_local = mysqli_query($con, $sql_local);
$query_espaco = mysqli_query($con, $sql_espaco);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Lista de solicitações</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem de Locais</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblLocal" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Local</th>
                                <th>Logradouro</th>
                                <th width="5%">Editar</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($local = mysqli_fetch_array($query_local)) {
                                echo "<tr>";
                                echo "<td>" . $local['local'] . "</td>";
                                echo "<td>" . $local['logradouro'] . "</td>";
                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=administrativo&p=instituicao&sp=edita_local\" role=\"form\">
                                    <input type='hidden' name='idLocal' value='" . $local['id'] . "'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><span class='glyphicon glyphicon-eye-open'></span></button>
                                    </form>
                                </td>";
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Local</th>
                                <th>Logradouro</th>
                                <th width="5%">Editar</th>
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

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem de Espaços</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem2)) {
                            echo $mensagem2;
                        }; ?>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblEspaco" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Espaço</th>
                                <th width="5%">Editar</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($espaco = mysqli_fetch_array($query_espaco)) {
                                echo "<tr>";
                                echo "<td>" . $espaco['espaco'] . "</td>";
                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=administrativo&p=instituicao&sp=edita_espaco\" role=\"form\">
                                    <input type='hidden' name='idEspaco' value='" . $espaco['id'] . "'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><span class='glyphicon glyphicon-eye-open'></span></button>
                                    </form>
                                </td>";
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Espaço</th>
                                <th width="5%">Editar</th>
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
        $('#tblLocal').DataTable({
            "language": {
                "url": 'bower_components/datatables.net/Portuguese-Brasil.json'
            },
            "responsive": true,
            "dom": "<'row'<'col-sm-6'l><'col-sm-6 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>",
        });
    });

    $(function () {
        $('#tblEspaco').DataTable({
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