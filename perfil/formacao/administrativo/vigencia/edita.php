<?php
$con = bancoMysqli();
$idUser = $_SESSION['usuario_id_s'];

if (isset($_POST['cadastra'])) {
    $ano = $_POST['ano'];
    $descricao = addslashes($_POST['descricao']);
    $num = $_POST['num_parcela'];

    $sql = "INSERT INTO formacao_vigencias (ano, descricao, numero_parcelas) VALUES ('$ano', '$descricao', '$num')";

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
    $num = $_POST['num_parcela'];

    $sql = "DELETE FROM formacao_parcelas WHERE formacao_vigencia_id = '$idVigencia'";
    mysqli_query($con, $sql);

    $sql = "UPDATE formacao_vigencias SET ano = '$ano', descricao = '$descricao', numero_parcelas = '$num' WHERE id = '$idVigencia'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Vigência atualizada com sucesso");
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao atualizar a vigência. Tente novamente!");
    }

}

if (isset($_POST['carregar']))
    $idVigencia = $_POST['idVigencia'];

$vigencia = recuperaDados('formacao_vigencias', 'id', $idVigencia);

if (isset($_POST['edita'])) {
    $parcelas = $_POST['parcela'];
    $valores = $_POST['valor'];
    $data_inicios = $_POST['data_inicio'];
    $data_fins = $_POST['data_fim'];
    $data_pagamentos = $_POST['data_pagamento'];
    $cargas = $_POST['carga'];

    $i = $vigencia['numero_parcelas'];

    for ($count = 0; $count < $i; $count++) {
        $parcela = $parcelas[$count] ?? NULL;
        $valor = $valores[$count] ?? NULL;
        $data_inicio = $data_inicios[$count] ?? NULL;
        $data_fim = $data_fins[$count] ?? NULL;
        $data_pagamento = $data_pagamentos[$count] ?? NULL;
        $carga = $cargas[$count] ?? NULL;

        $sql = "INSERT INTO formacao_parcelas (formacao_vigencia_id, numero_parcelas, valor, data_inicio, data_fim, data_pagamento, carga_horaria)
                                       VALUES ('$idVigencia', '$parcela', '$valor', '$data_inicio', '$data_fim', '$data_pagamento', '$carga')";

        mysqli_query($con, $sql);
    }
}
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
                                    <input type="number" min="2018" id="ano" name="ano" required class="form-control"
                                           value="<?= $vigencia['ano'] ?>">
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="num_parcela">Qtd. Parcelas *</label>
                                    <input type="number" min="1" id="num_parcela" name="num_parcela" required
                                           class="form-control"
                                           value="<?= $vigencia['numero_parcelas'] ?>">
                                </div>

                                <div class="form-group col-md-8">
                                    <label for="descricao">Descrição *</label>
                                    <input type="text" id="descricao" name="descricao" class="form-control" required
                                           maxlength="45"
                                           value="<?= $vigencia['descricao'] ?>">
                                </div>
                            </div>

                            <?php
                            for ($i = 1; $i < $vigencia['numero_parcelas'] + 1; $i++) {
                                $testaParcela = $con->query("SELECT * FROM formacao_parcelas WHERE formacao_vigencia_id = '$idVigencia' AND numero_parcelas = '$i'");
                                $parcelaArray = mysqli_fetch_array($testaParcela);
                                ?>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <label for="parcela[]">Parcela:</label>
                                        <input type="number" readonly class="form-control" value="<?= $i ?>"
                                               name="parcela[]" id="parcela[]" required>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="valor[]">Valor:</label>
                                        <input type="text" id="valor<?= $i ?>" name="valor[]"
                                               class="form-control valor" value="<?= dinheiroParaBr($parcelaArray['valor'] ?? NULL) ?>">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="data_inicio">Data inicial:</label>
                                        <input type="date" name="data_inicio[]" class="form-control" id="data_inicio<?= $i ?>"
                                               placeholder="DD/MM/AAAA" value="<?= $parcelaArray['data_inicio'] ?? NULL ?>">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="data_fim">Data final: </label>
                                        <input type="date" name="data_fim[]" class="form-control" id="data_fim<?= $i ?>"
                                               placeholder="DD/MM/AAAA" value="<?= $parcelaArray['data_fim'] ?? NULL ?>">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="data_pagamento">Data pagamento: </label>
                                        <input type="date" name="data_pagamento[]" class="form-control"
                                               id="data_pagamento<?= $i ?>" placeholder="DD/MM/AAAA" value="<?= $parcelaArray['data_pagamento'] ?? NULL ?>">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="carga[]">Carga horária: </label>
                                        <input type="number" name="carga[]" class="form-control" id="carga<?= $i ?>"
                                               value="<?= $parcelaArray['carga_horaria'] ?? NULL ?>" min="1">
                                    </div>
                                </div>

                            <?php } ?>
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

<script>
    $(document).ready(function () {
        $('.valor').mask('000,00', {reverse: true});
    });
</script>