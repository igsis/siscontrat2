<?php
/**
 * Conteúdo da label "#atracao" do arquivo "visualizacao_producao.php"
 */
$atracoes = $con->query("SELECT * FROM atracoes WHERE evento_id = '$idEvento' AND publicado = '1'")
?>

<div class="box box-solid">
    <div class="box-body">
        <div class="box-group" id="accordionAtracao">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <?php foreach ($atracoes as $atracao) {
                $acaoId = recuperaDados('acao_atracao', 'atracao_id', $atracao['id'])['acao_id'];
                $categoria = recuperaDados('acoes', 'id', $acaoId)['acao'];
                $classificacao = recuperaDados('classificacao_indicativas', 'id', $atracao['classificacao_indicativa_id'])['classificacao_indicativa'];
                $produtor = recuperaDados('produtores', 'id', $atracao['produtor_id']);
                ?>
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordionAtracao"
                               href="#collapse<?= $atracao['id'] ?>">
                                Resumo da Atração: <?= $atracao['nome_atracao'] ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse<?= $atracao['id'] ?>" class="panel-collapse collapse">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th width="30%">Nome da Atração:</th>
                                        <td><?= $atracao['nome_atracao'] ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Categoria da Atração:</th>
                                        <td><?= $categoria ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Ficha Técnica:</th>
                                        <td><?= $atracao['ficha_tecnica'] ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Integrantes:</th>
                                        <td><?= $atracao['integrantes'] ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Classificação Indicativa:</th>
                                        <td><?= $classificacao ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Release:</th>
                                        <td><?= $atracao['release_comunicacao'] ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Links:</th>
                                        <td><?= $atracao['links'] ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-center bg-primary" colspan="2">Dados do Produtor</th>
                                    </tr>
                                    <tr>
                                        <th width="30%">Nome:</th>
                                        <td><?= $produtor['nome'] ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Email:</th>
                                        <td><?= $produtor['email'] ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Telefone #1:</th>
                                        <td><?= $produtor['telefone1'] ?></td>
                                    </tr>
                                    <?php
                                    if ($produtor['telefone2'] != null) { ?>
                                        <tr>
                                            <th width="30%">Telefone #2:</th>
                                            <td><?= $produtor['telefone2'] ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <th width="30%">Observação:</th>
                                        <td><?= $produtor['observacao'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <!-- /.box-body -->
</div>
