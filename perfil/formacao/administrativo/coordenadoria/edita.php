<?php
$con = bancoMysqli();
$idUser = $_SESSION['idUser'];

if (isset($_POST['cadastra'])) {
    $coordenadoria = addslashes($_POST['coordenadoria']);

    $sql = "INSERT INTO coordenadorias (coordenadoria) VALUES ('$coordenadoria')";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Coordenadoria cadastrado com sucesso");
        $idCoordenadoria = recuperaUltimo('coordenadorias');
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao cadastrar a coordenadoria. Tente novamente!");
    }
}

if (isset($_POST['edita'])) {
    $coordenadoria = addslashes($_POST['coordenadoria']);
    $idCoordenadoria = $_POST['idCoordenadoria'];

    $sql = "UPDATE coordenadorias SET coordenadoria = '$coordenadoria' WHERE id = '$idCoordenadoria'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Coordenadoria atualizada com sucesso");
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao atualizar a coordenadoria. Tente novamente!");
    }

}

if (isset($_POST['carregar'])) {
    $idCoordenadoria = $_POST['idCoordenadoria'];
}

$coordenadoria = recuperaDados('coordenadorias', 'id', $idCoordenadoria);
?>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Atualizar Coordenadoria</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Coordenadoria</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=administrativo&sp=coordenadoria&spp=edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="titulo">Coordenadoria *</label>
                                    <input type="text" id="coordenadoria" name="coordenadoria" class="form-control"
                                           required
                                           value="<?= $coordenadoria['coordenadoria'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=formacao&p=administrativo&sp=coordenadoria&spp=index">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" id="idCoordenadoria" name="idCoordenadoria"
                                   value="<?= $idCoordenadoria ?>">
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