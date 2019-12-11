<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || (isset($_POST['edita']))) {
    $idInstituicao = $_POST['idInstituicao'] ?? NULL;
    $local = addslashes($_POST['local']);
    $cep = $_POST['cep'];
    $rua = addslashes($_POST['rua']);
    $numero = $_POST['numero'];
    $complemento = addslashes($_POST['complemento'] ?? NULL);
    $bairro = addslashes($_POST['bairro']);
    $cidade = addslashes($_POST['cidade']);
    $estado = addslashes($_POST['estado']);
    $zona = addslashes($_POST['zona']);

    if (isset($_POST['cadastra'])) {
        $sql = "INSERT INTO locais (instituicao_id, local, logradouro, numero, complemento, bairro, cidade, uf, cep, zona_id)
                VALUES ('$idInstituicao', '$local', '$rua', '$numero', '$complemento', '$bairro', '$cidade', '$estado', '$cep', '$zona')";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Local cadastrado com sucesso!");
            $idLocal = recuperaUltimo('locais');
        } else {
            $mensagem = mensagem("danger", "Erro no cadastro de local! Tente novamente.");
        }
    }

    if (isset($_POST['edita'])) {
        $idLocal = $_POST['idLocal'];

        $sql = "UPDATE locais SET local = '$local', logradouro = '$rua', numero = '$numero', complemento = '$complemento', bairro = '$bairro', cidade = '$cidade', uf = '$estado', cep = '$cep' WHERE id = '$idLocal'";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Local editada com sucesso!");
        } else {
            $mensagem = mensagem("danger", "Erro ao salvar o local! Tente novamente.");
        }
    }
}

if (isset($_POST['carregar'])) {
    $idLocal = $_POST['idLocal'];
}

$local = recuperaDados('locais', 'id', $idLocal);

if($local['publicado'] == 1){
    $caminho = "?perfil=administrativo&p=instituicao&sp=edita_local";
}else if($local['publicado'] == 2){
    $caminho = "?perfil=administrativo&p=instituicao&sp=solicitacoes_local_espaco";
}

$sql = "SELECT * FROM espacos WHERE local_id = '$idLocal'";
$query = mysqli_query($con, $sql);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Edição de Local</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Local</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="<?= $caminho ?>"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="cep">Local: *</label>
                                    <input type="text" class="form-control" name="local" id="local" required
                                           value="<?= $local['local'] ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="cep">CEP: *</label>
                                    <input type="text" class="form-control" name="cep" id="cep" maxlength="9"
                                           placeholder="Digite o CEP" required data-mask="00000-000"
                                           value="<?= $local['cep'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="cep">Zona: *</label>
                                    <select class="form-control" id="zona" name="zona">
                                        <?php
                                        geraOpcao('zonas', $local['zona_id']);
                                        ?>
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="rua">Rua: *</label>
                                    <input type="text" class="form-control" name="rua" id="rua"
                                           placeholder="Digite a rua" maxlength="200" readonly
                                           value="<?= $local['logradouro'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="numero">Número: *</label>
                                    <input type="number" name="numero" class="form-control" placeholder="Ex.: 10"
                                           required value="<?= $local['numero'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="complemento">Complemento:</label>
                                    <input type="text" name="complemento" class="form-control" maxlength="20"
                                           placeholder="Digite o complemento" value="<?= $local['complemento'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="bairro">Bairro: *</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro"
                                           placeholder="Digite o Bairro" maxlength="80" readonly
                                           value="<?= $local['bairro'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade"
                                           placeholder="Digite a cidade" maxlength="50" readonly
                                           value="<?= $local['cidade'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" class="form-control" name="estado" id="estado" maxlength="2"
                                           placeholder="Ex.: SP" readonly value="<?= $local['uf'] ?>">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <?php
                        if ($local['publicado'] == 1) {
                            ?>
                            <div class="box-footer">
                                <a href="?perfil=administrativo&p=instituicao&sp=instituicao_lista">
                                    <button type="button" class="btn btn-default">Voltar</button>
                                </a>
                                <input type="hidden" name="idLocal" id="idLocal" value="<?= $idLocal ?>">
                                <button type="submit" name="edita" id="edita" class="btn btn-primary pull-right">
                                    Salvar
                                </button>
                            </div>
                            <?php
                        } else if ($local['publicado'] == 2) {
                            ?>
                            <div class="box-footer">
                                <a href="?perfil=administrativo&p=instituicao&sp=solicitacoes_local_espaco">
                                    <button type="button" class="btn btn-default">Voltar</button>
                                </a>
                                <input type="hidden" name="idLocal" id="idLocal" value="<?= $idLocal ?>">
                                <button type="submit" name="recusarLocal" id="recusarLocal" class="btn btn-warning">
                                    Recusar local
                                </button>
                                <button type="submit" name="aceitarLocal" id="aceitarLocal" class="btn btn-primary pull-right">
                                    Aceitar local
                                </button>
                            </div>
                            <?php
                        } ?>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <?php
        if ($local['publicado'] == 1) {
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Listagem de Espaços</h3>
                            <form action="?perfil=administrativo&p=instituicao&sp=adicionar_espaco" method="POST">
                                <input type="hidden" name="idLocal" id="idLocal" value="<?= $idLocal ?>">
                                <button type="submit" class="text-right btn btn-success" style="float: right">Adicionar
                                    Espaço
                                </button>
                            </form>
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
                                while ($espaco = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    echo "<td>" . $espaco['espaco'] . "</td>";
                                    echo "<td>
                                    <form method=\"POST\" action=\"?perfil=administrativo&p=instituicao&sp=edita_espaco\" role=\"form\">
                                    <input type='hidden' name='idEspaco' value='" . $espaco['id'] . "'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><span class='glyphicon glyphicon-eye-open'></span></button>
                                    </form>
                                </td>";
                                }
                                ?>
                                <tfoot>
                                <tr>
                                    <th>Espaço</th>
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
            <?php
        }

        ?>
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
