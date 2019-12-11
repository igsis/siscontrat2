<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || (isset($_POST['edita']))) {
    $nome = addslashes($_POST['nome']);
    $sigla = addslashes($_POST['sigla']);

    if (isset($_POST['cadastra'])) {
        $sql = "INSERT INTO instituicoes (nome, sigla)
                VALUES ('$nome', '$sigla')";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Instituicao cadastrada com sucesso!");
            $idInstituicao = recuperaUltimo('instituicoes');
        } else {
            $mensagem = mensagem("danger", "Erro no cadastro de instituição! Tente novamente.");
        }
    }

    if (isset($_POST['edita'])) {
        $idInstituicao = $_POST['idInstituicao'];

        $sql = "UPDATE instituicoes SET nome = '$nome', sigla = '$sigla' WHERE id = '$idInstituicao'";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Instituição editada com sucesso!");
        } else {
            $mensagem = mensagem("danger", "Erro ao salvar a instituição! Tente novamente.");
        }
    }
}

if (isset($_POST['carregar'])) {
    $idInstituicao = $_POST['idInstituicao'];
}

if (isset($_POST['excluir'])) {
    $idPerfil = $_POST['idPerfil'];
    $idModulo = $_POST['idModulo'];
    $stmt = $conn->prepare("DELETE FROM modulo_perfis WHERE perfil_id = :idPerfil AND modulo_id = :idModulo");
    $stmt->execute(['idPerfil' => $idPerfil, 'idModulo' => $idModulo]);
    $mensagem2 = mensagem("success", "Excluido com sucesso!");
}

$instituicao = recuperaDados('instituicoes', 'id', $idInstituicao);

$sql = "SELECT * FROM locais WHERE instituicao_id = '$idInstituicao'";
$query = mysqli_query($con, $sql);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Edição de Perfil</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Perfil</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=administrativo&p=instituicao&sp=edita_instituicao"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-9">
                                    <label for="nome">Nome da Instituição *</label>
                                    <input type="text" id="nome" name="nome" class="form-control" required
                                           value="<?= $instituicao['nome'] ?>" maxlength="60">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="token">Sigla *</label>
                                    <input type="text" id="sigla" name="sigla" class="form-control" required
                                           value="<?= $instituicao['sigla'] ?>" maxlength="8">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="?perfil=administrativo&p=instituicao&sp=instituicao_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" name="idInstituicao" id="idPerfil" value="<?= $idInstituicao ?>">
                            <button type="submit" name="edita" id="edita" class="btn btn-primary pull-right">
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem de Locais</h3>
                        <form action="?perfil=administrativo&p=instituicao&sp=adicionar_local" method="POST">
                            <input type="hidden" name="idInstituicao" id="idInstituicao" value="<?= $idInstituicao ?>">
                            <button type="submit" class="text-right btn btn-success" style="float: right">Adicionar
                                Local
                            </button>
                        </form>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem2)) {
                            echo $mensagem2;
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
                            while ($local = mysqli_fetch_array($query)) {
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
                            ?>
                            <tfoot>
                            <tr>
                                <th>Local</th>
                                <th>Logradouro</th>
                                <th>Editar</th>
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
</script>
