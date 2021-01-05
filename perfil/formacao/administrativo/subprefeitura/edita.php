<?php
$con = bancoMysqli();
$idUser = $_SESSION['usuario_id_s'];

if (isset($_POST['cadastra'])) {
    $subprefeitura = addslashes($_POST['subprefeitura']);

    $sql = "INSERT INTO subprefeituras (subprefeitura) VALUES ('$subprefeitura')";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Subprefeitura cadastrada com sucesso");
        $idSubprefeitura = recuperaUltimo('subprefeituras');
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao cadastrar a subprefeitura. Tente novamente!");
    }
}

if (isset($_POST['edita'])) {
    $subprefeitura = addslashes($_POST['subprefeitura']);
    $idSubprefeitura = $_POST['idSubprefeitura'];

    $sql = "UPDATE subprefeituras SET subprefeitura = '$subprefeitura' WHERE id = '$idSubprefeitura'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Subprefeitura atualizada com sucesso");
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao atualizar a subprefeitura. Tente novamente!");
    }

}

if (isset($_POST['carregar'])) {
    $idSubprefeitura = $_POST['idSubprefeitura'];
}

$subprefeitura = recuperaDados('subprefeituras', 'id', $idSubprefeitura);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Atualizar Subprefeitura</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Subprefeitura</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=administrativo&sp=subprefeitura&spp=edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="projeto">Subprefeitura *</label>
                                    <input type="text" id="subprefeitura" name="subprefeitura" required class="form-control" value="<?= $subprefeitura['subprefeitura'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=formacao&p=administrativo&sp=subprefeitura&spp=index">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" id="idSubprefeitura" name="idSubprefeitura"
                                   value="<?= $subprefeitura['id'] ?>">
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