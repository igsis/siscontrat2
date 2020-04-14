<?php
$con = bancoMysqli();
$idUser = $_SESSION['usuario_id_s'];

if (isset($_POST['cadastra'])) {
    $linguagem = addslashes($_POST['linguagem']);

    $sql = "INSERT INTO linguagens (linguagem) VALUES ('$linguagem')";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Linguagem cadastrada com sucesso");
        $idLinguagem = recuperaUltimo('linguagens');
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao cadastrar a linguagem. Tente novamente!");
    }
}

if (isset($_POST['edita'])) {
    $linguagem = addslashes($_POST['linguagem']);
    $idLinguagem = $_POST['idLinguagem'];

    $sql = "UPDATE linguagens SET linguagem = '$linguagem' WHERE id = '$idLinguagem'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Linguagem atualizada com sucesso");
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao atualizar a linguagem. Tente novamente!");
    }

}

if (isset($_POST['carregar'])) {
    $idLinguagem = $_POST['idLinguagem'];
}

$linguagem = recuperaDados('linguagens', 'id', $idLinguagem);
?>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Atualizar Linguagem</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Linguagem</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=administrativo&sp=linguagem&spp=edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="titulo">Linguagem *</label>
                                    <input type="text" id="linguagem" name="linguagem" class="form-control"
                                           required
                                           value="<?= $linguagem['linguagem'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=formacao&p=administrativo&sp=linguagem&spp=index">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" id="idLinguagem" name="idLinguagem"
                                   value="<?= $idLinguagem ?>">
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