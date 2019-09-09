<?php
$con = bancoMysqli();

$idEV = $_SESSION['idEV'];
$numParcela = $_POST['numParcela'];
$valor = $_POST['valor'];
$data_inicio = $_POST['data_inicio'];
$data_fim = $_POST['data_fim'];
$data_pgt = $_POST['data_pgt'];
$mes = $_POST['mes_ref'];
$carga = $_POST['carga_horaria'];

if (isset($_POST['cadastra'])) {
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
    if (mysqli_query($con, $sqlParcela)) {
        $mensagem = mensagem("success", "Cadastrado com Sucesso!");
        $idParcela = recuperaUltimo("emia_parcelas");
    } else {
        $mensagem = mensagem("danger", "Erro ao cadastrar! Tente novamente.");
    }
}

if (isset($_POST['edita'])) {
    $numParcela = $_POST['numParcela'];
    $valor = $_POST['valor'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $data_pgt = $_POST['data_pgt'];
    $mes = $_POST['mes_ref'];
    $carga = $_POST['carga_horaria'];

    $idVigencia = $_POST['idVigencia'];
    $sqlCorrige = "DELETE FROM emia_parcelas WHERE emia_vigencia_id = '$idVigencia'";
    mysqli_query($con, $sqlCorrige);

    $sqlUpdateParcela = "UPDATE emia_parcelas SET
                                numero_parcelas = '$numParcela',
                                valor = '$valor',
                                data_inicio = '$data_inicio',
                                data_fim = '$data_fim',
                                data_pagamento = '$data_pgt',
                                mes_referencia = '$mes',
                                carga_horaria = '$carga'
                                WHERE id = '$idParcela'";
    if (mysqli_query($con, $sqlUpdateParcela)) {
        $mensagem = mensagem("success", "Salvo com Sucesso!");
    } else {
        $mensagem = mensagem("danger", "Erro ao salvar! Tente novamente.");
    }
}

?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2>EMIA - Parcelas</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Cadastro de Parcela</h3>
            </div>
            <form method="post" action="?perfil=emia&p=vigencia&sp=listagem" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="numParcelas">Numero de Parcelas: *</label>
                            <input type="number" min="1" class="form-control" required name="numParcelas"
                                   id="numParcelas" value="<?= $parcelas[''] ?>">
                        </div>

                        <div class="col-md-3">
                            <label for="valor">Valor: *</label>
                            <input type="number" class="form-control" required name="valor" id="valor"
                                   value="<?= $parcelas[''] ?>">
                        </div>

                        <div class="col-md-3">
                            <label for="data_inicio">Data de Início: *</label>
                            <input type="date" class="form-control" required name="data_inicio" id=""
                                   value="<?= $parcelas[''] ?>">
                        </div>

                        <div class="col-md-3">
                            <label for="data_fim">Data de Encerramento: *</label>
                            <input type="date" class="form-control" required name="data_fim" id=""
                                   value="<?= $parcelas[''] ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="data_pgt">Data de Pagamento: *</label>
                            <input type="date" class="form-control" required name="data_pgt" id="data_pgt"
                                   value="<?= $parcelas[''] ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="mes_ref">Mês de Referência: *</label>
                            <input type="text" class="form-control" required name="mes_ref" id="mes_ref"
                                   value="<?= $parcelas[''] ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="carga_horaria">Carga Horaria: *</label>
                            <input type="number" class="form-control" required name="carga_horaria" id="carga_horaria"
                                   value="<?= $parcelas[''] ?>">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="?perfil=emia&p=vigencia&sp=listagem">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <button name="cadastra" id="cadastra" type="submit" class="btn btn-primary pull-right">Salvar
                    </button>
            </form>
        </div>
    </section>
</div>