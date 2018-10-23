<?php
$con = bancoMysqli();

$evento = recuperaDados('eventos', 'id', $_SESSION['idEvento']);

include "includes/menu_interno.php";

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {

    $tipo_evento_id = $evento['tipo_evento_id'];
    $origem_ocorrencia_id = isset($_POST['idAtracao']) ? $_POST['idAtracao'] : NULL;
    $instituicao_id = $_POST['instituicao'];
    $local_id = $_POST['local'];
    $espaco_id = $_POST['espaco'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = isset($_POST['data_fim']) ? $_POST['data_fim'] : NULL;
    $segunda = isset($_POST['segunda']) ? $_POST['segunda'] : 0;
    $terca = isset($_POST['terca']) ? $_POST['terca'] : 0;
    $quarta = isset($_POST['quarta']) ? $_POST['quarta'] : 0;
    $quinta = isset($_POST['quinta']) ? $_POST['quinta'] : 0;
    $sexta = isset($_POST['sexta']) ? $_POST['sexta'] : 0;
    $sabado = isset($_POST['sabado']) ? $_POST['sabado'] : 0;
    $domingo = isset($_POST['domingo']) ? $_POST['domingo'] : 0;
    $horario_inicio = $_POST['horaInicio'];
    $horario_fim = $_POST['horaFim'];
    $retirada_ingresso_id = $_POST['retiradaIngresso'];
    $valor_ingresso = $_POST['valor_ingresso'];
    $observacao = isset($_POST['observacao']) ? addslashes($_POST['observacao']) : NULL;
    $idOcorrencia = isset($_POST['idOcorrencia']) ? $_POST['idOcorrencia'] : NULL;

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
                                  '$observacao')";
    echo $sql;
    if (mysqli_query($con, $sql)) {
        $idOcorrencia = recuperaUltimo('ocorrencias');

        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
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
                            observacao = '$observacao'
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

if (isset($_POST['cadastra'])) {
    $idOcorrencia = recuperaUltimo('ocorrencias');
}

$ocorrencia = recuperaDados('ocorrencias', 'id', $idOcorrencia);

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
                                           placeholder="DD/MM/AAAA" required value="<?= $ocorrencia['data_inicio'] ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="data_fim">Data Encerramento (apenas se for temporada)</label> <br>
                                    <input type="date" name="data_fim" class="form-control" id="datepicker10"
                                           value="<?= isset($ocorrencia['horario_fim']) ? $ocorrencia['horario_fim'] : NULL ?>"
                                           placeholder="DD/MM/AAAA">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>
                                        <input type="checkbox" name="domingo" id="domingo"
                                               value="1" <?= $ocorrencia['domingo'] == 1 ? 'checked' : NULL ?>> Domingo
                                        &nbsp;
                                        <input type="checkbox" name="segunda" id="segunda"
                                               value="1" <?= $ocorrencia['segunda'] == 1 ? 'checked' : NULL ?>> Segunda
                                        &nbsp;
                                        <input type="checkbox" name="terca" id="terca"
                                               value="1" <?= $ocorrencia['terca'] == 1 ? 'checked' : NULL ?>> Terça
                                        &nbsp;
                                        <input type="checkbox" name="quarta" id="quarta"
                                               value="1" <?= $ocorrencia['quarta'] == 1 ? 'checked' : NULL ?>> Quarta
                                        &nbsp;
                                        <input type="checkbox" name="quinta" id="quinta"
                                               value="1" <?= $ocorrencia['quinta'] == 1 ? 'checked' : NULL ?>> Quinta
                                        &nbsp;
                                        <input type="checkbox" name="sexta" id="sexta"
                                               value="1" <?= $ocorrencia['sexta'] == 1 ? 'checked' : NULL ?>> Sexta
                                        &nbsp;
                                        <input type="checkbox" name="sabado" id="sabado"
                                               value="1" <?= $ocorrencia['sabado'] == 1 ? 'checked' : NULL ?>> Sábado
                                        &nbsp;
                                    </label>
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
                                           value="<?= $ocorrencia['valor_ingresso'] ?>" required id="valor_ingresso"
                                           placeholder="Em reais"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="instituicao">Instituição</label>
                                    <select class="form-control" name="instituicao" id="instituicao">
                                        <option value="">Selecione uma opção...</option>

                                        <?php
                                        geraOpcao("instituicoes", $ocorrencia['instituicao_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="local">Local</label>
                                    <select class="form-control" id="local" name="local">
                                        <option value="">Selecione uma opção...</option>

                                        <?php
                                        geraOpcao("locais", $ocorrencia['local_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="espaco">Espaço</label>
                                    <select class="form-control" id="espaco" name="espaco">
                                        <option value="">Selecione uma opção...</option>

                                        <?php
                                        geraOpcao("espacos", $ocorrencia['espaco_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="observacao">Observação</label><br/>
                                <textarea name="observacao" id="observacao" class="form-control"
                                          rows="5"><?= isset($ocorrencia['observacao']) ? $ocorrencia['observacao'] : NULL ?></textarea>
                            </div>

                        </div>


                        <div class="box-footer">
                            <button type="submit" class="btn btn-default">Cancelar</button>
                            <input type="hidden" name="idOcorrencia" value="<?= $idOcorrencia ?>">
                            <button type="submit" name="edita" class="btn btn-info pull-right">Gravar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

