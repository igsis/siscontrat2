<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

$evento = recuperaDados('eventos', 'id', $idEvento);
?>

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
                                    <label for="dataInicio">Data Início*</label> <br>
                                    <input type="date" name="dataInicio" class="form-control" id="datepicker10" required placeholder="DD/MM/AAAA">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="dataFim">Data Encerramento (apenas se for temporada)</label> <br>
                                    <input type="date" name="dataFim" class="form-control" id="datepicker10" placeholder="DD/MM/AAAA">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>
                                        <input type="checkbox" name="domingo" id="domingo" value="1"> Domingo &nbsp;
                                        <input type="checkbox" name="segunda" id="segunda" value="1"> Segunda &nbsp;
                                        <input type="checkbox" name="terca" id="terca" value="1"> Terça &nbsp;
                                        <input type="checkbox" name="quarta" id="quarta" value="1"> Quarta &nbsp;
                                        <input type="checkbox" name="quinta" id="quinta" value="1"> Quinta &nbsp;
                                        <input type="checkbox" name="sexta" id="sexta" value="1"> Sexta &nbsp;
                                        <input type="checkbox" name="sabado" id="sabado" value="1"> Sábado &nbsp;
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="horaInicio">Hora de Início*</label> <br>
                                    <input type="time" name="horaInicio" class="form-control" id="horaInicio" required placeholder="hh:mm"/>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="horaFim">Hora Fim*</label> <br>
                                    <input type="time" name="horaFim" class="form-control" id="horaFim" required placeholder="hh:mm"/>
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
                                    <label for="valorIngresso">Valor Ingresso*</label> <br>
                                    <input type="text" name="valorIngresso" class="form-control" required id="valorIngresso"
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
                            <input type="text" name="idAtracao" value="<?= $_POST['idAtracao'] ?>">
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
