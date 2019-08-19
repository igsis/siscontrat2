<?php
$con = bancoMysqli();
$idUser = $_SESSION['idUser'];

if (isset($_POST['cadastra'])) {
    $projeto = addslashes($_POST['projeto']);

    $sql = "INSERT INTO projetos (projeto) VALUES ('$projeto')";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Projeto cadastrado com sucesso");
        $idProjeto = recuperaUltimo('projetos');
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao cadastrar o projeto. Tente novamente!");
    }
}

if (isset($_POST['edita'])) {
    $projeto = addslashes($_POST['projeto']);
    $idProjeto = $_POST['idProjeto'];

    $sql = "UPDATE projetos SET projeto = '$projeto' WHERE id = '$idProjeto'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Projeto atualizado com sucesso");
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao atualizar o projeto. Tente novamente!");
    }

}

if (isset($_POST['carregar'])) {
    $idProjeto = $_POST['idProjeto'];
}

$projeto = recuperaDados('projetos', 'id', $idProjeto);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Atualizar Projeto</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Projeto</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=administrativo&sp=projeto&spp=edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="projeto">Projeto *</label>
                                    <input type="text" id="projeto" name="projeto" required class="form-control" value="<?= $projeto['projeto'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=formacao&p=administrativo&sp=projeto&spp=index">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" id="idProjeto" name="idProjeto"
                                   value="<?= $idProjeto ?>">
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