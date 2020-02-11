<?php
    include "includes/menu_principal.php";
?>
<div class="content-wrapper">
    <section class="content">
    <h3 class="box-title">Eventos - Gerar Excel - Filtrar</h3>
        <div class="box box-primary" id="filtro">
            <form method="POST" action="?perfil=agendao&p=resultado">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-4" align="center">
                            <label for="inserido">Inserido por: (usuário)</label>
                            <input type="text" class="form-control" id="inserido" name="inserido" placeholder="">
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-4 col-md-4" align="center">
                            <label for="projetoEspecial">Projeto Especial: </label> <br>
                            <select class="form-control" name="projetoEspecial" id="projetoEspecial">
                                <option value="">Selecione uma opção...</option>
                                <?php
                                geraOpcaoPublicado("projeto_especiais", "");
                                ?>
                            </select>
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-4 col-md-4" align="center">
                            <label for="local">Local: </label>
                            <select name="local" class="form-control" id="local">
                                <option value="">Seleciona uma opção...</option>
                                <?php
                                geraOpcao("locais", ""); ?>
                            </select>
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-3 col-md-3">
                            <label>Data início: </label>
                            <input type="date" name="inicio" class="form-control" id="data_inicio">
                        </div>
                        <div class="col-md-3">
                            <label>Data encerramento: </label>
                            <input type="date" name="final" class="form-control" id="final">
                            <br>
                        </div>
                    </div>
                    <div class="row" id="msg">
                                <div style="text-align: center;" class="form-group col-md-12">
                                    <span style="color: red;"><b>Data de início precisa ser maior que a data final!</b></span>
                                </div>
                            </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-theme center-block" id="filtrar" name="filtrar">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
$('#msg').hide();
function comparaData() {
        var isMsg = $('#msg');
        var dataInicio = document.querySelector('#data_inicio').value;
        var dataFim = document.querySelector('#final').value;

        if (dataInicio != "" && dataFim != "") {
            var dataInicio = parseInt(dataInicio.split("-")[0].toString() + dataInicio.split("-")[1].toString() + dataInicio.split("-")[2].toString());
            var dataFim = parseInt(dataFim.split("-")[0].toString() + dataFim.split("-")[1].toString() + dataFim.split("-")[2].toString());
            isMsg.hide();
        }

        $('#filtrar').attr("disabled", true);
            
        if (dataFim <= dataInicio) {
            $('#filtrar').attr("disabled", true);
            isMsg.show();
        } else {
            $('#filtrar').attr("disabled", false);
            isMsg.hide();
        }
}
   $('#data_inicio').on('change', comparaData);
   $('#final').on('change', comparaData);
</script>