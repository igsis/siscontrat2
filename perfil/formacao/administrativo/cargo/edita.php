<?php
$con = bancoMysqli();
$idUser = $_SESSION['idUser'];

if (isset($_POST['cadastra'])) {
    $cargo = addslashes($_POST['cargo']);
    $justificativa = addslashes($_POST['justificativa']);

    $sql = "INSERT INTO formacao_cargos (cargo, justificativa) VALUES ('$cargo', '$justificativa')";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Cargo cadastrado com sucesso");
        $idCargo = recuperaUltimo('formacao_cargos');
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao cadastrar o cargo. Tente novamente!");
    }
}

if (isset($_POST['edita'])) {
    $cargo = addslashes($_POST['cargo']);
    $justificativa = addslashes($_POST['justificativa']);
    $idCargo = $_POST['idCargo'];

    $sql = "UPDATE formacao_cargos SET cargo = '$cargo', justificativa = '$justificativa' WHERE id = '$idCargo'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Cargo atualizado com sucesso");
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao atualizar o cargo. Tente novamente!");
    }
}

if (isset($_POST['carregar'])) {
    $idCargo = $_POST['idCargo'];
}

$cargo = recuperaDados('formacao_cargos', 'id', $idCargo);
?>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Atualizar Cargo</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cargo</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=administrativo&sp=cargo&spp=edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="titulo">Cargo *</label>
                                    <input type="text" id="cargo" name="cargo" class="form-control" required
                                           value="<?= $cargo['cargo'] ?>">
                                </div>

                                <div class="form-group  col-md-8">
                                    <label for="msg">Justificativa *</label>
                                    <input type="text" id="justificativa" name="justificativa" class="form-control"
                                           required
                                           value="<?= $cargo['justificativa'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=formacao&p=administrativo&sp=cargo&spp=index">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" id="idCargo" name="idCargo" value="<?= $idCargo ?>">
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