<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Busca</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Busca por Período</h3>
                    </div>
                    <form method="POST" action="?perfil=contrato&p=resultado_periodo"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="data_inicio">Data Início: *</label> <br>
                                    <input type="date" name="data_inicio" class="form-control" id="datepicker10"
                                           placeholder="DD/MM/AAAA" onblur="comparaData()" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="data_fim">Data Final: </label><br>
                                    <input type="date" name="data_fim" class="form-control" id="datepicker11"
                                           placeholder="DD/MM/AAAA" onblur="comparaData()">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="operador">Operador </label>
                                    <select name="operador" id="operador" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('usuarios u INNER JOIN usuario_contratos uc ON uc.usuario_id = u.id WHERE uc.nivel_acesso = 2');
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="row" id="msgEscondeData">
                                <div class="form-group col-md-6">
                                    <span style="color: red;"><b>Data de encerramento deve ser maior que a data inicial</b></span>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="busca" id="busca" class="btn btn-primary pull-right">
                                Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function comparaData() {
        var isMsgData = $('#msgEscondeData');
        isMsgData.hide();
        var dataInicio = document.querySelector('#datepicker10').value;
        var dataFim = document.querySelector('#datepicker11').value;

        if (dataInicio != "") {
            var dataInicio = parseInt(dataInicio.split("-")[0].toString() + dataInicio.split("-")[1].toString() + dataInicio.split("-")[2].toString());
        }

        if (dataFim != "") {
            var dataFim = parseInt(dataFim.split("-")[0].toString() + dataFim.split("-")[1].toString() + dataFim.split("-")[2].toString());

            if (dataFim == "") {
                $('#busca').attr("disabled", false);
            }

            if (dataFim <= dataInicio) {
                isMsgData.show();
                $('#busca').attr("disabled", true);
            } else {
                isMsgData.hide();
                $('#busca').attr("disabled", false);
            }
        }
    }

    $('#msgEscondeData').hide();
</script>
