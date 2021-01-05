<?php
$con = bancoMysqli();
$idPedido = $_POST['idPedido'];

?>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Notas de empenho</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Notas de empenho</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=pagamento&sp=empenho_edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="protocolo">Número da nota de empenho: *</label>
                                    <input type="text" name="numEmpenho" id="numEmpenho" class="form-control" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="">Data de emissão da nota de empenho: *</label>
                                    <input type="date" name="data_emissao" id="data_emissao" required
                                           class="form-control" placeholder="DD/MM/AAAA">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="">Data de entrega da nota de empenho: *</label>
                                    <input type="date" name="data_entrega" id="data_entrega" required
                                           class="form-control" placeholder="DD/MM/AAAA">
                                </div>
                            </div>
                            <div class="row" id="msg">
                                <div class="form-group col-md-12">
                                    <span class="pull-right" style="color: red;"><b>Data de entrega precisa ser maior ou igual a de emissão!</b></span>
                                </div>
                            </div>

                        </div>
                        <div class="box-footer">
                            <input type="hidden" id="idPedido" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" name="cadastra" id="cadastra" class="btn btn-primary pull-right">
                                Cadastrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
            
        if (dataFim < dataInicio) {
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