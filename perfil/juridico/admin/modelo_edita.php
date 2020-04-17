<?php 
$con = bancoMysqli();
if (isset($_POST['carregar']));
    $idModelo = $_POST['idModelo'];

$modelo = recuperaDados('modelo_juridicos','id', $idModelo);

if (isset($_POST['cadastra']) || (isset($_POST['edita']))) {
    $amparo = trim(addslashes($_POST['amparo']));
    $finalizacao = trim(addslashes($_POST['finalizacao']));

    $sql = "INSERT INTO modelo_juridicos (amparo, finalizacao) VALUES ('$amparo', '$finalizacao')";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Cadastrado com sucesso");
    } else {
        $mensagem = mensagem("danger", "Erro ao cadastrar. Tente novamente.");
    }
}

if (isset($_POST['edita'])){
     $idModelo  = $_POST['idModelo'];
     $sql = "UPDATE modelo_juridicos SET amparo = '$amparo', finalizacao = '$finalizacao' WHERE id = '$idModelo'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Atualizado com sucesso");
    } else {
        $mensagem = mensagem("danger", "Erro ao atualizar. Tente novamente.");
    }
}
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Atualizar Modelo</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Modelo</h3>
                    </div>
                    <form method="POST" action="?perfil=juridico&p=admin&sp=modelo_edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="amparo">Amparo: *</label>
                                    <textarea id="amparo" name="amparo" class="form-control" required
                                        rows="5"><?= $modelo['amparo'] ?>
                                    </textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group  col-md-12">
                                    <label for="finalizacao">Finalização: *</label>
                                    <textarea id="finalizacao" name="finalizacao" class="form-control" required
                                        rows="5"><?= $modelo['finalizacao'] ?>
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=juridico&p=admin&sp=lista_modelo">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" id="idModelo" name="idModelo" value="<?= $idModelo ?>">
                            <button type="submit" name="edita" id="edita" class="btn btn-primary pull-right">
                                Gravar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>