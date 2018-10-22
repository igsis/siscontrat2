<?php
$con = bancoMysqli();

$evento = recuperaDados('eventos', 'id', $_SESSION['idEvento']);

include "includes/menu_interno.php";

if (isset($_POST['cadastra']) || isset($_POST['edita'])){

    $tipoOcorrencia =  $evento['tipo_evento_id'];
    $origemOcorrencia = $_POST['idAtracao'];
    $instituicaoId = $_POST['instituicao'];
    $localId = $_POST['local'];
    $espacoId =  $_POST['espaco'];
    $dataInicio = $_POST['dataInicio'];
    $dataFim = isset($_POST['dataFim']) ? $_POST['dataFim'] : NULL;
    $segunda = isset($_POST['segunda']) ? $_POST['segunda'] : 0;
    $terca = isset($_POST['terca']) ? $_POST['terca'] : 0;
    $quarta = isset($_POST['quarta']) ? $_POST['quarta'] : 0;
    $quinta = isset($_POST['quinta']) ? $_POST['quinta'] : 0;
    $sexta = isset($_POST['sexta']) ? $_POST['sexta'] : 0;
    $sabado = isset($_POST['sabado']) ? $_POST['sabado'] : 0;
    $domingo = isset($_POST['domingo']) ? $_POST['domingo'] : 0;
    $horarioInicio = $_POST['horaInicio'];
    $horarioFim = $_POST['horaFim'];
    $retiradaIngressoId = $_POST['retiradaIngresso'];
    $valorIngresso = $_POST['valorIngresso'];
    $observacao = isset($_POST['observacao']) ? addslashes($_POST['observacao']) : NULL;

}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO ocorrencias (tipo_ocorrencia_id,
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
                                 observacao)
                          VALUES ('$tipoOcorrencia',
                                  '$origemOcorrencia',
                                  '$instituicaoId',
                                  '$localId',
                                  '$espacoId',
                                  '$dataInicio',
                                  '$dataFim',
                                  '$segunda',
                                  '$terca',
                                  '$quarta',
                                  '$quinta',
                                  '$sexta',
                                  '$sabado',
                                  '$domingo',
                                  '$horarioInicio',
                                  '$horarioFim',
                                   '$retiradaIngressoId',
                                  '$valorIngresso',
                                  '$observacao')";
    echo $sql;
    if(mysqli_query($con, $sql))
    {
        $mensagem = mensagem("success","Cadastrado com sucesso!");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if(isset($_POST['edita'])){
    $idEvento = $_POST['idEvento'];
    $sql = "UPDATE eventos SET
                            instituicao_id = '$instituicaoId',
                            local_id = '$localId',
                            espaco_id = '$espacoId',
                            data_inicio = '$dataInicio',
                            data_fim = '$dataFim',
                            segunda = '$segunda',
                            terca = '$terca',
                            quarta = '$quarta',
                            quinta = '$quinta',
                            sexta = '$sexta',
                            sabado = '$sabado',
                            domingo = '$domingo',
                            horario_inicio = '$horarioInicio',
                            horario_fim = '$horarioFim',
                            retirada_ingresso_id = '$retiradaIngressoId',
                            valor_ingresso = '$valorIngresso',
                            observacao = '$observacao')
                            WHERE id = '$idOcorrencia'";
    If(mysqli_query($con,$sql)){
        $mensagem = mensagem("success","Gravado com sucesso!");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if(isset($_POST['carregar'])){
    $idEvento = $_POST['idEvento'];
    $_SESSION['idEvento'] = $idEvento;
}

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
                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>
                    <form method="POST" action="?perfil=evento&p=ocorrencia_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="dataInicio">Data Início*</label> <br>
                                    <input type="date" name="dataInicio" class="form-control" id="datepicker10" placeholder="DD/MM/AAAA" required value="<?= $dataInicio ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="dataFim">Data Encerramento (apenas se for temporada)</label> <br>
                                    <input type="date" name="dataFim" class="form-control" id="datepicker10" value="<?= isset($horarioFim) ? $horarioFim : NULL ?>" placeholder="DD/MM/AAAA">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>
                                        <input type="checkbox" name="domingo" id="domingo" value="1" <?= $domingo == 1 ? 'checked' : NULL ?>> Domingo &nbsp;
                                        <input type="checkbox" name="segunda" id="segunda" value="1" <?= $segunda == 1 ? 'checked' : NULL ?>> Segunda &nbsp;
                                        <input type="checkbox" name="terca" id="terca" value="1" <?= $terca == 1 ? 'checked' : NULL ?>> Terça &nbsp;
                                        <input type="checkbox" name="quarta" id="quarta" value="1" <?= $quarta == 1 ? 'checked' : NULL ?>> Quarta &nbsp;
                                        <input type="checkbox" name="quinta" id="quinta" value="1" <?= $quinta == 1 ? 'checked' : NULL ?>> Quinta &nbsp;
                                        <input type="checkbox" name="sexta" id="sexta" value="1" <?= $sexta == 1 ? 'checked' : NULL ?>> Sexta &nbsp;
                                        <input type="checkbox" name="sabado" id="sabado" value="1" <?= $sabado == 1 ? 'checked' : NULL ?>> Sábado &nbsp;
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="horaInicio">Hora de Início*</label> <br>
                                    <input type="time" name="horaInicio" class="form-control" id="horaInicio" value="<?= $horarioInicio ?>" required placeholder="hh:mm"/>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="horaFim">Hora Fim*</label> <br>
                                    <input type="time" name="horaFim" class="form-control" id="horaFim" required value="<?= $horarioFim ?>" placeholder="hh:mm"/>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="retiradaIngresso">Retirada de Ingresso</label>
                                    <select name="retiradaIngresso" id="retiradaIngresso" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("retirada_ingressos", $retiradaIngressoId);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="valorIngresso">Valor Ingresso*</label> <br>
                                    <input type="text" name="valorIngresso" class="form-control" value="<?= $valorIngresso ?>" required id="valorIngresso"
                                           placeholder="Em reais"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="instituicao">Instituição</label>
                                    <select class="form-control" name="instituicao" id="instituicao">
                                        <option value="">Selecione uma opção...</option>

                                        <?php
                                        geraOpcao("instituicoes", $instituicaoId);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="local">Local</label>
                                    <select class="form-control" id="local" name="local">
                                        <option value="">Selecione uma opção...</option>

                                        <?php
                                        geraOpcaoPublicado("locais", $localId);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="espaco">Espaço</label>
                                    <select class="form-control" id="espaco" name="espaco">
                                        <option value="">Selecione uma opção...</option>
                                        <option value="1">TESTE</option>
                                        <?php
                                        geraOpcaoPublicado("espacos", $espacoId);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="observacao">Observação</label><br/>
                                <textarea name="observacao" id="observacao" class="form-control" rows="5"><?= isset($observacao) ? $observacao : NULL ?></textarea>
                            </div>

                        </div>


                        <div class="box-footer">
                            <button type="submit" class="btn btn-default">Cancelar</button>
                            <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                            <button type="submit" name="edita" class="btn btn-info pull-right">Gravar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
