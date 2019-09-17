<?php
$con = bancoMysqli();

if (isset($_POST['cadastra'])) {
    $ano = $_POST['ano'];
    $desc = $_POST['desc'];
    $numParcela = $_POST['numParcelas'];

    $sqlInsert = "INSERT INTO emia_vigencias
                            (ano, descricao,numero_parcelas)
                            VALUES
                            ('$ano', '$desc', '$numParcela')";
    if (mysqli_query($con, $sqlInsert)) {
        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        $idEV = recuperaUltimo('emia_vigencias');

    } else {
        $mensagem = mensagem("danger", "Erro ao cadastrar! Tente novamente.");
    }
    $ev = recuperaDados('emia_vigencias', 'id', $idEV);
}

if (isset($_POST['editar'])) {
    $idEV = $_POST['idEV'];
    $ano = $_POST['ano'];
    $desc = $_POST['desc'];
    $numParcela = $_POST['numParcelas'];

    $sql = "DELETE FROM emia_parcelas WHERE emia_vigencia_id = '$idEV'";
    mysqli_query($con, $sql);

    $sqlUpdate = "UPDATE emia_vigencias SET
                    ano = '$ano',
                    descricao = '$desc'
                    WHERE id = '$idEV'";

    if (mysqli_query($con, $sqlUpdate)) {
        $mensagem = mensagem("success", "Gravado com sucesso!");
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
    }
    $ev = recuperaDados('emia_vigencias', 'id', $idEV);
}

if (isset($_POST['edit'])) {
    $idEV = $_POST['idEVEdit'];
    $ev = recuperaDados('emia_vigencias', 'id', $idEV);
}

if(isset($_POST['editar'])){
    $valor = dinheiroDeBr($_POST['valor']);
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $data_pgt = $_POST['data_pgt'];
    $mes = $_POST['mes_ref'];
    $carga = $_POST['carga_horaria'];

    $i = $parcelas['numero_parcelas'];

    for ($count = 0; $count < $i; $count++) {
        $parcela = $parcelas[$count] ?? NULL;
        $valor = $valores[$count] ?? NULL;
        $data_inicio = $data_inicios[$count] ?? NULL;
        $data_fim = $data_fins[$count] ?? NULL;
        $data_pagamento = $data_pagamentos[$count] ?? NULL;
        $carga = $cargas[$count] ?? NULL;

        $sql = "INSERT INTO emia_parcelas (emia_vigencia_id, numero_parcelas, valor, data_inicio, data_fim, data_pagamento, carga_horaria)
                                       VALUES ('$idEV', '$parcela', '$valor', '$data_inicio', '$data_fim', '$data_pagamento', '$carga')";


        mysqli_query($con, $sql);
    }
}


/*



        mysqli_query($con,$sqlParcela);
        $idParcela = recuperaUltimo('emia_parcelas');


 $numParcela = $_POST['numParcelas'];
        $valor = $_POST['valor'];
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];
        $data_pgt = $_POST['data_pgt'];
        $mes = $_POST['mes_ref'];
        $carga = $_POST['carga_horaria'];

        $sqlUpdateParcela = "UPDATE emia_parcelas SET
                                numero_parcelas = '$numParcela',
                                valor = '$valor',
                                data_inicio = '$data_inicio',
                                data_fim = '$data_fim',
                                data_pagamento = '$data_pgt',
                                mes_referencia = '$mes',
                                carga_horaria = '$carga'
                                WHERE id = '$idEV'";
        mysqli_query($con, $sqlUpdateParcela);



 * */




?>

<script type="text/javascript">
    $(document).ready(function () {
        validate();
        $('#datepicker11').change(validate);
    });

    function validate() {
        comparaData();
        if ($('#datepicker11').val().length > 0) {
        }

    }

    function comparaData() {
        let botao = $('#cadastra');
        var isMsgData = $('#msgEscondeData');
        isMsgData.hide();
        var dataInicio = document.querySelector('#datepicker10').value;
        var dataFim = document.querySelector('#datepicker11').value;

        if (dataInicio != "") {
            var dataInicio = parseInt(dataInicio.split("-")[0].toString() + dataInicio.split("-")[1].toString() + dataInicio.split("-")[2].toString());
        }

        if (dataFim != "") {
            var dataFim = parseInt(dataFim.split("-")[0].toString() + dataFim.split("-")[1].toString() + dataFim.split("-")[2].toString());

            if (dataFim <= dataInicio) {
                botao.prop('disabled', true);
                isMsgData.show();
                $('#cadastra').attr("disabled", true);
            } else {
                botao.prop('disabled', false);
                isMsgData.hide();
                $('#cadastra').attr("disabled", false);
            }
        }

    }
</script>

<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2>Cadastro de Vigência</h2>
        </div>
        <div class="box box-primary">
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">Vigência</h3>
            </div>
            <form method="post" action="?perfil=emia&p=vigencia&sp=edita" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="ano">Ano: *</label>
                            <input class="form-control" type="number" min="2018" required name="ano" id="ano"
                                   value="<?= $ev['ano'] ?>">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="num_parcela">Numero de Parcelas: *</label>
                            <input type="number" min="1" id="num_parcela" name="num_parcela" required
                                   class="form-control"
                                   value="<?= $ev['numero_parcelas'] ?>">
                        </div>

                        <div class="col-md-8">
                            <label for="descricao">Descrição: *</label>
                            <input class="form-control" type="text" required name="desc" id="desc"
                                   value="<?= $ev['descricao'] ?>">
                        </div>
                    </div>

                    <hr />

                    <?php
                    for ($i = 1; $i < $ev['numero_parcelas'] + 1; $i++) {
                        $sql = "SELECT * FROM emia_parcelas WHERE emia_vigencia_id = '$idEV' AND numero_parcelas = '$i'";
                        $parcelas = mysqli_fetch_array(mysqli_query($con, $sql));
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
                                <input type="text" id="valor[]" name="valor[]"
                                       class="form-control" onKeyPress="return(moeda(this,'.',',',event))" value="<?= dinheiroParaBr($parcelas['valor']) ?>">
                            </div>

                            <div class="form-group col-md-2">
                                <label for="data_inicio">Data inicial:</label>
                                <input type="date" name="data_inicio[]" class="form-control" style="max-width: 175px;" onblur="validate()" id="datepicker10"
                                       placeholder="DD/MM/AAAA" value="<?= $parcelas['data_inicio'] ?? NULL ?>">
                            </div>

                            <div class="form-group col-md-2">
                                <label for="data_fim">Data final: </label>
                                <input type="date" name="data_fim[]" class="form-control" style="max-width: 175px;" onblur="validate()" id="datepicker11"
                                       placeholder="DD/MM/AAAA" value="<?= $parcelas['data_fim'] ?? NULL ?>">
                            </div>

                            <div class="row" id="msgEscondeData">
                                <div class="form-group col-md-6 pull-right">
                                    <span style="color: red;"><b>Data de encerramento menor que a data inicial!</b></span>
                                </div>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="data_pagamento">Data pagamento: </label>
                                <input type="date" name="data_pagamento[]" class="form-control"
                                       id="datepicker12" placeholder="DD/MM/AAAA" value="<?= $parcelas['data_pagamento'] ?? NULL ?>">
                            </div>

                            <div class="form-group col-md-2">
                                <label for="carga[]">Carga horária: </label>
                                <input type="time" name="carga[]" class="form-control" id="carga[]"
                                       value="<?= $parcelas['carga_horaria'] ?? NULL ?>"  placeholder="hh:mm">
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                </div>
                <div class="box-footer">
                    <a href="?perfil=emia&p=vigencia&sp=listagem">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <input type="hidden" name="idEV" value="<?= $idEV ?>" id="idEV">
                    <button name="editar" id="editar" type="submit" class="btn btn-primary pull-right">Salvar</button>

            </form>
                </div>
        </div>
    </section>
</div>

