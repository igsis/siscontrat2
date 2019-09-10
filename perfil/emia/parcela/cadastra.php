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
            <h2>EMIA - Parcelas</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Cadastro de Parcela</h3>
            </div>
            <form method="post" action="?perfil=emia&p=parcela&sp=edita" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="numParcelas">Numero de Parcelas: *</label>
                            <input type="number" min="1" class="form-control" required name="numParcelas" id="numParcelas">
                        </div>

                        <div class="col-md-3">
                            <label for="valor">Valor: *</label>
                            <input type="tel" class="form-control" required name="valor" id="valor" onkeypress="return(moeda(this, '.', ',', event))">
                        </div>

                        <div class="col-md-3">
                            <label for="data_inicio">Data de Início: *</label>
                            <input type="date" class="form-control" required name="data_inicio" style="max-width: 175px;" onblur="validate()" id="datepicker10">
                        </div>

                        <div class="col-md-3">
                            <label for="data_fim">Data de Encerramento: *</label>
                            <input type="date" class="form-control" required name="data_fim" style="max-width: 175px;" onblur="validate()" id="datepicker11">
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
                            <input type="date" class="form-control" required name="data_pgt" id="data_pgt">
                        </div>

                        <div class="col-md-4">
                            <label for="mes_ref">Mês de Referência: *</label>
                            <input type="text" class="form-control" required name="mes_ref" id="mes_ref">
                        </div>

                        <div class="col-md-4">
                            <label for="carga_horaria">Carga Horaria: *</label>
                            <input type="number" class="form-control" min="0" required name="carga_horaria" id="carga_horaria">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="?perfil=emia&p=vigencia&sp=listagem">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <button name="cadastra" id="cadastra" type="submit" class="btn btn-primary pull-right">Cadastrar</button>
            </form>
        </div>
    </section>
</div>
