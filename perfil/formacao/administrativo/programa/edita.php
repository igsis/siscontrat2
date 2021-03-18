<?php
$con = bancoMysqli();
$idUser = $_SESSION['usuario_id_s'];

if (isset($_POST['cadastra'])) {
    $programa = addslashes($_POST['programa']);
    $edital = addslashes($_POST['edital']);
    $idVerba = $_POST['verba'];
    $descricao = addslashes($_POST['descricao']);

    $sql = "INSERT INTO programas (programa, verba_id, edital, descricao) VALUES ('$programa', '$idVerba', '$edital', '$descricao')";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Programa cadastrado com sucesso");
        $idPrograma = recuperaUltimo('programas');
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao cadastrar o programa. Tente novamente!");
    }
}

if (isset($_POST['edita'])) {
    $programa = addslashes($_POST['programa']);
    $edital = addslashes($_POST['edital']);
    $idVerba = $_POST['verba'];
    $descricao = addslashes($_POST['descricao']);
    $idPrograma = $_POST['idPrograma'];

    $sql = "UPDATE programas SET programa = '$programa', edital = '$edital', verba_id = '$idVerba', descricao = '$descricao' WHERE id = '$idPrograma'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Programa atualizado com sucesso");
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao atualizar o programa. Tente novamente!");
    }

}

if (isset($_POST['carregar'])) {
    $idPrograma = $_POST['idPrograma'];
}

$programa = recuperaDados('programas', 'id', $idPrograma);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Atualizar Programa</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Programa</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=administrativo&sp=programa&spp=edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="programa">Programa *</label>
                                    <input type="text" id="programa" name="programa" required class="form-control" value="<?= $programa['programa'] ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="edital">Edital *</label>
                                    <input type="text" id="edital" name="edital" required class="form-control" value="<?= $programa['edital'] ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="verba">Verba *</label>
                                    <select name="verba" id="verba" required class="form-control">
                                        <?php
                                        geraOpcao('verbas', $programa['verba_id']);
                                        ?>

                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="descricao">Descrição *</label>
                                    <textarea name="descricao" id="descricao" rows="3" required class="form-control"><?= $programa['descricao'] ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=formacao&p=administrativo&sp=programa&spp=index">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" id="idPrograma" name="idPrograma"
                                   value="<?= $idPrograma ?>">
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