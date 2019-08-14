<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

$idAtracao = $_POST['idAtracao'];
$sqlDias = "SELECT * FROM execucao_dias";
$queryDias = mysqli_query($con, $sqlDias);
?>

<script language="JavaScript">

    function barraData(n) {
        if (n.value.length == 2)
            c.value += '/';
        if (n.value.length == 5)
            c.value += '/';
    }
</script>

<script type="text/javascript">
    function desmarca() {
        $("#diasemana01").prop("checked", false);
        $("#diasemana02").prop("checked", false);
        $("#diasemana03").prop("checked", false);
        $("#diasemana04").prop("checked", false);
        $("#diasemana05").prop("checked", false);
        $("#diasemana06").prop("checked", false);
        $("#diasemana07").prop("checked", false);
    }

    function mudaData(valor) {
        $("#diasemana01").prop("disabled", valor);
        $("#diasemana02").prop("disabled", valor);
        $("#diasemana03").prop("disabled", valor);
        $("#diasemana04").prop("disabled", valor);
        $("#diasemana05").prop("disabled", valor);
        $("#diasemana06").prop("disabled", valor);
        $("#diasemana07").prop("disabled", valor);

        desmarca();
    }

    $(document).ready(function () {
        validate();
        $('#datepicker11').change(validate);
    });

    function validate() {
        comparaData();
        if ($('#datepicker11').val().length > 0) {
            mudaData(false);
        } else {
            mudaData(true);

            var data = document.querySelector('input[name="data_inicio"]').value;
            data = new Date(data);
            dayName = new Array("0", "1", "2", "3", "4", "5", "6", "0");
            let dia = dayName[data.getDay() + 1];

            if (dia == 0) {
                $("#diasemana07").prop("disabled", false);
                $("#diasemana07").prop("checked", true);
            } else if (dia == 1) {
                $("#diasemana01").prop("disabled", false);
                $("#diasemana01").prop("checked", true);
            } else if (dia == 2) {
                $("#diasemana02").prop("disabled", false);
                $("#diasemana02").prop("checked", true);
            } else if (dia == 3) {
                $("#diasemana03").prop("disabled", false);
                $("#diasemana03").prop("checked", true);
            } else if (dia == 4) {
                $("#diasemana04").prop("disabled", false);
                $("#diasemana04").prop("checked", true);
            } else if (dia == 5) {
                $("#diasemana05").prop("disabled", false);
                $("#diasemana05").prop("checked", true);
            } else if (dia == 6) {
                $("#diasemana06").prop("disabled", false);
                $("#diasemana06").prop("checked", true);
            }
        }

        validaDiaSemana();
    }

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

            if (dataFim <= dataInicio) {
                isMsgData.show();
                $('#cadastra').attr("disabled", true);
            } else {
                isMsgData.hide();
                $('#cadastra').attr("disabled", false);
            }
        }

        if (dataFim == "") {
            $('#cadastra').attr("disabled", false);
        }
    }
</script>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atração - Especificidades de Área</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=oficina_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="modalidade">Modalidade:</label>
                                    <input type="text" id="modalidade" name="modalidade" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="desc_modalidade">Descrição da Modalidade:</label><br/>
                                    <textarea name="desc_modalidade" id="desc_modalidade" class="form-control"
                                              rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="valor_hora">Valor hora/aula: </label><br>
                                    <input class="form-control" style="max-width: 175px;" type="tel" name="valor_hora"
                                           onkeypress="return(moeda(this, '.', ',', event))">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="carga_horaria">Carga Horária (em horas): </label><br>
                                    <input class="form-control" style="max-width: 175px;" type="number"
                                           name="carga_horaria"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="data_inicio">Início de inscrição: </label> <br/>
                                    <input class="form-control" style="max-width: 175px;" type="date" name="data_inicio"
                                           id="datepicker10"
                                           onkeyup="barraData(this);" onblur="validate()"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="data_fim">Encerramento de inscrição: </label> <br>
                                    <input class="form-control" style="max-width: 175px;" type="date" name="data_fim"
                                           id="datepicker11" onblur="validate()"/>
                                </div>
                            </div>
                            <div class="row" id="msgEscondeData">
                                <div class="form-group col-md-6">
                                    <span style="color: red;"><b>Data de encerramento menor que a data inicial!</b></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">

                                    <label>Selecione o primeiro dia de execução:</label>
                                    <select name="idDia1">
                                        <option>Selecione o Dia</option>
                                        <?php
                                        while ($dias = mysqli_fetch_array($queryDias)) {

                                            echo "<option value='" . $dias['id'] . "'>" . $dias['dia'] . "</option>";
                                            ?>

                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">

                                    <label>Selecione o segundo dia de execução:</label>
                                    <select name="idDia2">
                                        <option>Selecione o Dia</option>
                                        <?php
                                        $sqlDias = "SELECT * FROM execucao_dias";
                                        $queryDias = mysqli_query($con, $sqlDias);
                                        while ($dias2 = mysqli_fetch_array($queryDias)) {

                                            echo "<option value='" . $dias2['id'] . "'>" . $dias2['dia'] . "</option>";
                                            ?>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=evento&p=atracoes_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" name="idAtracao" value="<?= $idAtracao ?>">
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </section>
</div>
<script>
    function validaDiaSemana() {
        var dataInicio = document.querySelector('#datepicker10').value;
        var isMsg = $('#msgEsconde');
        isMsg.hide();
        if (dataInicio != "") {
            var i = 0;
            var counter = 0;
            var diaSemana = $('.semana');

            for (; i < diaSemana.length; i++) {
                if (diaSemana[i].checked) {
                    counter++;
                }
            }

            if (counter == 0) {
                $('#cadastra').attr("disabled", true);
                isMsg.show();
                return false;
            }

            $('#cadastra').attr("disabled", false);
            isMsg.hide();
            return true;
        }
    }

    var diaSemana = $('.semana');
    diaSemana.change(validaDiaSemana);
</script>