<?php
$con = bancoMysqli();

$idPedido = $_POST['idPedido'];

$pedido = $con->query("SELECT e.protocolo, p.numero_processo FROM pedidos AS p INNER JOIN eventos AS e ON p.origem_id = e.id WHERE p.id = $idPedido")->fetch_array();
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
                <form action="?perfil=pagamento&p=empenho_edita" method="post" role="form">
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
                            <input type="text" class="form-control" name="numEmpenho" id="numEmpenho" required>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="data_inicio">Data de Emissão da Nota de Empenho: *</label>
                            <input type="date" class="form-control" name="data_emissao" placeholder="DD/MM/AAAA"
                                   id="data_emissao" required>
                        </div>

                        <div class="col-md-6">
                            <label for="data_fim"> Data de Entrega da Nota de Empenho: *</label>
                            <input type="date" class="form-control" name="data_entrega" placeholder="DD/MM/AAAA"
                                   id="data_entrega" required>
                        </div>
                    </div>
                    <div class="row" id="msg">
                                <div class="form-group col-md-12">
                                    <span class="pull-right" style="color: red;"><b>Data de emissão precisa ser maior que a de entrega!</b></span>
                                </div>
                            </div>

            </div>

            <div class="box-footer">
                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                <button type="submit" class="btn btn-primary pull-right" name="cadastra">Cadastrar</button>

                <a href="?perfil=pagamento">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
            </div>
        </div>
        </form>
    </section>
</div>
<script>
$('#msg').hide();
function comparaData() {
        var isMsg = $('#msg');
        var dataInicio = document.querySelector('#data_emissao').value;
        var dataFim = document.querySelector('#data_entrega').value;

        if (dataInicio != "" && dataFim != "") {
            var dataInicio = parseInt(dataInicio.split("-")[0].toString() + dataInicio.split("-")[1].toString() + dataInicio.split("-")[2].toString());
            var dataFim = parseInt(dataFim.split("-")[0].toString() + dataFim.split("-")[1].toString() + dataFim.split("-")[2].toString());
            isMsg.hide();
        }

        $('#cadastra').attr("disabled", true);
            
        if (dataInicio <= dataFim) {
            $('#cadastra').attr("disabled", true);
            isMsg.show();
        } else {
            $('#cadastra').attr("disabled", false);
            isMsg.hide();
        }
}
   $('#data_emissao').on('change', comparaData);
   $('#data_entrega').on('change', comparaData);
</script>