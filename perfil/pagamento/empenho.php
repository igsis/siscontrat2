<?php
$con = bancoMysqli();

//$idPedido = $_POST['idPedido'];

//$pedido = $con->query("SELECT protocolo, numero_processo FROM pedidos WHERE id = $idPedido")->fetch_array();
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
                            <input type="text" class="form-control" name="numEmpenho" id="numEmpenho">
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="data_inicio">Data de Emissão da Nota de Empenho: *</label>
                            <input type="date" class="form-control" name="data_emissao" placeholder="DD/MM/AAAA"
                                   id="data_emissao">
                        </div>

                        <div class="col-md-6">
                            <label for="data_fim"> Data de Entrega da Nota de Empenho: *</label>
                            <input type="date" class="form-control" name="data_entrega" placeholder="DD/MM/AAAA"
                                   id="data_entrega">
                        </div>
                    </div>

            </div>

            <div class="box-footer">
                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                <button type="submit" class="btn btn-primary pull-right" name="cadastra">Cadastrar</button>

                <a href="#">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
            </div>
        </div>
        </form>
    </section>
</div>
