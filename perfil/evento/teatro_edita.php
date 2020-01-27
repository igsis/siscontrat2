<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $idAtracao = $_POST['idAtracao'] ?? NULL;
    $idTeatro = $_POST['idTeatro'] ?? NULL;
    $estreia = $_POST ['estreia'];
    $genero = trim($_POST['genero']);
    $venda = $_POST['venda'];
    $descricao = trim(addslashes($_POST['descricao']));
}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO teatro (atracao_id, 
                                  estreia,
                                  genero,
                                  venda,
                                  descricao) 
                          VALUES ('$idAtracao',
                                  '$estreia',
                                  '$genero',
                                  '$venda',
                                  '$descricao')";


    if (mysqli_query($con, $sql)) {

        $idTeatro = recuperaUltimo("teatro");

        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if (isset($_POST['edita'])) {
    $sql = "UPDATE teatro SET
                            genero = '$genero',
                            estreia = '$estreia',
                            venda = '$venda',
                            descricao = '$descricao'
                            WHERE id = '$idTeatro'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Gravado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if (isset($_POST['carregar'])) {
    $idTeatro = $_POST['idTeatro'];
}

$teatro = recuperaDados("teatro", "id", $idTeatro);

include "includes/menu_interno.php";
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Edição de Especificidade</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atração - Especificidades de Área</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <form method="POST" action="?perfil=evento&p=teatro_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-2 text-center">
                                    <label for="genero">Gênero *</label><br/>
                                    <input class='form-control' type="text" id="genero" name="genero" maxlength="30"
                                           pattern="[a-zA-ZàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇáéíóúýÁÉÍÓÚÝ ]{1,30}"
                                           title="Apenas letras" value="<?= $teatro['genero'] ?>" required>
                                </div>
                                <div class="form-group col-md-2 text-center">
                                    <label for="estreia">Estréia?</label> <br>
                                    <label><input type="radio" name="estreia"
                                                  value="0" <?= $teatro['estreia'] == 0 ? 'checked' : NULL ?> > Não
                                    </label>
                                    <label><input type="radio" name="estreia"
                                                  value="1" <?= $teatro['estreia'] == 1 ? 'checked' : NULL ?> > Sim
                                    </label>
                                </div>
                                <div class="form-group col-md-2 text-center">
                                    <label for="venda">Venda de material?</label> <br>
                                    <label><input type="radio" name="venda"
                                                  value="0" <?= $teatro['venda'] == 0 ? 'checked' : NULL ?> class="venda" id="nao"> Não
                                    </label>
                                    <label><input type="radio" name="venda"
                                                  value="1" <?= $teatro['venda'] == 1 ? 'checked' : NULL ?> class="venda" id="sim"> Sim
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="descricao">Descrição</label><br/>
                                    <input type="text" class="form-control" name="descricao" id="descricao"
                                           maxlength="255" value="<?= $teatro['descricao'] ?>" readonly>
                                </div>
                            </div>
                            <div class="box-footer">
                                <a href="?perfil=evento&p=atracoes_lista">
                                    <button type="button" class="btn btn-default">Voltar</button>
                                </a>
                                <input type="hidden" name="idTeatro" value="<?= $idTeatro ?>">
                                <button type="submit" name="edita" class="btn btn-info pull-right">Salvar</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    var venda = $('.venda');
    venda.on("change", verificaVenda);
    $(document).ready(verificaVenda());

    function verificaVenda () {
        if ($('#sim').is(':checked')) {
            $('#descricao')
                .attr('readonly', false)
        } else {
            $('#descricao')
                .attr('readonly', true)
                .val('');
        }
    }
</script>