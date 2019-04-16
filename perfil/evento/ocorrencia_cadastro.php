<?php
$con = bancoMysqli();
include "includes/menu_interno.php";
$url = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_locais_espacos.php';

$idEvento = $_SESSION['idEvento'];
$_SESSION['idOrigem'] = $_POST['idOrigem'];
$idAtracao = $_SESSION['idOrigem'];

$evento = recuperaDados('eventos', 'id', $idEvento);

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
        }
        else {
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
    }

    function comparaData() {
        var dataInicio = document.querySelector('#datepicker10').value;
        var dataFim = document.querySelector('#datepicker11').value;

        var dataInicio = parseInt(dataInicio.split("-")[0].toString() + dataInicio.split("-")[1].toString() + dataInicio.split("-")[2].toString());
        if (dataFim != "") {
            var dataFim = parseInt(dataFim.split("-")[0].toString() + dataFim.split("-")[1].toString() + dataFim.split("-")[2].toString());

            if (dataFim <= dataInicio) {
                alert("Data final menor que a data inicial");
                $('#cadastra').attr("disabled", true);
            } else {
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
                    <form method="POST" action="?perfil=evento&p=ocorrencia_edita" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="data_inicio">Data Início*</label> <br>
                                    <input type="date" name="data_inicio" class="form-control" id="datepicker10"
                                           required placeholder="DD/MM/AAAA" onblur="mudaData()">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="data_fim">Data Encerramento (apenas se for temporada)</label> <br>
                                    <input type="date" name="data_fim" class="form-control" id="datepicker11"
                                           placeholder="DD/MM/AAAA" onblur="validate()">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>
                                        <input type="checkbox" name="domingo" id="diasemana07" value="1"> Domingo &nbsp;
                                        <input type="checkbox" name="segunda" id="diasemana01" value="1"> Segunda &nbsp;
                                        <input type="checkbox" name="terca" id="diasemana02" value="1"> Terça &nbsp;
                                        <input type="checkbox" name="quarta" id="diasemana03" value="1"> Quarta &nbsp;
                                        <input type="checkbox" name="quinta" id="diasemana04" value="1"> Quinta &nbsp;
                                        <input type="checkbox" name="sexta" id="diasemana05" value="1"> Sexta &nbsp;
                                        <input type="checkbox" name="sabado" id="diasemana06" value="1"> Sábado &nbsp;
                                    </label>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="virada">É virada?</label> &nbsp;
                                    <input type="radio" name="virada" id="viradaSim" value="1" class="virada"> Sim
                                    &nbsp;
                                    <input type="radio" name="virada" id="viradaNao" value="0" checked class="virada">
                                    Não
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
                                    <label for="instituicao">Instituição</label>
                                    <select class="form-control" name="instituicao" id="instituicao" required>
                                        <option value="">Selecione uma opção...</option>

                                        <?php
                                        geraOpcao("instituicoes");
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="local">Local</label><!--
                                    <a href="?perfil=evento&p=solicitar_local">-->
                                    <button type="button" name="idAtracao" data-toggle='modal'
                                            data-target='#modaLocal' class="btn-success pull-right"><i
                                                class="fa fa-plus"></i></button>
                                    <!--</a>-->
                                    <select class="form-control" id="local" name="local">
                                        <!-- Populando pelo js -->
                                    </select>

                                </div>

                                <div class="form-group col-md-4">
                                    <label for="espaco">Espaço</label>
                                    <button type="button" data-toggle="modal" data-target="#modalEspaco"
                                            class="btn-success pull-right"><i class="fa fa-plus"></i>
                                    </button>
                                    <select class="form-control" id="espaco" name="espaco">
                                        <!-- Populando pelo js -->
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="observacao">Observação</label><br/>
                                <textarea name="observacao" id="observacao" class="form-control" rows="5"></textarea>
                            </div>

                        </div>

                        <div class="box-footer">
                            <a href="?perfil=evento&p=evento_cinema_lista">
                                <button type="button" class="btn btn-default" id="voltar" name="voltar">Voltar</button>
                            </a>
                            <input type="hidden" name="idOrigem" value="<?= $_POST['idOrigem'] ?>">
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

<!-- Modal solicitar local -->
<div class="modal fade" id="modaLocal" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-bold"><p>Solicitar adição de Local</p></h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="#" role="form">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="cep">Instituição: *</label>
                            <select name="instituicaoModal" id="instituicaoModal" class="form-control" required>
                                <?php
                                geraOpcao('instituicoes');
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="cep">Local: *</label>
                            <input type="text" class="form-control" name="localModal" id="localModal" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="cep">CEP: *</label>
                            <input type="text" class="form-control" name="cep" id="cep" maxlength="9"
                                   placeholder="Digite o CEP" required data-mask="00000-000" onblur="cepPesquisa();">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cep">Zona: *</label>
                            <select class="form-control" id="zona" name="zona">
                                <?php
                                geraOpcao('zonas');
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="numero">Número: *</label>
                            <input type="number" name="numero" class="form-control" placeholder="Ex.: 10"
                                   required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="complemento">Complemento:</label>
                            <input type="text" name="complemento" class="form-control" maxlength="20"
                                   placeholder="Ex.: Ap. 100">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="rua">Rua: *</label>
                            <input type="text" class="form-control" name="rua" id="rua"
                                   placeholder="Digite a rua" maxlength="200" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label for="bairro">Bairro: *</label>
                            <input type="text" class="form-control" name="bairro" id="bairro"
                                   placeholder="Digite o Bairro" maxlength="80" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cidade">Cidade: *</label>
                            <input type="text" class="form-control" name="cidade" id="cidade"
                                   placeholder="Digite a cidade" maxlength="50" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="estado">Estado: *</label>
                            <input type="text" class="form-control" name="estado" id="estado" maxlength="2"
                                   placeholder="Ex.: SP" readonly>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.box-body -->
            <div class="modal-footer">
                <input type="hidden" name="cadastraLocal">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type='button' class='btn btn-success' name="cadastraLocal" id="cadastraLocal"
                        onclick="cadastraLocal()">Solicitar
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal solicitar espaco -->
<div class="modal fade" id="modalEspaco" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><p>Solicitar adição de Espaço</p></h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="#" role="form">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="instituicaoModal">Instituição</label>
                            <select class="form-control" name="instituicaoModal" id="instituicaoModal"
                                    required>
                                <option value="">Selecione uma opção...</option>
                                <?php
                                geraOpcao("instituicoes");
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="localModal">Local: </label>
                            <select name="localModal" id="localModal" class="form-control" required>
                                <!--
                                geraOpcaoPublicado('locais');
                                ?> -->
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="sigla">Espaço: </label>
                            <input type="text" class="form-control" id="espaco" name="espaco" required>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.box-body -->
            <div class="modal-footer">
                <input type="hidden" name="cadastraEspaco">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type='button' class='btn btn-success' name="cadastraEspaco" id="cadastraEspaco"
                        onclick="cadastraEspaco()">Solicitar
                </button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

        const urlModal = `<?=$url?>`;
        let instituicaoModal = document.querySelector('#instituicaoModal');

        instituicaoModal.addEventListener('change', async e => {

            console.log(instituicaoModal);
            let idInstituicaoModal = $('#instituicaoModal option:checked').val();

            fetch(`${urlModal}?instituicao_id=${idInstituicaoModal}`)
                .then(response => response.json())
                .then(locais => {
                    $('#localModal option').remove();
                    $('#localModal').append('<option value="">Selecione uma opção...</option>');

                    for (const local of locais) {
                        $('#localModal').append(`<option value='${local.id}'>${local.local}</option>`).focus();
                    }
                })

            console.log(locais);
        })


    function cadastraLocal() {

        var instituicao = $("#instituicaoModal").val();
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

        $.post('?perfil=evento&p=index', {
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
            .done(function () {
                swal("Solicitacao de novo local enviada com sucesso!", "Aguarde a analise de um administrador, ele ira aprovar ou nao sua solicitacao.", "success")
                    .then(() => {
                        $('#modaLocal').modal('hide');
                    });
            })
            .fail(function () {
                swal("danger", "Erro ao gravar");
            });
    }


    function cadastraEspaco() {

        var local = $("#localId").val();
        var espaco = $("input[name='espaco']").val();

        $('#modalEspaco').slideUp();

        $.post('?perfil=evento&p=index', {
            cadastraEspaco: 1,
            espaco: espaco,
            local: local
        })
            .done(function () {
                swal("Solicitacao de novo espaco enviada com sucesso!", "Aguarde a analise de um administrador, ele ira aprovar ou nao sua solicitacao.", "success")
                    .then(() => {
                        $('#modaLocal').modal('hide');
                    });
            })
            .fail(function () {
                swal("danger", "Erro ao gravar");
            });
    }


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

        console.log(locais);

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
        }

        getLocais(10, 626);
        getEspacos();
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
                        }
                        else {
                            //CEP pesquisado não foi encontrado.
                            limpa_formulário_cep();
                            alert("CEP não encontrado.");
                        }
                    });
                }
                else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            }
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        });
    }

</script>


