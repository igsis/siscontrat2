<?php
/**
 * Conteúdo da label "#ocorrencia" do arquivo "vizualizacao_evento.php"
 */

?>

<div class="box box-solid">
    <div class="box-body">
        <div class="box-group" id="accordionOcorrencia">
            <?php
            foreach ($atracoes as $atracao) {
                $sqlOcorrencias = "SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = '$idEvento' AND publicado = '1'";
                $ocorrencias = $con->query($sqlOcorrencias);
                ?>
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordionOcorrencia"
                               href="#ocorrencia<?= $atracao['id'] ?>">
                                Ocorrência da Atração: <?= $atracao['nome_atracao'] ?>
                            </a>
                        </h4>
                    </div>
                    <div class="panel-collapse collapse" id="ocorrencia<?= $atracao['id'] ?>">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <?php
                                    if ($ocorrencias->num_rows > 0) {
                                        $i = 1;
                                        foreach ($ocorrencias as $ocorrencia) {
                                            $retiradaIngresso = recuperaDados('retirada_ingressos', 'id', $ocorrencia['retirada_ingresso_id']);
                                            $instituicao = recuperaDados("instituicoes", "id", $ocorrencia['instituicao_id']);
                                            $local = recuperaDados("locais", "id", $ocorrencia['local_id']);
                                            $espaco = recuperaDados("espacos", "id", $ocorrencia['espaco_id']);
                                            ?>
                                            <tr>
                <th class="text center bg-primary" colspan="2"> Ocorrências
                    #<?= $i ?>  </th>
            </tr>
            <tr>
                <th width="30%"> Data de Início:</th>
                <td><?= exibirDataBr($ocorrencia['data_inicio']) ?></td>
            </tr>
            <tr>
                <th width="30%"> Data de Encerramento:</th>
                <td><?= $ocorrencia['data_fim'] == null ? exibirDataBr($ocorrencia['data_fim']) : "Não é Temporada" ?></td>
            </tr>
            <tr>
                <th width="30%"> Hora de Início:</th>
                <td><?= date("H:i", strtotime($ocorrencia['horario_inicio'])) ?></td>
            </tr>
            <tr>
                <th width="30%"> Hora de Encerramento:</th>
                <td><?= date("H:i", strtotime($ocorrencia['horario_fim'])) ?></td>
            </tr>
            <tr>
                <th width="30%"> Retirada de Ingressos:</th>
                <td><?= $retiradaIngresso['retirada_ingresso'] ?></td>
            </tr>
            <tr>
                <th width="30%"> Valor do Ingresso:</th>
                <td><?= dinheiroParaBr($ocorrencia['valor_ingresso']) ?></td>
            </tr>
            <tr>
                <th width="30%"> Instituição:</th>
                <td><?= $instituicao['nome'] ?></td>
            </tr>
            <tr>
                <th width="30%"> Local:</th>
                <td><?= $local['local'] ?></td>
            </tr>
            <?php if ($ocorrencia['espaco_id'] != 0) { ?>
            <tr>
                <th width="30%"> Espaço:</th>
                <td><?= $espaco['espaco'] ?></td>
            </tr>
            <?php } ?>
            <tr>
                <th width="30%"> Observação:</th>
                <td><?= $ocorrencia['observacao'] ?></td>
            </tr>
            <?php
                                            $i++;
                                        }
                                    } else { ?>
            <tr>
                <th class="text-center bg-danger"> Não há ocorrências inseridas</th>
            </tr>

            <?php } ?>
            </table>
        </div>

        <div class="row" align="center">
            <?php if (isset($mensagem)) {echo $mensagem;}; ?>
        </div>

    </div>
</div>
    </div>
<?php } ?>
        </div>
    </div>
</div>
