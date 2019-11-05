<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Relatório por período</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form action="?perfil=juridico&p=relatorio_periodo&sp=relatorio_periodo" method="POST" ROLE="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-3">
                                    <label>Data início *</label>
                                    <input type="date" name="inicio" class="form-control datepicker" id="data_inicio"
                                           onchange="btnfiltrar()" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    <label>Data encerramento *</label>
                                    <input type="date" name="final" class="form-control datepicker" id="final"
                                           autocomplete="off">
                                    <br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-offset-4 col-md-4" align="center">
                                    <label for="projetoEspecial">Instituição</label> <br>
                                    <select class="form-control" name="projetoEspecial" id="projetoEspecial">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("instituicoes");
                                        ?>
                                    </select>
                                    <br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-offset-4 col-md-4" align="center">
                                    <label for="projetoEspecial">Sala</label> <br>
                                    <select class="form-control" name="projetoEspecial" id="projetoEspecial">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("locais");
                                        ?>
                                    </select>
                                    <br>
                                </div>
                            </div>
                            <div class="box-footer">
                    <span id="spanFiltrar" title="Informe uma data de início!">
                        <input type="submit" class="btn btn-primary btn-theme center-block" name="filtrar" id="filtrar"
                               value="Filtrar">
                    </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
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