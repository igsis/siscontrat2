<?php
$con = bancoMysqli();
$idFormacao = $_POST['idFormacao'];
$formacao = recuperaDados('formacao_contratacoes', 'id', $idFormacao);
$pedido = recuperaDados('pedidos', 'id', $formacao['pedido_id']);

if (isset($_POST['cadastra'])) {
    $idPedido = $pedido['id'];
    $numEmpenho = $_POST['numEmpenho'];
    $data_entrega = $_POST['data_entrega'];
    $data_emissao = $_POST['data_emissao'];
    $idUser = $_SESSION['idUser'];

    $sql = "INSERT INTO pagamentos (pedido_id, nota_empenho, emissao_nota_empenho, entrega_nota_empenho, usuario_pagamento_id) 
                                VALUES ('$idPedido', '$numEmpenho', '$data_emissao', '$data_entrega', '$idUser')";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", 'Pagamento cadastrado com sucesso!');
    }else{
        $mensagem = mensagem("danger", 'Ocorreu um erro ao cadastrar o pagamento! Tente novamente.');
    }
}

$pagamentos = recuperaDados('pagamentos', 'pedido_id', $pedido['id']);
?>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Notas de empenho</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Notas de empenho</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=pagamento&sp=resultado"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="protocolo">Número da nota de empenho</label>
                                    <input type="text" name="numEmpenho" id="numEmpenho" class="form-control" required
                                           value="<?= $pagamentos['nota_empenho'] ?>" disabled readonly>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="">Data de emissão da nota de empenho</label>
                                    <input type="date" name="data_emissao" id="datepicker10" required
                                           class="form-control" placeholder="DD/MM/AAAA"
                                           value="<?= $pagamentos['emissao_nota_empenho'] ?>" disabled readonly>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="">Data de entrega da nota de empenho</label>
                                    <input type="date" name="data_entrega" id="datepicker11" required
                                           class="form-control" placeholder="DD/MM/AAAA"
                                           value="<?= $pagamentos['entrega_nota_empenho'] ?>" disabled readonly>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=formacao&p=pagamento&sp=index">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
