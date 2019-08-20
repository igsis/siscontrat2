<?php
$con = bancoMysqli();
$idUser = $_SESSION['idUser'];

if (isset($_POST['cadastra'])) {
    $ano = $_POST['ano'];
    $descricao = addslashes($_POST['descricao']);

    $sql = "INSERT INTO formacao_vigencias (ano, descricao) VALUES ('$ano', '$descricao')";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Vigência cadastrada com sucesso");
        $idVigencia = recuperaUltimo('formacao_vigencias');
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao cadastrar a vigência. Tente novamente!");
    }
}

if (isset($_POST['edita'])) {
    $ano = $_POST['ano'];
    $descricao = addslashes($_POST['descricao']);
    $idVigencia = $_POST['idVigencia'];

    $sql = "UPDATE formacao_vigencias SET ano = '$ano', descricao = '$descricao' WHERE id = '$idVigencia'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Vigência atualizado com sucesso");
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao atualizar a vigência. Tente novamente!");
    }

}

if (isset($_POST['carregar'])) {
    $idVigencia = $_POST['idVigencia'];
}

$vigencia = recuperaDados('formacao_vigencias', 'id', $idVigencia);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Atualizar Vigência</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Vigência</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=administrativo&sp=vigencia&spp=edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label for="ano">Ano *</label>
                                    <input type="number" min="2018" id="ano" name="ano" required class="form-control" value="<?= $vigencia['ano'] ?>">
                                </div>

                                <div class="form-group col-md-10">
                                    <label for="descricao">Descrição *</label>
                                    <input type="text" id="descricao" name="descricao" class="form-control" required value="<?= $vigencia['descricao'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=formacao&p=administrativo&sp=vigencia&spp=index">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" id="idVigencia" name="idVigencia"
                                   value="<?= $vigencia['id'] ?>">
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