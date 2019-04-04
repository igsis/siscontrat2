<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || (isset($_POST['edita']))) {
    $titulo = addslashes($_POST['titulo']);

    if (isset($_POST['cadastra'])) {
        $sql = "INSERT INTO relacao_juridicas (relacao_juridica)
                VALUES ('$titulo')";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Relação Jurídica cadastrada com sucesso!");
            $idRelacaoJuridica = recuperaUltimo('relacao_juridicas');
        } else {
            $mensagem = mensagem("danger", "Erro no cadastro de Relação Jurídica! Tente Novamente.");
        }
    }
    if (isset($_POST['edita'])) {
        $idRelacaoJuridica = $_POST['idRelacaoJuridica'];

        $sql = "UPDATE relacao_juridicas SET relacao_juridica = '$titulo' WHERE id= '$idRelacaoJuridica'";
        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Relação Jurídica editada com sucesso!");
        } else {
            $mensagem = mensagem("danger", "Erro ao salvar relação jurídica! Tente novamente.");
        }
    }
}
        if(isset($_POST['carregar'])){
            $idRelacaoJuridica = $_POST['idRelacaoJuridica'];
        }

        $relacaoJuridica = recuperaDados('relacao_juridicas','id', $idRelacaoJuridica);
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Edição de Relação Jurídica</h2>

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Relação Jurídica</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;}?>
                    </div>

                    <form method="POST" action="?perfil=administrativo&p=relacao_juridica&sp=edita_relacao_juridica" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="titulo">Título* </label>
                                    <input type="text" id="titulo" name="titulo" class="form-control" required value="<?= $relacaoJuridica['relacao_juridica']?>">
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <a href="?perfil=administrativo&p=relacao_juridica&sp=relacao_juridica_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" name="idRelacaoJuridica" id="idRelacaoJuridica" required value="<?= $idRelacaoJuridica ?>" >
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

