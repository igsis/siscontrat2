<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || (isset($_POST['edita']))) {
    $modelo = trim(addslashes($_POST['modelo']));
    $texto = trim(addslashes($_POST['texto']));

    $sql = "INSERT INTO penalidades (modelo, texto) VALUES ('$modelo', '$texto')";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Cadastrado com sucesso");
        $idPenalidade = recuperaUltimo('penalidades');
    } else {
        $mensagem = mensagem("danger", "Erro ao cadastrar. Tente novamente.");
    }
}

if (isset($_POST['edita'])){
     $idPenalidade = $_POST['idPenalidade'];
     $sql = "UPDATE penalidades SET modelo = '$modelo', texto = '$texto' WHERE id = '$idPenalidade'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Atualizado com sucesso");
    } else {
        $mensagem = mensagem("danger", "Erro ao atualizar. Tente novamente.");
    }
}

if (isset($_POST['carregar'])) {
    $idPenalidade = $_POST['idPenalidade'];
}

$penal = recuperaDados('penalidades', 'id', $idPenalidade);
?>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Atualizar Penalidade</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Penalidade</h3>
                    </div>
                    <form method="POST" action="?perfil=contrato&p=admin&sp=penalidades_edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="modelo">Modelo: *</label>
                                    <input type="text" id="modelo" name="modelo" class="form-control" required
                                           value="<?= $penal['modelo'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group  col-md-12">
                                    <label for="msg">Texto: *</label>
                                    <textarea id="texto" name="texto" class="form-control"
                                           required
                                           rows="5"><?= $penal['texto'] ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=contrato&p=admin&sp=penalidades_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" id="idPenalidade" name="idPenalidade" value="<?= $idPenalidade ?>">
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