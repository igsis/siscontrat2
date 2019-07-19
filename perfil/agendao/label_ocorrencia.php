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
                $idEvento = $_SESSION['idEvento'];
                $sqlOcorrencia = "SELECT * FROM agendao_ocorrencias WHERE origem_ocorrencia_id = '".$idEvento."'  AND tipo_ocorrencia_id = 3 AND publicado = '1'";
                $ocorrencias = $con->query($sqlOcorrencia);

                $aberto = "in";
                ?>
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">

                        </h4>
                    </div>
                    <div id="ocorrencia" class="panel-collapse collapse <?=$aberto?>">
                        <div class="box-body">
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
                                    $espaco = recuperaDados('espacos', 'id', $ocorrencia['espaco_id'])['espaco'];
                                    $periodo = recuperaDados("periodos","id",$ocorrencia['periodo_id']);
                                    $subprefeitura = recuperaDados("subprefeituras","id",$ocorrencia['subprefeitura_id']);
                                    ?>
                                    <div class="row text-center bg-primary">
                                        Ocorrência #<?=$i?>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Data de Início:</label> <?= exibirDataBr($ocorrencia['data_inicio']) ?>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Data de Encerramento:</label> <?= $ocorrencia['data_fim'] == null ? exibirDataBr($ocorrencia['data_fim']) : "Não é Temporada" ?>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Hora de Início:</label> <?= date("H:i", strtotime($ocorrencia['horario_inicio'])) ?>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Hora de Encerramento:</label> <?= date("H:i", strtotime($ocorrencia['horario_fim'])) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label>Libras:</label>
                                            <?php
                                            if($ocorrencia['libras'] == 1){
                                                echo "Sim";
                                            } else{
                                                echo "Não";
                                            }
                                            ?>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Audiodrescrição:</label>
                                            <?php
                                            if($ocorrencia['audiodescricao'] == 1){
                                                echo "Sim";
                                            } else{
                                                echo "Não";
                                            }
                                            ?>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label>Retirada de Ingresso:</label> <?= $retiradaIngresso ?>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Valor do Ingresso:</label> <?= dinheiroParaBr($ocorrencia['valor_ingresso']) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Local:</label> <?= $local ?>  <?php if ($ocorrencia['espaco_id'] != 0) {
                                                echo " - " . $espaco;
                                            } ?>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Subprefeitura:</label> <?= $subprefeitura['subprefeitura'] ?>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Período:</label> <?= $periodo['periodo'] ?>
                                        </div>
                                    </div>
                                    <?php if($ocorrencia['observacao'] != ''){ ?>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Observação:</label> <?= $ocorrencia['observacao'] ?>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    $i++;
                                }
                            } else { ?>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <div class="row text-center bg-danger">Não há ocorrências inseridas</div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>