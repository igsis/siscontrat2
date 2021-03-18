<?php
/**
 * Conteúdo da label "#ocorrencia" do arquivo "finalizar.php"
 */

?>

<div class="box box-solid">
    <div class="box-body">
        <div class="box-group" id="accordionOcorrencia">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <?php
            if ($evento['tipo_evento_id'] == 1) {
                foreach ($atracoes as $atracao) {
                    $sqlOcorrencia = "SELECT * FROM ocorrencias
                                      WHERE atracao_id = '{$atracao['id']}'
                                      AND tipo_ocorrencia_id = '1'
                                      AND origem_ocorrencia_id = '{$evento['id']}'
                                      AND publicado = '1'";
                    $ocorrencias = $con->query($sqlOcorrencia);
                    ?>
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordionOcorrencia"
                                   href="#ocorrencia<?= $atracao['id'] ?>">
                                    Ocorrências da Atração: <?= $atracao['nome_atracao'] ?>
                                </a>
                            </h4>
                        </div>
                        <div id="ocorrencia<?= $atracao['id'] ?>" class="panel-collapse collapse">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <?php
                                        if ($ocorrencias->num_rows > 0) {
                                            $i = 1;
                                            foreach ($ocorrencias as $ocorrencia) {
                                                $retiradaIngresso = recuperaDados('retirada_ingressos', 'id', $ocorrencia['retirada_ingresso_id'])['retirada_ingresso'];
                                                $instituicao = recuperaDados('instituicoes', 'id', $ocorrencia['instituicao_id'])['nome'];
                                                $local = recuperaDados('locais', 'id', $ocorrencia['local_id'])['local'];
                                                if ($ocorrencia['espaco_id'] != 0) {
                                                    $espaco = recuperaDados('espacos', 'id', $ocorrencia['espaco_id'])['espaco'];
                                                }
                                                $subPrefeitura = recuperaDados('subprefeituras', 'id', $ocorrencia['subprefeitura_id']);
                                                $periodo = recuperaDados('periodos', 'id', $ocorrencia['periodo_id']);

                                                //testa e se necessário retorna as datas de exceção
                                                $datas = "";
                                                $testaExcecao = $con->query("SELECT * FROM ocorrencia_excecoes WHERE atracao_id = " . $ocorrencia['id']);
                                                if ($testaExcecao->num_rows > 0) {
                                                    while ($excessoesArray = mysqli_fetch_array($testaExcecao)) {
                                                        $datas = $datas . exibirDataBr($excessoesArray['data_excecao']) . ", ";
                                                    }
                                                    $datas = substr($datas, 0, -2);
                                                }
                                                ?>
                                                <tr>
                                                    <th class="text-center bg-primary" colspan="2">Ocorrência
                                                        #<?= $i ?></th>
                                                </tr>
                                                <tr>
                                                    <th width="30%">Data de Inicio:</th>
                                                    <td><?= exibirDataBr($ocorrencia['data_inicio']) ?></td>
                                                </tr>
                                                <?php
                                                if ($ocorrencia['virada'] != 1) {
                                                    ?>
                                                    <tr>
                                                        <th width="30%">Data de Encerramento:</th>
                                                        <td><?= $ocorrencia['data_fim'] == "0000-00-00" ? "Não é Temporada" : exibirDataBr($ocorrencia['data_fim']) ?></td>
                                                    </tr>

                                                    <?php
                                                    if ($datas != ""): ?>
                                                        <tr>
                                                            <th width="30%">Data de Exceção:</th>
                                                            <td><?= $datas ?></td>
                                                        </tr>
                                                    <?php endif; ?>

                                                    <tr>
                                                        <th width="30%">Hora de Início:</th>
                                                        <td><?= date("H:i", strtotime($ocorrencia['horario_inicio'])) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th width="30%">Hora de Encerramento:</th>
                                                        <td><?= date("H:i", strtotime($ocorrencia['horario_fim'])) ?></td>
                                                    </tr>

                                                    <?php
                                                }
                                                ?>
                                                <tr>
                                                    <th width="30%">Período:</th>
                                                    <td><?= $periodo['periodo'] ?></td>
                                                </tr>

                                                <tr>
                                                    <th width="30%">Retirada de Ingresso:</th>
                                                    <td><?= $retiradaIngresso ?></td>
                                                </tr>
                                                <?php
                                                if ($ocorrencia['retirada_ingresso_id'] != 2) {
                                                    ?>
                                                    <tr>
                                                        <th width="30%">Valor do Ingresso:</th>
                                                        <td><?= "R$" . dinheiroParaBr($ocorrencia['valor_ingresso']) ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                <tr>
                                                    <th width="30%">Libras:</th>
                                                    <td><?= $ocorrencia['libras'] == 1 ? "Sim" : "Não" ?></td>
                                                </tr>
                                                <tr>
                                                    <th width="30%">Audiodescrição:</th>
                                                    <td><?= $ocorrencia['audiodescricao'] == 1 ? "Sim" : "Não" ?></td>
                                                </tr>
                                                <tr>
                                                    <th width="30%">Subprefeitura:</th>
                                                    <td><?= $subPrefeitura['subprefeitura'] ?></td>
                                                </tr>
                                                <?php
                                                if ($ocorrencia['instituicao_id'] != 10) {
                                                    ?>
                                                    <tr>
                                                        <th width="30%">Instituição:</th>
                                                        <td><?= $instituicao ?></td>
                                                    </tr>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <th width="30%">É virada?</th>
                                                        <td>Sim</td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                <tr>
                                                    <th width="30%">Local:</th>
                                                    <td><?= $local ?></td>
                                                </tr>
                                                <?php if ($ocorrencia['espaco_id'] != 0) { ?>
                                                    <tr>
                                                        <th width="30%">Espaço:</th>
                                                        <td><?= $espaco ?? "Não há espaços para este local" ?></td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <th width="30%">Observação:</th>
                                                    <td><?= checaCampo($ocorrencia['observacao']) ?></td>
                                                </tr>
                                                <?php
                                                $i++;
                                            }
                                        } else { ?>
                                            <tr>
                                                <th class="text-center bg-danger" colspan="2">Não há ocorrências
                                                    inseridas
                                                </th>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                </div>

                                <div class="row" align="center">
                                    <?php if (isset($mensagem)) {
                                        echo $mensagem;
                                    }; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else {
                foreach ($filmes as $filme) {
                    $idFilme = $filme['id'];
                    $idFilmeEvento = $filme['idFilmeEvento'];
                    $sqlOcorrencia = "SELECT * FROM ocorrencias oco 
                                      INNER JOIN filme_eventos fe ON fe.evento_id = oco.origem_ocorrencia_id 
                                      WHERE fe.filme_id = '$idFilme' AND oco.publicado = 1 AND oco.tipo_ocorrencia_id = 2 AND fe.evento_id = $idEvento AND oco.atracao_id = $idFilmeEvento";
                    $ocorrencias = $con->query($sqlOcorrencia);
                    $numOco = $ocorrencias->num_rows;
                    ?>
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordionOcorrencia"
                                   href="#ocorrencia<?= $filme['idFilmeEvento'] ?>">
                                    Ocorrências do Filme: <?= $filme['titulo'] ?>
                                </a>
                            </h4>
                        </div>
                        <div id="ocorrencia<?= $filme['idFilmeEvento'] ?>" class="panel-collapse collapse">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <?php
                                        if ($numOco != 0) {
                                            $i = 1;
                                            foreach ($ocorrencias as $ocorrencia) {
                                                $retiradaIngresso = recuperaDados('retirada_ingressos', 'id', $ocorrencia['retirada_ingresso_id'])['retirada_ingresso'];
                                                $instituicao = recuperaDados('instituicoes', 'id', $ocorrencia['instituicao_id'])['nome'];
                                                $local = recuperaDados('locais', 'id', $ocorrencia['local_id'])['local'];
                                                $espaco = recuperaDados('espacos', 'id', $ocorrencia['espaco_id'])['espaco'] ?? NULL;
                                                $subPrefeitura = recuperaDados('subprefeituras', 'id', $ocorrencia['subprefeitura_id']);
                                                $periodo = recuperaDados('periodos', 'id', $ocorrencia['periodo_id']);

                                                //testa e se necessário retorna as datas de exceção
                                                $datas = "";
                                                $testaExcecao = $con->query("SELECT * FROM ocorrencia_excecoes WHERE atracao_id = " . $ocorrencia['id']);
                                                if ($testaExcecao->num_rows > 0) {
                                                    while ($excessoesArray = mysqli_fetch_array($testaExcecao)) {
                                                        $datas = $datas . exibirDataBr($excessoesArray['data_excecao']) . ", ";
                                                    }
                                                    $datas = substr($datas, 0, -2);
                                                }
                                                ?>
                                                <tr>
                                                    <th class="text-center bg-primary" colspan="2">Ocorrência
                                                        #<?= $i ?></th>
                                                </tr>
                                                <tr>
                                                    <th width="30%">Data de Inicio:</th>
                                                    <td><?= exibirDataBr($ocorrencia['data_inicio']) ?></td>
                                                </tr>
                                                <?php
                                                if ($ocorrencia['virada'] != 1) {
                                                    ?>
                                                    <tr>
                                                        <th width="30%">Data de Encerramento:</th>
                                                        <td><?= $ocorrencia['data_fim'] == "0000-00-00" ? "Não é Temporada" : exibirDataBr($ocorrencia['data_fim']) ?></td>
                                                    </tr>

                                                    <?php
                                                    if ($datas != ""): ?>
                                                        <tr>
                                                            <th width="30%">Data de Exceção:</th>
                                                            <td><?= $datas ?></td>
                                                        </tr>
                                                    <?php endif; ?>

                                                    <tr>
                                                        <th width="30%">Hora de Início:</th>
                                                        <td><?= date("H:i", strtotime($ocorrencia['horario_inicio'])) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th width="30%">Hora de Encerramento:</th>
                                                        <td><?= date("H:i", strtotime($ocorrencia['horario_fim'])) ?></td>
                                                    </tr>

                                                    <?php
                                                }
                                                ?>
                                                <tr>
                                                    <th width="30%">Período:</th>
                                                    <td><?= $periodo['periodo'] ?></td>
                                                </tr>

                                                <tr>
                                                    <th width="30%">Retirada de Ingresso:</th>
                                                    <td><?= $retiradaIngresso ?></td>
                                                </tr>
                                                <?php
                                                if ($ocorrencia['retirada_ingresso_id'] != 2) {
                                                    ?>
                                                    <tr>
                                                        <th width="30%">Valor do Ingresso:</th>
                                                        <td><?= "R$" . dinheiroParaBr($ocorrencia['valor_ingresso']) ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                <tr>
                                                    <th width="30%">Libras:</th>
                                                    <td><?= $ocorrencia['libras'] == 1 ? "Sim" : "Não" ?></td>
                                                </tr>
                                                <tr>
                                                    <th width="30%">Audiodescrição:</th>
                                                    <td><?= $ocorrencia['audiodescricao'] == 1 ? "Sim" : "Não" ?></td>
                                                </tr>
                                                <tr>
                                                    <th width="30%">Subprefeitura:</th>
                                                    <td><?= $subPrefeitura['subprefeitura'] ?></td>
                                                </tr>
                                                <?php
                                                if ($ocorrencia['instituicao_id'] != 10) {
                                                    ?>
                                                    <tr>
                                                        <th width="30%">Instituição:</th>
                                                        <td><?= $instituicao ?></td>
                                                    </tr>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <th width="30%">É virada?</th>
                                                        <td>Sim</td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                <tr>
                                                    <th width="30%">Local:</th>
                                                    <td><?= $local ?></td>
                                                </tr>
                                                <?php if ($ocorrencia['espaco_id'] != 0) { ?>
                                                    <tr>
                                                        <th width="30%">Espaço:</th>
                                                        <td><?= $espaco ?></td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <th width="30%">Observação:</th>
                                                    <td><?= checaCampo($ocorrencia['observacao']) ?></td>
                                                </tr>
                                                <?php
                                                $i++;
                                            }
                                        } else { ?>
                                            <tr>
                                                <th class="text-center bg-danger" colspan="2">Não há ocorrências
                                                    inseridas
                                                </th>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                </div>

                                <div class="row" align="center">
                                    <?php if (isset($mensagem)) {
                                        echo $mensagem;
                                    }; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } ?>
        </div>
    </div>
    <!-- /.box-body -->
</div>