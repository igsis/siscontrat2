<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

$idAtracao = $_POST['idAtracao'];
$sqlDias = "SELECT * FROM execucao_dias";
$queryDias = mysqli_query($con, $sqlDias);
?>

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

        <h2 class="page-header">Cadastro de Especificidade</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atração - Especificidades de Área</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=oficina_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="modalidade">Modalidade: *</label>
                                    <input type="text" id="modalidade" name="modalidade" required class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="desc_modalidade">Descrição da Modalidade: *</label><br/>
                                    <textarea name="desc_modalidade" id="desc_modalidade" required class="form-control"
                                              rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="valor_hora">Valor hora/aula: *</label><br>
                                    <input class="form-control" style="max-width: 175px;" type="tel" required name="valor_hora"
                                           onkeypress="return(moeda(this, '.', ',', event))">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="carga_horaria">Carga Horária (em horas): *</label><br>
                                    <input class="form-control" style="max-width: 175px;" type="number" required
                                           name="carga_horaria" min="0">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="data_inicio">Início de inscrição: *</label> <br/>
                                    <input class="form-control semana" style="max-width: 175px;" type="date"
                                           name="data_inicio" required
                                           onkeyup="barraData(this);" onblur="validate()" id="datepicker10">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="data_fim">Encerramento de inscrição: *</label> <br>
                                    <input class="form-control semana" style="max-width: 175px;" type="date"
                                           name="data_fim" required
                                           onblur="validate()" id="datepicker11">
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
                                    <select name="idDia1" id="dia1" class="form-control" required>
                                        <option>Selecione o dia...</option>
                                        <?php
                                            geraOpcaoParcelas('execucao_dias')
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">

                                    <label>Selecione o segundo dia de execução: *</label>
                                    <select name="idDia2" id="dia2" class="form-control" required>
                                        <option>Selecione o dia...</option>
                                        <?php
                                             geraOpcaoParcelas('execucao_dias')
                                        ?>

                                    </select>
                                </div>
                            </div>

                            <div class="row" id="msgEscondeDias">
                                <div class="form-group col-md-6">
                                    <span style="color: red;"><b>Os dias de execução escolhidos são iguais!</b></span>
                                </div>
                            </div>

                        </div>

                        <div class="box-footer">
                            <a href="?perfil=evento&p=atracoes_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" name="idAtracao" id="idAtracao" value="<?= $idAtracao ?>">
                            <button type="submit" id="cadastra" name="cadastra" class="btn btn-info pull-right">
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
    let dia1 = $('#dia1');
    let dia2 = $('#dia2');
    let botao = $('#cadastra');
    var isMsgDia = $('#msgEscondeDias');
    isMsgDia.hide();
    function igual() {
        if (dia1.val() == "Selecione o dia..." || dia2.val() == "Selecione o dia...") {
            botao.prop('disabled', true)
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