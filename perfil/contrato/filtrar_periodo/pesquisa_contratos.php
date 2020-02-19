<?php
if (isset($_POST['reabertura'])) {
    $con = bancoMysqli();
    $idEvento = $_SESSION['idEvento'];
    $now = date('Y-m-d H:i:s', strtotime("-3 Hours"));
    $idUsuario = $_SESSION['usuario_id_s'];
    $sql = "INSERT INTO evento_reaberturas (evento_id, data_reabertura, usuario_reabertura_id) VALUES ('$idEvento', '$now', '$idUsuario')";
    $sqlStatus = "UPDATE eventos SET evento_status_id = 1 WHERE id = '$idEvento'";

    if ((mysqli_query($con, $sql)) && (mysqli_query($con, $sqlStatus))) {
        $mensagem = mensagem("success", "Reabertura do evento realizada com sucesso!");
        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao efetuar a reabertura do evento! Tente novamente.");
    }
}

unset($_SESSION['idEvento']);
unset($_SESSION['idPedido']);
?>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Busca</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Busca por Período</h3>
                    </div>
                    <form method="POST" action="?perfil=contrato&p=filtrar_periodo&sp=resultado"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="data_inicio">Data Início *</label> <br>
                                    <input type="date" name="data_inicio" class="form-control" id="datepicker10"
                                           placeholder="DD/MM/AAAA" onblur="comparaData()">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="data_fim">Data Final *</label><br>
                                    <input type="date" name="data_fim" class="form-control" id="datepicker11"
                                           placeholder="DD/MM/AAAA" onblur="comparaData()">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="operador">Operador </label>
                                    <select name="operador" id="operador" class="form-control">
                                        <option value="0">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('usuarios u INNER JOIN usuario_contratos uc on uc.usuario_id = u.id');
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
