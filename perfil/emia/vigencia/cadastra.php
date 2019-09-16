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
            <h2 class="page-title">EMIA - Cadastro de Vigência</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Vigência</h3>
            </div>

            <form method="post" action="?perfil=emia&p=vigencia&sp=edita" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2  form-group">
                            <label for="ano">Ano: *</label>
                            <input class="form-control" type="number" min="2018" required name="ano" id="ano">
                        </div>


                        <div class="col-md-2 form-group">
                            <label for="numParcelas">Numero de Parcelas: *</label>
                            <input type="number" min="1" class="form-control" required name="numParcelas"
                                   id="numParcelas">
                        </div>


                        <div class="col-md-8  form-group">
                            <label for="descricao">Descrição: *</label>
                            <input class="form-control" type="text" required name="desc" id="desc">
                        </div>

                    </div>
                </div>


                <div class="box-footer">
                    <a href="?perfil=emia&p=vigencia&sp=listagem">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <button name="cadastra" id="cadastra" type="submit" class="btn btn-primary pull-right">Cadastrar
                    </button>
            </form>
        </div>
</div>
</section>
</div>
