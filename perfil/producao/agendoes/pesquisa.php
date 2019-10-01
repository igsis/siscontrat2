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
        <h3 class="page-header">Produção - Pesquisa</h3>
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Exportar agendão para Excel</h3>
            </div>
            <div class="box-body">
                <form role="form" action="?perfil=producao&p=agendoes&sp=exporta" method="POST">
                    <div class="row">

                        <div class="col-md-12">
                            <label for="usuario">Inserido pelo usuario: </label>
                            <input type="text" required class="form-control" name="usuario" id="usuario">
                        </div>


                    </div>
                    <br>
                    <div class="row">

                        <div class="col-md-6">
                            <label for="projeto">Projeto Especial: </label>
                            <select class="form-control" required name="projeto" id="projeto">
                                <option value="">Selecione um projeto...</option>
                                <?php
                                geraOpcaoPublicado("projeto_especiais", "");
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="local">Local: </label>
                            <select class="form-control" required name="local" id="local">
                                <option value="">Selecione um local...</option>
                                <?php
                                geraOpcao("locais", "");
                                ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">

                        <div class="col-md-6">
                            <label for="data_inicio">Data de Início: </label>
                            <input type="date" required class="form-control" name="data_inicio" onblur="validate()" id="datepicker10">
                        </div>

                        <div class="col-md-6">
                            <label for="data_fim">Data de Encerramento: </label>
                            <input type="date" class="form-control" name="data_fim" onblur="validate()" id="datepicker11">
                        </div>
                    </div>

                    <div class="row" id="msgEscondeData">
                        <div class="form-group col-md-6">
                            <span style="color: red;"><b>Data de encerramento deve ser maior que a data inicial</b></span>
                        </div>
                    </div>

                </form>
            </div>
            <div class="box-footer">
                <button name="pesquisa" id="pesquisa" type="submit" class="btn btn-primary pull-right">Pesquisar</button>
                <a href="?perfil=producao">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
            </div>
        </div>
    </section>
</div>

