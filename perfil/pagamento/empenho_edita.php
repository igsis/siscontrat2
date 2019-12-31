<?php

$con = bancoMysqli();
$idPedido = $_POST['idPedido'];

if (isset($_POST['cadastra']) || $_POST['edita']) {
    $empenho = trim(addslashes($_POST['numEmpenho']));
    $dataEntrega = $_POST['data_entrega'];
    $dataEmissao = $_POST['data_emissao'];
    $idUser = $_SESSION['idUser'];
}

if (isset($_POST['cadastra'])) {
    $sqlCadastra = "INSERT INTO pagamentos (pedido_id, 
                                    nota_empenho, 
                                    emissao_nota_empenho, 
                                    entrega_nota_empenho, 
                                    usuario_pagamento_id) 
                                    VALUES($idPedido,
                                           $empenho,
                                           $dataEmissao,
                                           $dataEntrega)";
    if (mysqli_query($con, $sqlCadastra)) {
        $mensagem = mensagem("success", "Cadastado com sucesso!");
    } else {
        $mensagem = mensagem("danger", "Erro ao cadastar.");
    }
}

if(isset($_POST['edita'])){
    $sqlEdita = "UPDATE pagamentos SET nota_empenho = $empenho, emissao_nota_empenho = $dataEmissao, entrega_nota_empenho = $dataEntrega WHERE pedido_id = $idPedido";
    if(mysqli_query($con,$sqlEdita)){
        $mensagem = mensagem("success", "Editado com sucesso!");
    }else{
        $mensagem = mensagem("danger", "Erro ao editar.");
    }
}

$empenho = recuperaDados('pagamentos', 'pedido_id', $idPedido);
$pedido = $con->query("SELECT protocolo, numero_processo FROM pedidos WHERE id = $idPedido")->fetch_array();
?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3>Pagamento - Nota de Empenho</h3>
        </div>
        <div class="box">
            <div class="box-header">
                <h2 class="box-title">Cadastro de Nota de Empenho</h2>
            </div>
            <div class="box-body">
                <form action="#" method="post" role="form">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="protocolo">Protocolo: </label>
                            <input type="text" class="form-control" name="protocolo" id="protocolo"
                                   value="<?= $pedido['protocolo'] ?>" disabled>
                        </div>

                        <div class="col-md-6">
                            <label for="numero_processo">Número do Processo: </label>
                            <input type="text" class="form-control" name="numero_processo" id="numero_processo" disabled
                                   value="<?= $pedido['numero_processo'] ?>">
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="numEmpenho">Numero da Nota de Empenho: *</label>
                            <input type="text" class="form-control" name="numEmpenho" id="numEmpenho" value="<?=$empenho['nota_empenho']?>">
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="data_inicio">Data de Emissão da Nota de Empenho: *</label>
                            <input type="date" class="form-control" name="data_inicio" placeholder="DD/MM/AAAA" value="<?=$empenho['emissao_nota_empenho']?>"
                                   id="data_fim">
                        </div>

                        <div class="col-md-6">
                            <label for="data_fim"> Data de Entrega da Nota de Empenho: *</label>
                            <input type="date" class="form-control" name="data_fim" placeholder="DD/MM/AAAA" value="<?=$empenho['entrega_nota_empenho']?>"
                                   id="data_fim">
                        </div>
                    </div>

            </div>

            <div class="box-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary pull-right" name="edita">Salvar</button>
                        <a href="#">
                            <button type="button" class="btn btn-default">Voltar</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </section>
</div>

