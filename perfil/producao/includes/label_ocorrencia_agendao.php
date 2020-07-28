<?php

/**
 * Conteúdo da label "#ocorrencia" do arquivo "producao/agendao/verificados.php"
 */

?>
<div class="box box-solid">
    <div class="box-body">
        <div class="box-group" id="accordionOcorrencia">
            <?php
            $idEvento = $_SESSION['idEvento'];
            $sqlOcorrencia = "SELECT * FROM agendao_ocorrencias WHERE origem_ocorrencia_id = '".$idEvento."'  AND tipo_ocorrencia_id = 3 AND publicado = '1'";
            $ocorrencias = $con->query($sqlOcorrencia);
            $aberto = "in";
            ?>
            <div class="panel box box-primary">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table">
                        <?php
                        if ($ocorrencias->num_rows > 0) {
                            $i = 1;
                            if ($i != 1){
                                $aberto = "";
                            }
                            foreach ($ocorrencias as $ocorrencia) {
                                $retiradaIngresso = recuperaDados('retirada_ingressos', 'id', $ocorrencia['retirada_ingresso_id'])['retirada_ingresso'];
                                $instituicao = recuperaDados('instituicoes', 'id', $ocorrencia['instituicao_id'])['nome'];
                                $local = recuperaDados('locais', 'id', $ocorrencia['local_id'])['local'];
                                $espaco = recuperaDados('espacos', 'id', $ocorrencia['espaco_id'])['espaco'] ?? NULL;
                                $periodo = recuperaDados("periodos","id",$ocorrencia['periodo_id']);
                                $subprefeitura = recuperaDados("subprefeituras","id",$ocorrencia['subprefeitura_id']);
                                ?>
                                <th class="text center bg-primary" colspan="2"> Ocorrências
                                    #<?= $i ?>  </th>
                                </tr>
                                <tr>
                                    <th width="30%"> Data de Início:</th>
                                    <td><?= exibirDataBr($ocorrencia['data_inicio']) ?></td>
                                </tr>
                                <tr>
                                    <th width="30%"> Data de Encerramento:</th>
                                    <td><?= $ocorrencia['data_fim'] == "0000-00-00" ? "Não é Temporada" : exibirDataBr($ocorrencia['data_fim']) ?></td>
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
                                    <th width="30%">Libras:</th>
                                    <td><?php
                                        if($ocorrencia['libras'] == 1){
                                            echo "Sim";
                                        }else{
                                            echo "Não";
                                        }
                                        ?></td>
                                </tr>
                                <tr>
                                    <th width="30%">Audiodescrição:</th>
                                    <td><?php
                                        if($ocorrencia['audiodescricao'] == 1){
                                            echo "Sim";
                                        }else{
                                            echo "Não";
                                        }
                                        ?></td>
                                </tr>
                                <tr>
                                    <th width="30%"> Retirada de Ingressos:</th>
                                    <td><?=$retiradaIngresso?></td>
                                </tr>
                                <tr>
                                    <th width="30%"> Valor do Ingresso:</th>
                                    <td><?= "R$" . dinheiroParaBr($ocorrencia['valor_ingresso']) ?></td>
                                </tr>
                                <tr>
                                    <th width="30%"> Local:</th>
                                    <td><?= $local ?></td>
                                </tr>
                                <?php if ($ocorrencia['espaco_id'] != 0) { ?>
                                    <tr>
                                        <th width="30%"> Espaço:</th>
                                        <td><?= $espaco ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <th width="30%"> Observação:</th>
                                    <td><?= $ocorrencia['observacao'] == "" ? "Não cadastrado" : $ocorrencia['observacao'] ?></td>
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
    </div>
</div>

