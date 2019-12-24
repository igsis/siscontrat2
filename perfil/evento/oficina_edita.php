<?php
$con = bancoMysqli();



if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $idAtracao = $_POST['idAtracao'];
    $modalidade = $_POST['modalidade'];
    $data_inicio = $_POST ['data_inicio'];
    $data_fim = $_POST ['data_fim'];
    $execucaodia1 = $_POST['idDia1'];
    $execucaodia2 = $_POST['idDia2'];
    $valor_hora = dinheiroDeBr($_POST['valor_hora']);
    $carga_horaria = $_POST['carga_horaria'];
}

if (isset($_POST['cadastra'])) {
    $sqlInsertOficinas = "INSERT INTO oficinas (atracao_id,
                                  modalidade_id, 
                                  data_inicio,
                                  data_fim,
                                  execucao_dia1_id,
                                  execucao_dia2_id,
                                  valor_hora,
                                  carga_horaria) 
                          VALUES ('$idAtracao',
                                  '$modalidade',
                                  '$data_inicio',
                                  '$data_fim',
                                  '$execucaodia1',
                                  '$execucaodia2',
                                  '$valor_hora',
                                  '$carga_horaria')";
    if (mysqli_query($con, $sqlInsertOficinas)) {

        $idOficina = recuperaUltimo("oficinas");

        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if (isset($_POST['edita'])) {
    $idOficina = $_POST['idOficina'];

    $sqlUpdateOficina = "UPDATE oficinas SET
                            modalidade_id = '$modalidade',
                            carga_horaria = '$carga_horaria',
                            data_inicio = '$data_inicio',
                            data_fim = '$data_fim',
                            execucao_dia1_id = '$execucaodia1',
                            execucao_dia2_id = '$execucaodia2',
                            valor_hora = '$valor_hora'
                            WHERE id = '$idOficina'";

    if (mysqli_query($con, $sqlUpdateOficina)) {
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
$idAtracao = $oficina['atracao_id'];
include "includes/menu_interno.php";

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

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Edição de Especificidade</h2>

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
                    <?php

                    $idModalidade = $oficina['modalidade_id'];
                    $execucaodia1 = $oficina['execucao_dia1_id'];
                    $execucaodia2 = $oficina['execucao_dia2_id'];
                    $modalidade = recuperaDados('modalidades', 'id', $idModalidade);
                    ?>
                    <form method="POST" action="?perfil=evento&p=oficina_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="modalidade">Modalidade: *</label> <button class='btn btn-default btn-sm' type='button' data-toggle='modal' data-target='#infoModalidade' style="border-radius: 15px;"><i class="fa fa-question-circle"></i></button>
                                    <select id="modalidade" name="modalidade" required class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php geraOpcao('modalidades', $oficina['modalidade_id']); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="valor_hora">Valor hora/aula: *</label><br>
                                    <input class="form-control" style="max-width: 175px;" type="tel" name="valor_hora" required
                                           onkeypress="return(moeda(this, '.', ',', event))"
                                           value="<?= dinheiroParaBr($oficina['valor_hora']) ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="carga_horaria">Carga Horária (em horas): *</label><br>
                                    <input class="form-control" style="max-width: 175px;" type="number" required
                                           name="carga_horaria" min="1" value="<?= $oficina['carga_horaria'] ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="data_inicio">Início de inscrição: *</label> <br/>
                                    <input class="form-control semana" style="max-width: 175px;" type="date"
                                           name="data_inicio" required
                                           value="<?= $oficina['data_inicio'] ?>"
                                           onkeyup="barraData(this);" onblur="validate()" id="datepicker10">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="data_fim">Encerramento de inscrição: *</label> <br>
                                    <input class="form-control semana" style="max-width: 175px;" type="date"
                                           name="data_fim" required
                                           value="<?= $oficina['data_fim'] ?>" onblur="validate()" id="datepicker11">
                                </div>
                            </div>

                            <div class="row" id="msgEscondeData">
                                <div class="form-group col-md-6">
                                    <span style="color: red;"><b>Data de encerramento deve ser maior que a data inicial</b></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">

                                    <label>Selecione o primeiro dia de execução: *</label>
                                    <select required name="idDia1" id="dia1" class="form-control">
                                        <?php
                                            geraOpcaoParcelas('execucao_dias', $oficina['execucao_dia1_id'])
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Selecione o segundo dia de execução: *</label>
                                    <select required name="idDia2" id="dia2" class="form-control">
                                        <?php
                                            geraOpcaoParcelas('execucao_dias', $oficina['execucao_dia2_id'])
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row" id="msgEscondeDias">
                                <div class="form-group col-md-6">
                                    <span style="color: red;"><b>Os dias de execução escolhidos são iguais!</b></span>
                                </div>
                            </div>

                            <div class="box-footer">
                                <a href="?perfil=evento&p=atracoes_lista">
                                    <button type="button" class="btn btn-default">Voltar</button>
                                </a>
                                <input type="hidden" name="idAtracao" value="<?= $idAtracao ?>">
                                <input type="hidden" name="idOficina" value="<?= $idOficina ?>">
                                <button type="submit" id="edita" name="edita" class="btn btn-info pull-right">Salvar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="infoModalidade" role="dialog" aria-labelledby="infoModalidadeLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Informações para a Escolha da Modalidade</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tbody>
                            <?php geraModalDescritivo('modalidades', true); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    let dia1 = $('#dia1');
    let dia2 = $('#dia2');
    let botao = $('#cadastra');
    var isMsgDia = $('#msgEscondeDias');
    isMsgDia.hide();

    function igual() {
        if (dia1.val() == "Selecione o dia..." || dia2.val() == "Selecione o dia...") {
            botao.prop('disabled', true);
        } else {
            botao.prop('disabled', false);
            if (dia1.val() == dia2.val()) {
                botao.prop('disabled', true);
                isMsgDia.show();
            } else {
                botao.prop('disabled', false);
                isMsgDia.hide();
            }
        }
    }

    dia2.on('change', igual);
    dia1.on('change', igual);

    $(document).ready(igual)
</script>