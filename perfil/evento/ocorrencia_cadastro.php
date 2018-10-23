<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

$evento = recuperaDados('eventos', 'id', $idEvento);
?>
<script type="text/javascript">
    $(document).ready(function ()
    {
        validate();
        $('#datepicker11').change(validate);
    });
    function validate()
    {
        if ($('#datepicker11').val().length > 0)
        {
            $("#diasemana01").prop("disabled", false);
            $("#diasemana02").prop("disabled", false);
            $("#diasemana03").prop("disabled", false);
            $("#diasemana04").prop("disabled", false);
            $("#diasemana05").prop("disabled", false);
            $("#diasemana06").prop("disabled", false);
            $("#diasemana07").prop("disabled", false);
        }
        else
        {
            $("#diasemana01").prop("disabled", true);
            $("#diasemana02").prop("disabled", true);
            $("#diasemana03").prop("disabled", true);
            $("#diasemana04").prop("disabled", true);
            $("#diasemana05").prop("disabled", true);
            $("#diasemana06").prop("disabled", true);
            $("#diasemana07").prop("disabled", true);
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
                                           required placeholder="DD/MM/AAAA" onblur="arrumaData()">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="data_fim">Data Encerramento (apenas se for temporada)</label> <br>
                                    <input type="date" name="data_fim" class="form-control" id="datepicker11"
                                           placeholder="DD/MM/AAAA" onblur="validate()">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
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
                                        geraOpcao("retirada_ingressos", "");
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="valor_ingresso">Valor Ingresso*</label> <br>
                                    <input type="text" name="valor_ingresso" class="form-control" required
                                           id="valor_ingresso"
                                           placeholder="Em reais"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="instituicao">Instituição</label>
                                    <select class="form-control" name="instituicao" id="instituicao">
                                        <option value="">Selecione uma opção...</option>

                                        <?php
                                        geraOpcao("instituicoes", "");
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="local">Local</label>
                                    <select class="form-control" id="local" name="local">
                                        <option value="">Selecione uma opção...</option>

                                        <?php
                                        geraOpcao("locais", "");
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="espaco">Espaço</label>
                                    <select class="form-control" id="espaco" name="espaco">
                                        <option value="">Selecione uma opção...</option>

                                        <?php
                                        geraOpcao("espacos", "");
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
                            <button type="submit" class="btn btn-default">Cancelar</button>
                            <input type="hidden" name="idAtracao" value="<?= $_POST['idAtracao'] ?>">
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
