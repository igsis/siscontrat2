<?php
$con = bancoMysqli();

function checarDiaSemana($id)
{
    //funcao para imprimir checked do checkbox
    if ($id != 0) {
        echo "checked";
    }
}

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $idOficina = $_POST['idOficina'] ?? NULL;
    $idAtracao = $_POST['idAtracao'] ?? NULL;
    $modalidade = $_POST['modalidade'] ?? NULL;
    $desc_modalidade = $_POST['desc_modalidade'];
    $data_inicio = $_POST ['data_inicio'];
    $data_fim = $_POST ['data_fim'];
    $segunda = $_POST['segunda'] ?? 0;
    $terca = $_POST['terca'] ?? 0;
    $quarta = $_POST['quarta'] ?? 0;
    $quinta = $_POST['quinta'] ?? 0;
    $sexta = $_POST['sexta'] ?? 0;
    $sabado = $_POST['sabado'] ?? 0;
    $domingo = $_POST['domingo'] ?? 0;
    $valor_hora = dinheiroDeBr($_POST['valor_hora']);
    $carga_horaria = $_POST['carga_horaria'];
    $execucaodia1 = "";
    $execucaodia2 = "";
}

if($segunda != 0){

}elseif ($terca != 0){

}

$sqlModalidade = "INSERT INTO modalidades (         modalidade,
                                                    descricao)
                                                    VALUES(
                                                    '$modalidade',
                                                    '$desc_modalidade' 
                                                    )";
if (mysqli_query($con, $sqlModalidade)) {
    $idModalidade = recuperaDados('modalidades', 'id', $idOficina);
    $sqlPublica = "UPDATE modalidades SET publicado = 1 WHERE id = '$idModalidade'";
}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO oficinas (atracao_id,
                                  modalidade_id, 
                                  data_inicio,
                                  data_fim,
                                  execucao_dia1_id,
                                  execucao_dia2_id,
                                  valor_hora,
                                  carga_horaria) 
                          VALUES ('$idAtracao',
                                   '$idModalidade'
                                  '$data_inicio',
                                  '$data_fim',
                                  '$execucaodia1',
                                  '$execucaodia2',
                                  '$valor_hora',
                                  '$carga_horaria')";

    if (mysqli_query($con, $sql)) {

        $idOficina = recuperaUltimo("oficinas");

        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if (isset($_POST['edita'])) {
    $sql = "UPDATE oficinas SET
                            carga_horaria = '$carga_horaria',
                            data_inicio = '$data_inicio',
                            data_fim = '$data_fim',
                            execucao_dia1_id = '$execucaodia1',
                            execucao_dia2_id = '$execucaodia2',
                            valor_hora = '$valor_hora',
                            WHERE id = '$idOficina'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Gravado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}
if (isset($_POST['carregar'])) {
    $idOficina = $_POST['idOficina'];
}

$oficina = recuperaDados("oficinas", "id", $idOficina);
$modalidade = recuperaDados('modalidades', 'id', $idAtracao);

include "includes/menu_interno.php";

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
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <form method="POST" action="?perfil=evento&p=oficina_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="modalidade">Modalidade:</label>
                                    <input type="text" id="modalidade" name="modalidade" class="form-control"
                                           value="<?= $modalidade['modalidade'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="desc_modalidade">Descrição da Modalidade:</label><br/>
                                    <textarea name="desc_modalidade" id="desc_modalidade" class="form-control"
                                              rows="3"><?= $modalidade['descricao'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="valor_hora">Valor hora/aula: </label><br>
                                    <input class="form-control" style="max-width: 175px;" type="tel" name="valor_hora"
                                           onkeypress="return(moeda(this, '.', ',', event))"
                                           value="<?= dinheiroParaBr($oficina['valor_hora']) ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="carga_horaria">Carga Horária (em horas): </label><br>
                                    <input class="form-control" style="max-width: 175px;" type="number"
                                           name="carga_horaria" value="<?= $oficina['carga_horaria'] ?>"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="data_inicio">Início de inscrição: </label> <br/>
                                    <input class="form-control" style="max-width: 175px;" type="date" name="data_inicio"
                                           value="<?= $oficina['data_inicio'] ?>"
                                           onkeyup="barraData(this);"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="data_fim">Encerramento de inscrição: </label> <br>
                                    <input class="form-control" style="max-width: 175px;" type="date" name="data_fim"
                                           value="<?= $oficina['data_fim'] ?>"/>
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
                                        <input type="checkbox" name="domingo" id="diasemana07"
                                               value="1" <?php checarDiaSemana($oficina['execucao_dia1_id'] || $oficina['execucao_dia2_id'])?>
                                               class="semana"> Domingo
                                        &nbsp;
                                        <input type="checkbox" name="segunda" id="diasemana01"
                                               value="1" <?php checarDiaSemana($oficina['execucao_dia1_id'] || $oficina['execucao_dia2_id'])?>
                                               class="semana"> Segunda
                                        &nbsp;
                                        <input type="checkbox" name="terca" id="diasemana02"
                                               value="1" <?php checarDiaSemana($oficina['execucao_dia1_id'] || $oficina['execucao_dia2_id'])?>
                                               class="semana" > Terça
                                        &nbsp;
                                        <input type="checkbox" name="quarta" id="diasemana03"
                                               value="1" <?php checarDiaSemana($oficina['execucao_dia1_id'] || $oficina['execucao_dia2_id'])?>
                                               class="semana"> Quarta
                                        &nbsp;
                                        <input type="checkbox" name="quinta" id="diasemana04"
                                               value="1" <?php checarDiaSemana($oficina['execucao_dia1_id'] || $oficina['execucao_dia2_id'])?>
                                               class="semana"> Quinta
                                        &nbsp;
                                        <input type="checkbox" name="sexta" id="diasemana05"
                                               value="1" <?php checarDiaSemana($oficina['execucao_dia1_id'] || $oficina['execucao_dia2_id'])?>
                                               class="semana"> Sexta
                                        &nbsp;
                                        <input type="checkbox" name="sabado" id="diasemana06"
                                               value="1" <?php checarDiaSemana($oficina['execucao_dia1_id'] || $oficina['execucao_dia2_id'])?>
                                               class="semana"> Sábado
                                        &nbsp;</label>
                                </div>
                            </div>
                            <div class="row" id="msgEsconde">
                                <div class="form-group col-md-6">
                                    <span style="color: red;">Selecione ao menos um dia da semana!</span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <a href="?perfil=evento&p=atracoes_lista">
                                    <button type="button" class="btn btn-default">Voltar</button>
                                </a>
                                <input type="hidden" name="idOficina" value="<?= $idOficina ?>">
                                <button type="submit" name="edita" class="btn btn-info pull-right">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
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