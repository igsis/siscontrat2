<?php
$con = bancoMysqli();

$idEvento = $_SESSION['idEvento'];
$idOrigem = $_SESSION['idOrigem'];

$evento = recuperaDados('eventos', 'id', $idEvento);
$url = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_locais_espacos.php';
$urlAjax = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_ajax_data_excessao.php';

include "includes/menu_interno.php";

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {

    // origem_ocorrencia_id = idEvento
    // atracao_id = idAtracao/idOrigem/idFilme

    $tipo_evento_id = $evento['tipo_evento_id'];
    $origem_ocorrencia_id = $idEvento;
    $atracao_id = $idOrigem;
    $instituicao_id = $_POST['instituicao'];
    $local_id = $_POST['local'];
    $espaco_id = $_POST['espaco'] ?? NULL;
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'] ?? NULL;
    $segunda = $_POST['segunda'] ?? 0;
    $terca = $_POST['terca'] ?? 0;
    $quarta = $_POST['quarta'] ?? 0;
    $quinta = $_POST['quinta'] ?? 0;
    $sexta = $_POST['sexta'] ?? 0;
    $sabado = $_POST['sabado'] ?? 0;
    $domingo = $_POST['domingo'] ?? 0;
    $horario_inicio = $_POST['horaInicio'];
    $horario_fim = $_POST['horaFim'];
    $retirada_ingresso_id = $_POST['retiradaIngresso'];
    $valor_ingresso = dinheiroDeBr($_POST['valor_ingresso']);
    $observacao = trim(addslashes($_POST['observacao'])) ?? NULL;
    $idOcorrencia = $_POST['idOcorrencia'] ?? NULL;
    $periodo_id = $_POST['periodo'];
    $subprefeitura_id = $_POST['subprefeitura'];
    $virada = $_POST['virada'];
    $libras = $_POST['libras'] ?? 0;
    $audiodescricao = $_POST['audiodescricao'] ?? 0;

}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO ocorrencias (tipo_ocorrencia_id,
                                 origem_ocorrencia_id,
                                 atracao_id,
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
                                  '$atracao_id',
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

    if (mysqli_query($con, $sql)) {
        $idOcorrencia = recuperaUltimo('ocorrencias');
        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
        echo $sql;
    }
}

if (isset($_POST['edita'])) {

    $sql = "UPDATE ocorrencias SET
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
                            periodo_id = '$periodo_id',
                            subprefeitura_id = '$subprefeitura_id',
                            virada = '$virada',
                            libras = '$libras',
                            audiodescricao = '$audiodescricao',
                            observacao = '$observacao'
                            WHERE id = '$idOcorrencia'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Gravado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if (isset($_POST['carregar'])) {
    if (isset($_POST['idFilme'])) {
        $idFilme = $_POST['idFilme'];
    }
    $idOcorrencia = $_POST['idOcorrencia'];
}

if (isset($_POST['idFilme'])):
    $idFilme = $_POST['idFilme'];
endif;

$ocorrencia = recuperaDados('ocorrencias', 'id', $idOcorrencia);
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
                    <form method="POST" action="?perfil=evento&p=ocorrencia_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="data_inicio">Data Início*</label> <br>
                                    <input type="date" name="data_inicio" class="form-control" id="datepicker10"
                                           placeholder="DD/MM/AAAA" required value="<?= $ocorrencia['data_inicio'] ?>"
                                           onblur="validate()">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="data_fim">Data Encerramento (apenas se for temporada)</label> <br>
                                    <input type="date" name="data_fim" class="form-control" id="datepicker11"
                                           value="<?= isset($ocorrencia['data_fim']) ? $ocorrencia['data_fim'] : NULL ?>"
                                           placeholder="DD/MM/AAAA" onblur="validate()">
                                </div>
                            </div>


                            <div class="row" id="msgEscondeData" style="display: none;">
                                <div class="form-group col-md-offset-6 col-md-6">
                                    <span style="color: red;"><b>Data de encerramento deve ser maior que a data inicial</b></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>
                                        <input type="checkbox" name="domingo" id="diasemana07"
                                               value="1"
                                               class="semana" <?php checarOcorrencia($ocorrencia['domingo']) ?>> Domingo
                                        &nbsp;
                                    </label>
                                    <label>
                                        <input type="checkbox" name="segunda" id="diasemana01"
                                               value="1"
                                               class="semana" <?php checarOcorrencia($ocorrencia['segunda']) ?>> Segunda
                                        &nbsp;
                                    </label>
                                    <label>
                                        <input type="checkbox" name="terca" id="diasemana02"
                                               value="1" class="semana" <?php checarOcorrencia($ocorrencia['terca']) ?>>
                                        Terça
                                        &nbsp;
                                    </label>
                                    <label>
                                        <input type="checkbox" name="quarta" id="diasemana03"
                                               value="1"
                                               class="semana" <?php checarOcorrencia($ocorrencia['quarta']) ?>> Quarta
                                        &nbsp;
                                    </label>
                                    <label>
                                        <input type="checkbox" name="quinta" id="diasemana04"
                                               value="1"
                                               class="semana" <?php checarOcorrencia($ocorrencia['quinta']) ?>> Quinta
                                        &nbsp;
                                    </label>
                                    <label>
                                        <input type="checkbox" name="sexta" id="diasemana05"
                                               value="1" class="semana" <?php checarOcorrencia($ocorrencia['sexta']) ?>>
                                        Sexta
                                        &nbsp;
                                    </label>
                                    <label>
                                        <input type="checkbox" name="sabado" id="diasemana06"
                                               value="1"
                                               class="semana" <?php checarOcorrencia($ocorrencia['sabado']) ?>> Sábado
                                        &nbsp;
                                    </label>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="virada">É virada?</label> &nbsp;
                                    <input type="radio" name="virada" id="viradaSim"
                                           value="1" class="virada" <?= $ocorrencia['virada'] == 1 ? "checked" : "" ?>>
                                    Sim &nbsp;
                                    <input type="radio" name="virada" id="viradaNaoviradaNao"
                                           value="0" class="virada" <?= $ocorrencia['virada'] == 0 ? "checked" : "" ?>>
                                    Não
                                </div>

                                <label>
                                    <input type="checkbox" name="libras" id="libras"
                                           value="1" <?= $ocorrencia['libras'] == 1 ? "checked" : NULL ?>> Libras
                                    &nbsp;
                                </label>
                                <label>
                                    <input type="checkbox" name="audiodescricao" id="audiodescricao"
                                           value="1" <?= $ocorrencia['audiodescricao'] == 1 ? "checked" : NULL ?>>
                                    Audiodescrição
                                    &nbsp;
                                </label>
                            </div>
                            <?php if (isset($ocorrencia['data_fim']) && $ocorrencia['data_fim'] != "0000-00-00"): ?>
                                <div class="row" style="margin-bottom: 15px">
                                    <div class="col-md-12">
                                        <button id="dtExc" class="btn btn-info" type="button">
                                            Data de exceção
                                        </button>
                                    </div>
                                </div>
                            <?php endif ?>
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

                                <?php
                                if ($evento['tipo_evento_id'] == 2 && isset($idFilme)) {
                                    $filme = $con->query("SELECT duracao FROM filmes WHERE id = $idFilme")->fetch_array();
                                    $readonly = "readonly";
                                    ?>
                                    <script type="text/javascript">

                                        $('#horaInicio').on('change', function () {
                                            var horainicio = $('#horaInicio').val();
                                            var hora = parseInt(horainicio.split(':', 1));
                                            var minuto = parseInt(horainicio[3] + horainicio[4]);
                                            var duracao = <?=$filme['duracao']?>;
                                            while (duracao >= 60) {
                                                duracao -= 60;
                                                hora += 1;
                                            }
                                            var minutoFinal = minuto + duracao;
                                            if (minutoFinal >= 60) {
                                                minutoFinal -= 60;
                                                hora += 1;
                                            }
                                            if (minutoFinal == 0 && minutoFinal != "00") {
                                                minutoFinal = minutoFinal + "0";
                                            }
                                            if (minutoFinal < 10) {
                                                minutoFinal = "0" + minutoFinal;
                                            }
                                            if (hora == 0 && minutoFinal != "00") {
                                                hora = hora + "0";
                                            }
                                            if (hora < 10) {
                                                hora = "0" + hora;
                                            }
                                            if (hora == "00") {
                                                hora = "00";
                                            }
                                            if (hora == "000") {
                                                hora = "00";
                                            }

                                            var resultado = hora + ":" + minutoFinal + ":00";


                                            $('#horaFim').val(resultado);
                                            $('#horaFim').attr("value", resultado);


                                        });
                                    </script>
                                <?php } else {
                                    $readonly = "";
                                }
                                ?>

                                <div class="form-group col-md-3">
                                    <label for="horaFim">Hora Fim*</label> <br>
                                    <input type="time" name="horaFim" class="form-control" id="horaFim" required
                                           value="<?= $ocorrencia['horario_fim'] ?>"
                                           placeholder="hh:mm" <?= $readonly ?>/>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="retiradaIngresso">Retirada de Ingresso *</label>
                                    <select name="retiradaIngresso" id="retiradaIngresso" class="form-control" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("retirada_ingressos", $ocorrencia['retirada_ingresso_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="valor_ingresso">Valor Ingresso*</label> <br>
                                    <input type="text" name="valor_ingresso" class="form-control"
                                           value="<?= dinheiroParaBr($ocorrencia['valor_ingresso']) ?>" required
                                           id="valor_ingresso" maxlength="5"
                                           placeholder="Em reais">
                                </div>
                            </div>

                            <div class="row" id="msgEscondeHora">
                                <div class="form-group col-md-6">
                                    <span style="color: red;">A hora final tem que ser maior que a hora inicial!</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="instituicao">Instituição * </label>
                                    <select class="form-control" name="instituicao" id="instituicao" required>
                                        <option value="">Selecione uma opção...</option>

                                        <?php
                                        geraOpcao("instituicoes", $ocorrencia['instituicao_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <a class="link-adc" target="_blank" href="?perfil=evento&p=adicionar_local">
                                        <button type="button" class="fa fa-plus btn-success pull-right"></button>
                                    </a>
                                    <label for="local">Local *</label>
                                    <select class="form-control" id="local" name="local" required>
                                        <!-- Populando pelo js -->
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <a class="link-adc" target="_blank" href="?perfil=evento&p=adicionar_espaco">
                                        <button type="button" class="fa fa-plus btn-success pull-right"></button>
                                    </a>
                                    <label for="espaco">Espaço *</label>
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
                            <a href="?perfil=evento&p=ocorrencia_lista">
                                <button type="button" class="btn btn-default" name="voltar">Voltar</button>
                            </a>
                            <input type="hidden" name="idOcorrencia" value="<?= $idOcorrencia ?>">
                            <?= isset($idFilme) ? "<input type='hidden' name='idFilme' value='{$idFilme}'>" : '' ?>
                            <button type="submit" name="edita" id="edita" class="btn btn-info pull-right">Gravar
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
                            <label for="instituicoes">Instituição: *</label>
                            <select name="instituicoes" id="instituicoes" class="form-control" required>
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
                                    onchange="insti_local()" required>
                                <option value="">Selecione uma opção...</option>
                                <?php
                                geraOpcao("instituicoes");
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="SelectLocal">Local: </label>
                            <select name="SelectLocal" id="SelectLocal" class="form-control" required>
                                <!-- Populado pelo JS -->
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
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type='button' class='btn btn-success' name="cadastraEspaco" id="cadastraEspaco"
                        onclick="cadastraEspaco()">Solicitar
                </button>
            </div>
        </div>
    </div>
</div>

<div id="ModalDtExec" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Datas de exceção</h4>
            </div>
            <form>
                <div class="modal-body" id="body-datas">
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-md-12">
                            <button type="button" id="btData" class="btn btn-success">Adicionar Data</button>
                        </div>
                    </div>
                    <div class="mensagemData">
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <p>
                                <i class="icon fa fa-ban"></i> Data de exceção deve ser maior que data inicio e menor que a data de encerramento!
                            </p>
                        </div>
                    </div>
                    <div id="datas">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" id="saveData" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>

    var links_adc = document.querySelectorAll(".link-adc")

    let datas = document.querySelector('#datas');
    $(document).ready(function () {
        $('#valor_ingresso').mask('00,00', {reverse: true})
    });


    //function insti_local() {
    //    const urlModal = `<?//=$url?>//`;
    //
    //    var idInstituicaoModal = $('#instituicaoModal').val();
    //
    //    $.post(urlModal, {
    //        instituicao_id: idInstituicaoModal,
    //    })
    //        .done(function (data) {
    //            $('#SelectLocal option').remove();
    //            $('#SelectLocal').append('<option value="">Selecione uma opção...</option>');
    //
    //            for (let local of data) {
    //                $('#SelectLocal').append(`<option value='${local.id}'>${local.local}</option>`).focus();
    //            }
    //        })
    //        .fail(function () {
    //            swal("danger", "Erro ao gravar");
    //        });
    //
    //}

    // function cadastraLocal() {
    //     var instituicao = $("#instituicoes").val();
    //     var local = $("input[name='localModal']").val();
    //     var cep = $("input[name='cep']").val();
    //     var rua = $("input[name='rua']").val();
    //     var num = $("input[name='numero']").val();
    //     var complemento = $("input[name='complemento']").val();
    //     var bairro = $("input[name='bairro']").val();
    //     var cidade = $("input[name='cidade']").val();
    //     var estado = $("input[name='estado']").val();
    //     var zona = $("#zona").val();
    //
    //     $('#modaLocal').slideUp();
    //
    //     $.post('?perfil=evento&p=index', {
    //         cadastraLocal: 1,
    //         instituicao: instituicao,
    //         local: local,
    //         cep: cep,
    //         rua: rua,
    //         numero: num,
    //         complemento: complemento,
    //         bairro: bairro,
    //         cidade: cidade,
    //         estado: estado,
    //         zona: zona
    //     })
    //         .done(function (data) {
    //             let res = $(data).find('#resposta').text();
    //
    //             if (res == 0) {
    //                 swal('Esse local já existe! Procure-o na lista novamente.', '', 'warning')
    //                     .then(() => {
    //                         $('#modaLocal').slideDown('slow');
    //                     });
    //             } else if (res == 1) {
    //                 swal("Solicitação de novo local enviada com sucesso!", "Após o administrador verificar sua solicitação, seja ela aprovada ou não você receberá uma notificação em seu e-mail.", "success")
    //                     .then(() => {
    //                         $('#modaLocal').modal('hide');
    //                     });
    //             } else {
    //                 console.log(res);
    //                 swal("Erro na solicitação! Tente novamente.", "", "danger")
    //                     .then(() => {
    //                         $('#modaLocal').slideDown('slow');
    //                     });
    //             }
    //
    //         })
    //         .fail(function () {
    //             swal("danger", "Erro ao gravar");
    //         });
    // }

    // function cadastraEspaco() {
    //
    //     var local = $("#SelectLocal").val();
    //     var espaco = $("input[name='espaco']").val();
    //
    //     $('#modalEspaco').slideUp();
    //
    //     $.post('?perfil=evento&p=index', {
    //         cadastraEspaco: 1,
    //         espaco: espaco,
    //         local: local
    //     })
    //         .done(function (data) {
    //             let res = $(data).find('#resposta').text();
    //
    //             if (res == 0) {
    //                 swal('Esse espaço já existe! Procure-o na lista novamente.', '', 'warning')
    //                     .then(() => {
    //                         $('#modalEspaco').slideDown('slow');
    //                     });
    //             } else if (res == 1) {
    //                 swal("Solicitação de novo espaço enviada com sucesso!", "Após o administrador verificar sua solicitação, seja ela aprovada ou não você receberá uma notificação em seu e-mail.", "success")
    //                     .then(() => {
    //                         $('#modalEspaco').modal('hide');
    //                     });
    //             } else {
    //                 swal("Erro na solicitação! Tente novamente.", "", "error")
    //                     .then(() => {
    //                         $('#modalEspaco').slideDown('slow');
    //                     });
    //             }
    //         })
    //         .fail(function () {
    //             swal("danger", "Erro ao gravar");
    //         });
    // }

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
        if (instituicao.value == 6) {
            hideOrShow(links_adc, "block")
        } else {
            hideOrShow(links_adc, "none")
        }
        getLocais(instituicao.value, local_id)
    }

    instituicao.addEventListener('change', async e => {
        let idInstituicao = $('#instituicao option:checked').val();
        getLocais(idInstituicao, '')
        getEspacos('', '') // Se alterar o primeiro ele limpa o local e o espaço
        if (idInstituicao != 6) {
            hideOrShow(links_adc, "none");
        } else {
            hideOrShow(links_adc, "block")
        }

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

            $('#local')
                .val(189);
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
        if (retiradaIngresso.value == 2 || retiradaIngresso.value == 7 || retiradaIngresso.value == 5 || retiradaIngresso.value == 11 || retiradaIngresso.value == 1) {
            valorIngressos.value = '0,00';
            valorIngressos.readOnly = true;
        } else {
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

    let msgHora = $('#msgEscondeHora');

    // msgHora.hide();

    function validaHora() {
        let horaInicio = $('#horaInicio').val();
        let horaFim = $('#horaFim').val();

        if (horaFim != "" && horaInicio != "") {
            horaInicio = parseInt(horaInicio.split(":")[0].toString() + horaInicio.split(":")[1].toString());
            horaFim = parseInt(horaFim.split(":")[0].toString() + horaFim.split(":")[1].toString());

            if (horaFim < horaInicio) {
                if (horaFim != 1200) {
                    msgHora.show();
                    $('#edita').attr("disabled", true);
                } else {
                    msgHora.hide();
                    $('#edita').attr("disabled", false);
                }
            } else {
                msgHora.hide();
                $('#edita').attr("disabled", false);
            }
        }
    }

    function criarInputData(valor = 0) {
        let row = document.createElement('div');
        row.classList.add('row');
        row.style.display = 'flex';
        row.style.alignItems = 'end';
        row.style.justifyContent = 'end';

        let col8 = document.createElement('div');
        col8.classList.add('col-md-10');

        let col4 = document.createElement('div');
        col4.classList.add('col-md-2');
        col4.style.marginTop = '5%';

        let remove = document.createElement('button');
        remove.classList.add('btn');
        remove.classList.add('btn-danger');
        remove.classList.add('apData');
        remove.setAttribute('type', 'button')
        remove.setAttribute('onClick', 'removerLinha(this)');

        let icone = document.createElement('i');
        icone.classList.add('fa');
        icone.classList.add('fa-fw');
        icone.classList.add('fa-trash-o');

        let label = document.createElement('label');
        label.textContent = "Data:";

        let input = document.createElement('input');
        input.setAttribute('type', 'date');
        input.setAttribute('onChange','validaData(this)')
        input.classList.add('dataEx');
        input.classList.add('form-control');
        if (valor) {
            input.value = valor;
        }

        remove.appendChild(icone);
        col4.appendChild(remove);

        col8.appendChild(label);
        col8.appendChild(input);

        row.appendChild(col8);
        row.appendChild(col4);

        datas = document.querySelector('#datas');

        datas.appendChild(row);


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

    let btnDataE = document.querySelector('#btData');
    let divDatas = document.querySelector('#datas');

    btnDataE.addEventListener('click', criarInputData);

    let saveDate = document.querySelector('#saveData');
    saveDate.addEventListener("click", function (event) {
        event.preventDefault();
        let data = document.querySelectorAll('.dataEx');
        let datas = [];
        data.forEach(function (item) {
            datas.push(item.value);
        });
        dados = {
            id: <?= $idOcorrencia ?>,
            datas: datas.length == 0 ? 0 : datas
        };
        $.ajax({
            url: '<?= $urlAjax ?>',
            type: 'POST',
            data: dados,
            async: true,
            success: function (response) {
                $('#ModalDtExec').modal('toggle');
                Swal.fire({
                    title: '<strong>Datas cadastradas com sucesso!</strong>',
                    icon: 'success',
                })
            },
            error: function (response) {
                console.log("Deu erro");
            }
        });

    });

    $('#dtExc').click(function () {
        let dados = {
            idOcorrencia: <?= $idOcorrencia ?>,
        };
        $.ajax({
            url: '<?= $urlAjax ?>',
            type: "POST",
            data: dados,
            async: true,
            success: function (response) {
                result = JSON.parse(response);
                resultado = result.map(function (obj) {
                    return Object.keys(obj).map(function (chave) {
                        return obj[chave];
                    })
                });

                document.querySelector('#datas').remove();

                let datas = document.createElement('div');
                datas.id = "datas";

                document.querySelector('#body-datas').appendChild(datas);

                resultado.forEach(function (x) {
                    criarInputData(x);
                })

                $('#ModalDtExec').modal('show');
            },
            error: function (xhr) {

            }
        });
    });

    function removerLinha(btnE) {
        let divPai = btnE.parentNode;
        let divAvo = divPai.parentNode;

        divAvo.remove();
    }

    function hideOrShow(array, acao) {
        for (ob of array) {
            ob.style.display = acao;
        }
    }

    window.onload = function () {
        let instuicao = $('#instituicao').val();
        document.querySelector(".mensagemData").style.display = 'none';

        if (instuicao != 6) {
            hideOrShow(links_adc, "none");
        } else {
            hideOrShow(links_adc, "block");
        }
    }

    function validaData(txtData){
        let dataEx = new Date(txtData.value);
        let dataIni = new Date(document.querySelector('#datepicker10').value);
        let dataEnc = new Date(document.querySelector('#datepicker11').value);
        let mensagem = document.querySelector(".mensagemData");
        let button = document.querySelector("#saveData");

        if (dataEx.getTime() > dataIni.getTime() && dataEx.getTime() < dataEnc.getTime()) {
            mensagem.style.display = "none";
            button.disabled = false;
        }
        else {
            mensagem.style.display = "block";
            button.disabled = true;
        }
    }

</script>