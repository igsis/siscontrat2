<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || (isset($_POST['edita']))) {
    $titulo = addslashes($_POST['titulo']);

    if (isset($_POST['cadastra'])) {
        $sql = "INSERT INTO verbas (verba)
        VALUES('$titulo')";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Verba cadastrada com sucesso!");
            $idVerbas = recuperaUltimo('verbas');
        } else {
            $mensagem = mensagem("danger", "Erro no cadastro de Verba! Tente novamente.");
        }
    }
    if(isset($_POST['edita'])){
        $idVerbas = $_POST['idVerbas'];

        $sql = "UPDATE verbas SET verba = '$titulo' WHERE id = '$idVerbas'";


        if(mysqli_query($con, $sql)){
            gravarLog($sql);
            $mensagem = mensagem("success", "Verba editada com sucesso!");
        }else{
            $mensagem = mensagem("danger", "Erro ao salvar a verba! Tente novamente.");
        }
    }
}
if (isset($_POST['carregar'])) {
    $idVerbas = $_POST['idVerbas'];
}
$Verbas = recuperaDados('verbas', 'id',$idVerbas);
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Edição de Verba</h2>

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Verba</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        } ?>
                    </div>

                    <form method="POST" action="?perfil=administrativo&p=verbas&sp=edita_verbas" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="titulo">Título *</label>
                                    <input type="text" id="titulo" name="titulo" class="form-control" required
                                           value="<?=$Verbas['verba']?>">
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <a href="?perfil=administrativo&p=verbas&sp=verbas_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" name="idVerbas" id="idVerbas" required value="<?= $idVerbas ?>" >
                            <button type="submit" name="edita" id="edita" class="btn btn-primary pull-right">
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
