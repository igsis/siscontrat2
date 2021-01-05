<?php
$con = bancoMysqli();
$idUser = $_SESSION['usuario_id_s'];

if (isset($_POST['cadastra'])) {
    $territorio = addslashes($_POST['territorio']);

    $sql = "INSERT INTO territorios (territorio) VALUES ('$territorio')";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Território cadastrado com sucesso");
        $idTerritorio = recuperaUltimo('territorios');
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao cadastrar o território. Tente novamente!");
    }
}

if (isset($_POST['edita'])) {
    $territorio = addslashes($_POST['territorio']);
    $idTerritorio = $_POST['idTerritorio'];

    $sql = "UPDATE territorios SET territorio = '$territorio' WHERE id = '$idTerritorio'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Território atualizado com sucesso");
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao atualizar o território. Tente novamente!");
    }

}

if (isset($_POST['carregar'])) {
    $idTerritorio = $_POST['idTerritorio'];
}

$territorio = recuperaDados('territorios', 'id', $idTerritorio);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Atualizar Território</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Território</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=administrativo&sp=territorio&spp=edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="territorio">Território *</label>
                                    <input type="text" id="territorio" name="territorio" required class="form-control" value="<?= $territorio['territorio'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=formacao&p=administrativo&sp=territorio&spp=index">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" id="idTerritorio" name="idTerritorio"
                                   value="<?= $idTerritorio ?>">
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