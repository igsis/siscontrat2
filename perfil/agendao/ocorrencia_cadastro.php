<?php
$con = bancoMysqli();
include "includes/menu_interno.php";
$url = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_locais_espacos.php';

$idEvento = $_SESSION['idEvento'];
$evento = recuperaDados('agendoes', 'id', $idEvento);

?>
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


        if(dataInicio != ""){
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

        <h2 class="page-header">Cadastro de Ocorrência</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <?php echo $evento['nome_evento'] ?>
                        </h3>
                    </div>
                    <form method="POST" action="?perfil=agendao&p=ocorrencia_edita" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="data_inicio">Data Início*</label> <br>
                                    <input type="date" name="data_inicio" class="form-control" id="datepicker10"
                                           required placeholder="DD/MM/AAAA" onblur="validate()">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="data_fim">Data Encerramento (apenas se for temporada)</label> <br>
                                    <input type="date" name="data_fim" class="form-control" id="datepicker11"
                                           placeholder="DD/MM/AAAA" onblur="validate()">
                                </div>
                            </div>
 
                            <div class="row" id="msgEscondeData">
                                <div class="form-group col-md-offset-6 col-md-6">
                                    <span style="color: red;"><b>Data de encerramento menor que a data inicial!</b></span>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>
                                        <input type="checkbox" name="domingo" id="diasemana07" value="1" class="semana"> Domingo &nbsp;
                                        <input type="checkbox" name="segunda" id="diasemana01" value="1" class="semana"> Segunda &nbsp;
                                        <input type="checkbox" name="terca" id="diasemana02" value="1" class="semana"> Terça &nbsp;
                                        <input type="checkbox" name="quarta" id="diasemana03" value="1" class="semana"> Quarta &nbsp;
                                        <input type="checkbox" name="quinta" id="diasemana04" value="1" class="semana"> Quinta &nbsp;
                                        <input type="checkbox" name="sexta" id="diasemana05" value="1" class="semana"> Sexta &nbsp;
                                        <input type="checkbox" name="sabado" id="diasemana06" value="1" class="semana"> Sábado &nbsp;
                                    </label>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="virada">É virada?</label> &nbsp;
                                    <input type="radio" name="virada" id="viradaSim" value="1" class="virada"> Sim
                                    &nbsp;
                                    <input type="radio" name="virada" id="viradaNao" value="0" checked class="virada">
                                    Não
                                </div>

                                <div class="form-group col-md-2">
                                    <input type="checkbox" name="libras" id="libras" value="1"> &nbsp;
                                    <label for="libras">Libras</label>
                                </div>

                                <div class="form-group col-md-2">
                                    <input type="checkbox" name="audiodescricao" id="audiodescricao" value="1"> &nbsp;
                                    <label for="libras">Audiodescrição</label>
                                </div>
                            </div>

                            <div class="row" id="msgEsconde">
                                <div class="form-group col-md-6">
                                    <span style="color: red;">Selecione ao menos um dia da semana!</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="horaInicio">Hora de Início*</label> <br>
                                    <input type="time" name="horaInicio" class="form-control" id="horaInicio" required
                                           placeholder="hh:mm"/>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="horaFim">Hora Fim*</label> <br>
                                    <input type="time" name="horaFim" class="form-control" id="horaFim" required
                                           placeholder="hh:mm"/>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="retiradaIngresso">Retirada de Ingresso</label>
                                    <select name="retiradaIngresso" id="retiradaIngresso" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("retirada_ingressos");
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="valor_ingresso">Valor Ingresso*</label> <br>
                                    <input type="text" name="valor_ingresso" class="form-control" required
                                           id="valor_ingresso"
                                           placeholder="Em reais" onkeypress="return(moeda(this, '.', ',', event))"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="instituicao">Instituição *</label>
                                    <select class="form-control" name="instituicao" id="instituicao">
                                        <option value="">Selecione uma opção...</option>

                                        <?php
                                            geraOpcao("instituicoes");
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="local">Local *</label>
                                    <select class="form-control" id="local" name="local">
                                        <!-- Populando pelo js -->
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="espaco">Espaço</label>
                                    <select class="form-control" id="espaco" name="espaco">
                                        <!-- Populando pelo js -->
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="subprefeitura">Subprefeitura*</label> <br>
                                    <select class="form-control" name="subprefeitura" id="subprefeitura" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("subprefeituras");
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="periodo">Período*</label> <br>
                                    <select class="form-control" name="periodo" id="periodo" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("periodos");
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="observacao">Observação</label><br/>
                                <textarea name="observacao" id="observacao" class="form-control" rows="5"></textarea>
                            </div>

                        </div>

                        <div class="box-footer">
                            <a href="?perfil=agendao&p=ocorrencia_lista">
                                <button type="button" class="btn btn-default" id="voltar" name="voltar">Voltar</button>
                            </a>
                            <button type="submit" name="cadastra" id="cadastra" class="btn btn-info pull-right">
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
    const url = `<?=$url?>`;

    let instituicao = document.querySelector('#instituicao');

    instituicao.addEventListener('change', async e => {
        let idInstituicao = $('#instituicao option:checked').val();

        fetch(`${url}?instituicao_id=${idInstituicao}`)
            .then(response => response.json())
            .then(locais => {
                $('#local option').remove();
                $('#local').append('<option value="">Selecione uma opção...</option>');

                for (const local of locais) {
                    $('#local').append(`<option value='${local.id}'>${local.local}</option>`).focus();
                    ;
                }
            })

    })

    let local = document.querySelector('#local');

    local.addEventListener('change', async e => {
        let idLocal = $('#local option:checked').val();

        fetch(`${url}?espaco_id=${idLocal}`)
            .then(response => response.json())
            .then(espacos => {
                $('#espaco option').remove();
                if (espacos.length < 1) {
                    $('#espaco').append('<option value="">Não há espaço para esse local</option>')
                        .attr('required', false)
                        .focus();
                } else {
                    $('#espaco').append('<option value="">Selecione uma opção...</option>')
                        .attr('required', true)
                        .focus();
                }

                for (const espaco of espacos) {
                    $('#espaco').append(`<option value='${espaco.id}'>${espaco.espaco}</option>`)
                }

            })
    })


    var virada = $('.virada');

    virada.on("change", function () {
        if ($('#viradaNao').is(':checked')) {
            $('#horaInicio')
                .attr('readonly', false)
                .val('');

            $('#horaFim')
                .attr('readonly', false)
                .val('');

            $('#instituicao')
                .attr('readonly', false)
                .val($('option:contains("Selecione uma opção...")').val());

            $('#local')
                .attr('readonly', false)
                .val($('option:contains("Selecione uma opção...")').val());

            $('#espaco')
                .attr('readonly', false)
                .val($('option:contains("Selecione uma opção...")').val());

            $('#retiradaIngresso')
                .attr('readonly', false)
                .val($('option:contains("Selecione uma opção...")').val());

            $('#valor_ingresso')
                .attr('readonly', false)
                .val('');
        } else {
            $('#horaInicio')
                .attr('readonly', true)
                .val('00:00');

            $('#horaFim')
                .attr('readonly', true)
                .val('00:00');

            $('#instituicao')
                .attr('readonly', true)
                .val($('option:contains("Virada Cultural")').val());

            $('#local')
                .attr('readonly', true)
                .val($('option:contains("De acordo com a programação do evento")').val());

            $('#retiradaIngresso')
                .attr('readonly', true)
                .val($('option:contains("INGRESSOS GRÁTIS")').val());

            $('#espaco')
                .attr('readonly', true);

            $('#valor_ingresso')
                .attr('readonly', true)
                .val('0,00');

            getLocais(10, 626);
            getEspacos();
        }

    });

    function getLocais(idInstituicao, selectedId) {
        fetch(`${url}?instituicao_id=${idInstituicao}`)
            .then(response => response.json())
            .then(locais => {
                $('#local option').remove();
                $('#local').append('<option value="">Selecione uma opção...</option>');

                for (const local of locais) {
                    if (selectedId == local.id) {
                        $('#local').append(`<option value='${local.id}' selected>${local.local}</option>`).focus();
                        ;
                    } else {
                        $('#local').append(`<option value='${local.id}'>${local.local}</option>`).focus();
                        ;
                    }

                }
            })
    }

    function getEspacos(idLocal, selectedId) {
        fetch(`${url}?espaco_id=${idLocal}`)
            .then(response => response.json())
            .then(espacos => {
                $('#espaco option').remove();
                if (espacos.length < 1) {
                    $('#espaco').append('<option value="">Não há espaço para esse local</option>')
                        .attr('required', false)
                        .focus();
                } else {
                    $('#espaco').append('<option value="">Selecione uma opção...</option>')
                        .attr('required', true)
                        .focus();
                }

                for (const espaco of espacos) {
                    if (selectedId == espaco.id) {
                        $('#espaco').append(`<option value='${espaco.id}' selected>${espaco.espaco}</option>`)
                    } else {
                        $('#espaco').append(`<option value='${espaco.id}'>${espaco.espaco}</option>`)
                    }
                }

            })
    }

</script>

<script>
function validaDiaSemana(){
    var dataInicio = document.querySelector('#datepicker10').value;
    var isMsg = $('#msgEsconde');
    isMsg.hide();
    if(dataInicio != ""){
        var i = 0;
        var counter = 0;
        var diaSemana = $('.semana');

        for (; i < diaSemana.length; i++) {
            if (diaSemana[i].checked) {
                counter++;
            }
        }

        if (counter==0){
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