<?php
$con = bancoMysqli();

if (isset($_POST['cadastra'])) {
    $ano = $_POST['ano'];
    $desc = $_POST['desc'];

    $sqlInsert = "INSERT INTO emia_vigencias
                            (ano, descricao)
                            VALUES
                            ('$ano', '$desc')";
    if (mysqli_query($con, $sqlInsert)) {
        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        $idEV = recuperaUltimo('emia_vigencias');

        $numParcela = $_POST['numParcelas'];
        $valor = $_POST['valor'];
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];
        $data_pgt = $_POST['data_pgt'];
        $mes = $_POST['mes_ref'];
        $carga = $_POST['carga_horaria'];

        $sqlParcela = "INSERT INTO emia_parcelas
                                        (emia_vigencia_id, 
                                         numero_parcelas, 
                                         valor, 
                                         data_inicio, 
                                         data_fim, 
                                         data_pagamento, 
                                         mes_referencia, 
                                         carga_horaria)
                                         VALUES
                                         ('$idEV',
                                          '$numParcela',
                                          '$valor',
                                          '$data_inicio',
                                          '$data_fim',
                                          '$data_pgt',
                                          '$mes',
                                          '$carga')";
        mysqli_query($con,$sqlParcela);
        $idParcela = recuperaUltimo('emia_parcelas');
    } else {
        $mensagem = mensagem("danger", "Erro ao cadastrar! Tente novamente.");
    }
    $ev = recuperaDados('emia_vigencias', 'id', $idEV);
    $parcelas = recuperaDados('emia_parcelas', 'id', $idParcela);
}

if (isset($_POST['editar'])) {

    $idEV = $_POST['idEV'];
    $ano = $_POST['ano'];
    $desc = $_POST['desc'];

    $sqlUpdate = "UPDATE emia_vigencias SET
                    ano = '$ano',
                    descricao = '$desc'
                    WHERE id = '$idEV'";

    if (mysqli_query($con, $sqlUpdate)) {
        $mensagem = mensagem("success", "Gravado com sucesso!");
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
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
    }
    $ev = recuperaDados('emia_vigencias', 'id', $idEV);
    $parcelas = recuperaDados('emia_parcelas', 'id', $idEV);
}

if (isset($_POST['edit'])) {
    $idEV = $_POST['idEVEdit'];
    $ev = recuperaDados('emia_vigencias', 'id', $idEV);
    $parcelas = recuperaDados('emia_parcelas', 'id', $idEV);
}

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
                        <div class="col-md-4">
                            <label for="ano">Ano: *</label>
                            <input class="form-control" type="number" min="2018" required name="ano" id="ano"
                                   value="<?= $ev['ano'] ?>">
                        </div>
                        <div class="col-md-8">
                            <label for="descricao">Descrição: *</label>
                            <input class="form-control" type="text" required name="desc" id="desc"
                                   value="<?= $ev['descricao'] ?>">
                        </div>
                    </div>

                    <hr />

                    <h4 class="form-group">Cadastro de parcela</h4>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="numParcelas">Numero de Parcelas: *</label>
                            <input type="number" min="1" class="form-control" required name="numParcelas"
                                   id="numParcelas" value="<?= $parcelas['numero_parcelas'] ?>">
                        </div>


                        <div class="col-md-3">
                            <label for="valor">Valor: *</label>
                            <input type="tel" class="form-control" required name="valor" id="valor" onkeypress="return(moeda(this, '.', ',', event))"
                                   value="<?= $parcelas['valor'] ?>">
                        </div>

                        <div class="col-md-3">
                            <label for="data_inicio">Data de Início: *</label>
                            <input type="date" class="form-control" required name="data_inicio" style="max-width: 175px;" onblur="validate()" id="datepicker10"
                                   value="<?=$parcelas['data_inicio']?>">
                        </div>

                        <div class="col-md-3">
                            <label for="data_fim">Data de Encerramento: *</label>
                            <input type="date" class="form-control" required name="data_fim" style="max-width: 175px;" onblur="validate()" id="datepicker11"
                                   value="<?= $parcelas['data_fim'] ?>">
                        </div>
                    </div>
                    <br>

                    <div class="row" id="msgEscondeData">
                        <div class="form-group col-md-6 pull-right">
                            <span style="color: red;"><b>Data de encerramento menor que a data inicial!</b></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="data_pgt">Data de Pagamento: *</label>
                            <input type="date" class="form-control" required name="data_pgt" id="data_pgt"
                                   value="<?= $parcelas['data_pagamento'] ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="mes_ref">Mês de Referência: *</label>
                            <input type="text" class="form-control" required name="mes_ref" id="mes_ref"
                                   value="<?= $parcelas['mes_referencia'] ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="carga_horaria">Carga Horaria: *</label>
                            <input type="number" class="form-control"  min="0" required name="carga_horaria" id="carga_horaria"
                                   value="<?= $parcelas['carga_horaria'] ?>">
                        </div>
                    </div>
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

