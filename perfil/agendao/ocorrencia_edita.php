<?php
$con = bancoMysqli();

$evento = recuperaDados('agendoes', 'id', $_SESSION['idEvento']);
$url = 'http://'.$_SERVER['HTTP_HOST'].'/siscontrat2/funcoes/api_locais_espacos.php';

include "includes/menu_interno.php";

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {

    $tipo_evento_id = 3;
    $origem_ocorrencia_id = $evento['id'];
    $instituicao_id = $_POST['instituicao'];
    $local_id = $_POST['local'];
    $espaco_id = $_POST['espaco'] ?? NULL;
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'] ?? NULL;
    $segunda = $_POST['segunda'] ?? 0;
    $terca = $_POST['terca']   ?? 0;
    $quarta = $_POST['quarta']  ?? 0;
    $quinta = $_POST['quinta']  ?? 0;
    $sexta = $_POST['sexta']   ?? 0;
    $sabado = $_POST['sabado']  ?? 0;
    $domingo = $_POST['domingo'] ?? 0;
    $horario_inicio = $_POST['horaInicio'];
    $horario_fim = $_POST['horaFim'];
    $retirada_ingresso_id = $_POST['retiradaIngresso'];
    $valor_ingresso = dinheiroDeBr($_POST['valor_ingresso']);
    $observacao = addslashes($_POST['observacao']) ?? NULL;
    $idOcorrencia =  $_POST['idOcorrencia'] ?? NULL;
    $periodo_id = $_POST['periodo'];
    $subprefeitura_id = $_POST['subprefeitura'];
    $virada = ($_POST['virada']);
    $libras = $_POST['libras'] ?? 0;
    $audiodescricao = $_POST['audiodescricao'] ?? 0;

}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO agendao_ocorrencias (tipo_ocorrencia_id,
                                 origem_ocorrencia_id,
                                 instituicao_id, 
                                 local_id,
                                 espaco_id,
                                 data_inicio, 
                                 data_fim, 
                                 segunda,
                                 terca,
                                 quarta,
                                 quinta,
                                 sexta,
                                 sabado,
                                 domingo,
                                 horario_inicio,
                                 horario_fim,
                                 retirada_ingresso_id,
                                 valor_ingresso,
                                 observacao,
                                 periodo_id,
                                 subprefeitura_id,
                                 virada,
                                 libras,
                                 audiodescricao)
                          VALUES ('$tipo_evento_id',
                                  '$origem_ocorrencia_id',
                                  '$instituicao_id',
                                  '$local_id',
                                  '$espaco_id',
                                  '$data_inicio',
                                  '$data_fim',
                                  '$segunda',
                                  '$terca',
                                  '$quarta',
                                  '$quinta',
                                  '$sexta',
                                  '$sabado',
                                  '$domingo',
                                  '$horario_inicio',
                                  '$horario_fim',
                                  '$retirada_ingresso_id',
                                  '$valor_ingresso',
                                  '$observacao',
                                  '$periodo_id',
                                  '$subprefeitura_id',
                                  '$virada',
                                  '$libras',
                                  '$audiodescricao')";
    
    if (mysqli_query($con, $sql)) 
    {
        $idOcorrencia = recuperaUltimo('agendao_ocorrencias');
        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }

}

if (isset($_POST['edita'])) {

    $sql = "UPDATE agendao_ocorrencias SET
                            instituicao_id = '$instituicao_id',
                            local_id = '$local_id',
                            espaco_id = '$espaco_id',
                            data_inicio = '$data_inicio',
                            data_fim = '$data_fim',
                            segunda = '$segunda',
                            terca = '$terca',
                            quarta = '$quarta',
                            quinta = '$quinta',
                            sexta = '$sexta',
                            sabado = '$sabado',
                            domingo = '$domingo',
                            horario_inicio = '$horario_inicio',
                            horario_fim = '$horario_fim',
                            retirada_ingresso_id = '$retirada_ingresso_id',
                            valor_ingresso = '$valor_ingresso',
                            observacao = '$observacao',
                            periodo_id = '$periodo_id',
                            subprefeitura_id = '$subprefeitura_id',
                            virada = '$virada',
                            libras = '$libras',
                            audiodescricao = '$audiodescricao'
                            WHERE id = '$idOcorrencia'";
    
    If (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Gravado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if (isset($_POST['carregar'])) {
    $idOcorrencia = $_POST['idOcorrencia'];
}

$ocorrencia = recuperaDados('agendao_ocorrencias', 'id', $idOcorrencia);

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

        if (valor) {
            desmarca();
        }
    }

    // $(document).ready(function () {
    //     validate();
    //     $('#datepicker11').change(validate);
    // });


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
    $(document).ready(comparaData)
    function comparaData() {
        var isMsgData = $('#msgEscondeData');
        isMsgData.hide();
        var dataInicio = document.querySelector('#datepicker10').value;
        var dataFim = document.querySelector('#datepicker11').value;

        if (dataInicio != "") {
            var dataInicio = parseInt(dataInicio.split("-")[0].toString() + dataInicio.split("-")[1].toString() + dataInicio.split("-")[2].toString());
        }

        msgHora.hide()
        $('#edita').attr("disabled", true);

        if (dataFim != "") {
            var dataFim = parseInt(dataFim.split("-")[0].toString() + dataFim.split("-")[1].toString() + dataFim.split("-")[2].toString());

            if (dataFim == "") {
                $('#edita').attr("disabled", false);
            }

            if (dataFim <= dataInicio) {
                isMsgData.show();
                $('#edita').attr("disabled", true);
                mudaData(true);
            } else {
                isMsgData.hide();
                $('#edita').attr("disabled", false);
                mudaData(false);
            }
        } else {
            validaHora()

            let horaInicio = $('#horaInicio').change(validaHora)
            let horaFim = $('#horaFim').change(validaHora)
        }
    }
</script>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Edição de Ocorrência</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <?php echo $evento['nome_evento'] ?>
                        </h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <form method="POST" action="?perfil=agendao&p=ocorrencia_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="data_inicio">Data Início*</label> <br>
                                    <input type="date" name="data_inicio" class="form-control" id="datepicker10"
                                           placeholder="DD/MM/AAAA" required value="<?= $ocorrencia['data_inicio'] ?>" onblur="validate()">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="data_fim">Data Encerramento (apenas se for temporada)</label> <br>
                                    <input type="date" name="data_fim" class="form-control" id="datepicker11"
                                           value="<?= isset($ocorrencia['data_fim']) ? $ocorrencia['data_fim'] : NULL ?>"
                                           placeholder="DD/MM/AAAA" onblur="validate()">
                                </div>
                            </div>

                            <div class="row" id="msgEscondeData"  style="display: none;">
                                <div class="form-group col-md-offset-6 col-md-6">
                                    <span style="color: red;"><b>Data de encerramento deve ser maior que a data inicial</b></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>
                                        <input type="checkbox" name="domingo" id="diasemana07"
                                               value="1" <?php checarOcorrencia($ocorrencia['domingo']) ?> class="semana"> Domingo
                                        &nbsp;
                                        <input type="checkbox" name="segunda" id="diasemana01"
                                               value="1" <?php checarOcorrencia($ocorrencia['segunda']) ?> class="semana"> Segunda
                                        &nbsp;
                                        <input type="checkbox" name="terca" id="diasemana02"
                                               value="1" <?php checarOcorrencia($ocorrencia['terca']) ?> class="semana"> Terça
                                        &nbsp;
                                        <input type="checkbox" name="quarta" id="diasemana03"
                                               value="1" <?php checarOcorrencia($ocorrencia['quarta']) ?> class="semana"> Quarta
                                        &nbsp;
                                        <input type="checkbox" name="quinta" id="diasemana04"
                                               value="1" <?php checarOcorrencia($ocorrencia['quinta']) ?> class="semana"> Quinta
                                        &nbsp;
                                        <input type="checkbox" name="sexta" id="diasemana05"
                                               value="1" <?php checarOcorrencia($ocorrencia['sexta']) ?> class="semana"> Sexta
                                        &nbsp;
                                        <input type="checkbox" name="sabado" id="diasemana06"
                                               value="1" <?php checarOcorrencia($ocorrencia['sabado']) ?> class="semana"> Sábado
                                        &nbsp;
                                    </label>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="virada">É virada?</label> &nbsp;
                                    <input type="radio" name="virada" id="viradaSim" value="1" <?= $ocorrencia['virada'] == 1 ? "checked" : NULL ?> class="virada"> Sim &nbsp;
                                    <input type="radio" name="virada" id="viradaNao" value="0" <?= $ocorrencia['virada'] == 0 ? "checked" : NULL ?>  class="virada" > Não
                                </div>

                                <div class="form-group col-md-2">
                                    <input type="checkbox" name="libras" id="libras" value="1" <?= $ocorrencia['libras'] == 1 ? "checked" : NULL ?>> &nbsp;
                                    <label for="libras">Libras</label>
                                </div>

                                <div class="form-group col-md-2">
                                    <input type="checkbox" name="audiodescricao" id="audiodescricao" value="1" <?= $ocorrencia['audiodescricao'] == 1 ? "checked" : NULL ?>> &nbsp;
                                    <label for="libras">Audiodescrição</label>
                                </div>
                            </div>

                            <div class="row" id="msgEsconde" style="display: none;">
                                <div class="form-group col-md-6">
                                    <span style="color: red;">Selecione ao menos um dia da semana!</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="horaInicio">Hora de Início*</label> <br>
                                    <input type="time" name="horaInicio" class="form-control" id="horaInicio"
                                           value="<?= $ocorrencia['horario_inicio'] ?>" required placeholder="hh:mm"/>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="horaFim">Hora Fim*</label> <br>
                                    <input type="time" name="horaFim" class="form-control" id="horaFim" required
                                           value="<?= $ocorrencia['horario_fim'] ?>" placeholder="hh:mm"/>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="retiradaIngresso">Retirada de Ingresso</label>
                                    <select name="retiradaIngresso" id="retiradaIngresso" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("retirada_ingressos", $ocorrencia['retirada_ingresso_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="valor_ingresso">Valor Ingresso*</label> <br>
                                    <input type="text" name="valor_ingresso" class="form-control"
                                           value="<?= dinheiroParaBr($ocorrencia['valor_ingresso']) ?>" required id="valor_ingresso"
                                           placeholder="Em reais" onkeypress="return(moeda(this, '.', ',', event))"/>
                                </div>
                            </div>

                            <div class="row" id="msgEscondeHora">
                                <div class="form-group col-md-6">
                                    <span style="color: red;">A hora final tem que ser maior que a hora inicial!</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="instituicao">Instituição *</label>
                                    <select class="form-control" name="instituicao" id="instituicao">
                                        <option value="">Selecione uma opção...</option>

                                        <?php
                                        geraOpcao("instituicoes", $ocorrencia['instituicao_id']);
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
                                        geraOpcao("subprefeituras", $ocorrencia['subprefeitura_id']);
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="periodo">Período*</label> <br>
                                    <select class="form-control" name="periodo" id="periodo" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("periodos", $ocorrencia['periodo_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="observacao">Observação</label><br/>
                                <textarea name="observacao" id="observacao" class="form-control"
                                          rows="1"><?= isset($ocorrencia['observacao']) ? $ocorrencia['observacao'] : NULL ?></textarea>
                            </div>

                        </div>

                        <div class="box-footer">
                            <a href="?perfil=agendao&p=ocorrencia_lista"><button type="button" class="btn btn-default" name="voltar">Voltar</button></a>
                            <input type="hidden" name="idOcorrencia" value="<?= $idOcorrencia ?>">
                            <button type="submit" name="edita" id="edita" class="btn btn-info pull-right">Gravar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


<script>

    function insti_local() {
        const urlModal = `<?=$url?>`;

        var idInstituicaoModal = $('#instituicaoModal').val();

        $.post(urlModal, {
            instituicao_id: idInstituicaoModal,
        })
            .done(function (data) {
                $('#SelectLocal option').remove();
                $('#SelectLocal').append('<option value="">Selecione uma opção...</option>');

                for (let local of data) {
                    $('#SelectLocal').append(`<option value='${local.id}'>${local.local}</option>`).focus();
                }
            })
            .fail(function () {
                swal("danger", "Erro ao gravar");
            });

    }

    function cadastraLocal() {
        var instituicao = $("#instituicoes").val();
        var local = $("input[name='localModal']").val();
        var cep = $("input[name='cep']").val();
        var rua = $("input[name='rua']").val();
        var num = $("input[name='numero']").val();
        var complemento = $("input[name='complemento']").val();
        var bairro = $("input[name='bairro']").val();
        var cidade = $("input[name='cidade']").val();
        var estado = $("input[name='estado']").val();
        var zona = $("#zona").val();

        $('#modaLocal').slideUp();

        $.post('?perfil=agendao&p=index', {
            cadastraLocal: 1,
            instituicao: instituicao,
            local: local,
            cep: cep,
            rua: rua,
            numero: num,
            complemento: complemento,
            bairro: bairro,
            cidade: cidade,
            estado: estado,
            zona: zona
        })
            .done(function (data) {
                let res = $(data).find('#resposta').text();

                if (res == 0) {
                    swal('Esse local já existe! Procure-o na lista novamente.', '', 'warning')
                        .then(() => {
                            $('#modaLocal').slideDown('slow');
                        });
                } else if (res == 1) {
                    swal("Solicitação de novo local enviada com sucesso!", "Após o administrador verificar sua solicitação, seja ela aprovada ou não você receberá uma notificação em seu e-mail.", "success")
                        .then(() => {
                            $('#modaLocal').modal('hide');
                        });
                } else {
                    console.log(res);
                    swal("Erro na solicitação! Tente novamente.", "", "danger")
                        .then(() => {
                            $('#modaLocal').slideDown('slow');
                        });
                }

            })
            .fail(function () {
                swal("danger", "Erro ao gravar");
            });
    }

    function cadastraEspaco() {

        var local = $("#SelectLocal").val();
        var espaco = $("input[name='espaco']").val();

        $('#modalEspaco').slideUp();

        $.post('?perfil=agendao&p=index', {
            cadastraEspaco: 1,
            espaco: espaco,
            local: local
        })
            .done(function (data) {
                let res = $(data).find('#resposta').text();

                if (res == 0) {
                    swal('Esse espaço já existe! Procure-o na lista novamente.', '', 'warning')
                        .then(() => {
                            $('#modalEspaco').slideDown('slow');
                        });
                } else if (res == 1) {
                    swal("Solicitação de novo espaço enviada com sucesso!", "Após o administrador verificar sua solicitação, seja ela aprovada ou não você receberá uma notificação em seu e-mail.", "success")
                        .then(() => {
                            $('#modalEspaco').modal('hide');
                        });
                } else {
                    swal("Erro na solicitação! Tente novamente.", "", "error")
                        .then(() => {
                            $('#modalEspaco').slideDown('slow');
                        });
                }
            })
            .fail(function () {
                swal("danger", "Erro ao gravar");
            });
    }

    let data_fim = document.querySelector("input[name='data_fim']");

    if (data_fim.value != '') {
        let dias = document.querySelectorAll("input[type='checkbox']");

        for (dia of dias) {
            dia.disabled = false;
        }

    }

    const url = `<?=$url?>`;

    let instituicao = document.querySelector('#instituicao');
    let local_id = <?=$ocorrencia['local_id']?>;

    if (instituicao.value != '') {
        getLocais(instituicao.value, local_id)
    }

    instituicao.addEventListener('change', async e => {
        let idInstituicao = $('#instituicao option:checked').val();
        getLocais(idInstituicao, '')
        getEspacos('', '') // Se alterar o primeiro ele limpa o local e o espaço

    })

    var virada = $('.virada');
    virada.on("click", verificaVirada);

    function verificaVirada() {
        let opc = parseInt($('input[name="virada"]:checked').val())
        if (opc) {

            $('#horaInicio')
                .val('00:00');

            $('#horaFim')
                .val('00:00');

            $('#instituicao')
                .val($('option:contains("Virada Cultural")').val());

            $('#retiradaIngresso')
                .val($('option:contains("INGRESSOS GRÁTIS")').val());

            $('#valor_ingresso')
                .val('0,00');

            getLocais(10, 189);
            getEspacos();

            $('#local').val(189);
        } else {
            $('#instituicao')
                .val($('option:contains("Selecione uma opção...")').val());

            $('#espaco')
                .val($('option:contains("Selecione uma opção...")').val());

            $('#retiradaIngresso')
                .val($('option:contains("Selecione uma opção...")').val());

            $('#valor_ingresso')
                .val('0,00');
        }
    }

    let retiradaIngresso = document.querySelector('#retiradaIngresso');

    retiradaIngresso.addEventListener("change", () => {
        let valorIngressos = document.querySelector('#valor_ingresso');
        if (retiradaIngresso.value == 2 || retiradaIngresso.value == 7 || retiradaIngresso.value == 5 || retiradaIngresso.value == 11){
            valorIngressos.value = '0,00';
            valorIngressos.readOnly = true;
        }else {
            valorIngressos.readOnly = false;
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

    let local = document.querySelector('#local');
    let idEspaco = <?=$ocorrencia['espaco_id']?>;

    if (local_id != '') {

        console.log(`local ${local_id} Espaco ${idEspaco}`);
        getEspacos(local_id, idEspaco)
    }

    local.addEventListener('change', async e => {
        let idLocal = $('#local option:checked').val();

        getEspacos(idLocal, '')
    })

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

    function cepPesquisa() {
        function limpa_formulário_cep() {
            // Limpa valores do formulário de cep.
            $("#rua").val("");
            $("#bairro").val("");
            $("#cidade").val("");
            $("#estado").val("");
        }

        //Quando o campo cep perde o foco.
        $("#cep").blur(function () {
            //Nova variável "cep" somente com dígitos.
            var cep = $(this).val().replace(/\D/g, '');

            //Verifica se campo cep possui valor informado.
            if (cep != "") {

                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                //Valida o formato do CEP.
                if (validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
                    $("#rua").val("...");
                    $("#bairro").val("...");
                    $("#cidade").val("...");
                    $("#estado").val("...");

                    //Consulta o webservice viacep.com.br/
                    $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                        if (!("erro" in dados)) {
                            //Atualiza os campos com os valores da consulta.
                            $("#rua").prop('readonly', true);
                            $("#bairro").prop('readonly', true);
                            $("#cidade").prop('readonly', true);
                            $("#estado").prop('readonly', true);

                            $("#rua").val(dados.logradouro);
                            $("#bairro").val(dados.bairro);
                            $("#cidade").val(dados.localidade);
                            $("#estado").val(dados.uf);

                            if (dados.logradouro == "") {
                                alert("Por favor preencha o formulário");
                                $("#rua").prop('readonly', false);
                                $("#bairro").prop('readonly', false);
                                $("#cidade").prop('readonly', false);
                                $("#estado").prop('readonly', false);
                            }
                        } else {
                            //CEP pesquisado não foi encontrado.
                            limpa_formulário_cep();
                            alert("CEP não encontrado.");
                        }
                    });
                } else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        });
    }
</script>

<script>
    let msgHora = $('#msgEscondeHora');
    // msgHora.hide();

    function validaHora() {
        let horaInicio = $('#horaInicio').val();
        let horaFim = $('#horaFim').val();

        if (horaFim != "" && horaInicio != "") {
            horaInicio = parseInt(horaInicio.split(":")[0].toString() + horaInicio.split(":")[1].toString());
            horaFim = parseInt(horaFim.split(":")[0].toString() + horaFim.split(":")[1].toString());

            if (horaFim < horaInicio) {
                msgHora.show();
                $('#edita').attr("disabled", true);
            } else {
                msgHora.hide();
                $('#edita').attr("disabled", false);
            }
        }
    }

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
                $('#edita').attr("disabled", true);
                isMsg.show();
                return false;
            }

            $('#edita').attr("disabled", false);
            isMsg.hide();
            return true;
        }
    }

    var diaSemana = $('.semana').change(validaDiaSemana)
</script>