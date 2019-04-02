<?php
$con = bancoMysqli();

if (isset($_POST['carregar'])) {
    $idEspaco = $_POST['idEspaco'];
}

if (isset($_POST['cadastra']) || (isset($_POST['edita']))) {
    $idLocal = $_POST['idLocal'] ?? NULL;
    $espaco = $_POST['espaco'];

    if (isset($_POST['cadastra'])) {
        $sql = "INSERT INTO espacos (local_id ,espaco)
                VALUES ('$idLocal', '$espaco')";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Espaço cadastrado com sucesso!");
            $idEspaco = recuperaUltimo('espacos');
        } else {
            $mensagem = mensagem("danger", "Erro no cadastro do espaço! Tente novamente.");
        }
    }

    if (isset($_POST['edita'])) {
        $idEspaco = $_POST['idEspaco'];

        $sql = "UPDATE espacos SET espaco = '$espaco' WHERE id = '$idEspaco'";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Espaço editado com sucesso!");
        } else {
            $mensagem = mensagem("danger", "Erro ao editar o espaço! Tente novamente.");
        }
    }
}
$espaco = recuperaDados('espacos', 'id', $idEspaco);

if ($espaco['publicado'] == 1) {
    $caminho = "?perfil=administrativo&p=instituicao&sp=edita_espaco";
} else if ($espaco['publicado'] == 2) {
    $caminho = "?perfil=administrativo&p=instituicao&sp=solicitacoes_local_espaco";
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Edição de Espaco</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Espaços</h3>
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
                                <div class="form-group col-md-12">
                                    <label for="sigla">Espaço: </label>
                                    <input type="text" class="form-control" id="espaco" name="espaco"
                                           value="<?= $espaco['espaco'] ?>" required>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <?php
                        if ($espaco['publicado'] == 1) {
                            ?>
                            <div class="box-footer">
                                <a href="?perfil=administrativo&p=instituicao&sp=instituicao_lista">
                                    <button type="button" class="btn btn-default">Voltar</button>
                                </a>
                                <input type="hidden" name="idEspaco" id="idEspaco" value="<?= $idEspaco ?>">
                                <button type="submit" name="edita" id="edita" class="btn btn-primary pull-right">
                                    Salvar
                                </button>
                            </div>
                            <?php
                        } else if ($espaco['publicado'] == 2) {
                            ?>
                            <div class="box-footer">
                                <a href="?perfil=administrativo&p=instituicao&sp=solicitacoes_local_espaco">
                                    <button type="button" class="btn btn-default">Voltar</button>
                                </a>
                                <input type="hidden" name="idEspaco" id="idEspaco" value="<?= $idEspaco ?>">
                                <button type="submit" name="recusarEspaco" id="recusarEspaco" class="btn btn-warning">
                                    Recusar espaco
                                </button>
                                <button type="submit" name="aceitarEspaco" id="aceitarEspaco"
                                        class="btn btn-primary pull-right">
                                    Aceitar espaço
                                </button>
                            </div>
                            <?php
                        }
                        ?>
                    </form>
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