<?php
/**
 * Conteúdo da label "#atracao" do arquivo "detalhes_gestao.php"
 */

if ($evento['tipo_evento_id'] == 2) {
    $sqlFilme = "SELECT f.id, f.titulo, f.ano_producao, f.genero, f.sinopse, f.duracao, fe.id as 'idFilmeEvento' FROM filme_eventos fe INNER JOIN eventos e on fe.evento_id = e.id INNER JOIN filmes f ON f.id = fe.filme_id WHERE e.id = $idEvento AND e.publicado = 1 AND f.publicado = 1";
    $filmes = mysqli_query($con, $sqlFilme);
    ?>

    <div class="box box-solid">
        <div class="box-body">
            <div class="box-group" id="accordionAtracao">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <?php foreach ($filmes as $filme) {
                    $idFilme = $filme['id'];
                    $sqlOcorrencia = "SELECT * FROM ocorrencias oco INNER JOIN filme_eventos fe ON fe.evento_id = oco.origem_ocorrencia_id WHERE fe.filme_id = '$idFilme' AND oco.publicado = 1";
                    $ocorrencias = mysqli_query($con, $sqlOcorrencia);
                    ?>
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordionAtracao"
                                   href="#collapse<?= $filme['idFilmeEvento'] ?>">
                                    Resumo do Filme: <?= $filme['titulo'] ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse<?= $filme['idFilmeEvento'] ?>" class="panel-collapse collapse">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th width="30%">Nome do Filme:</th>
                                            <td><?= $filme['titulo'] ?></td>
                                        </tr>
                                        <tr>
                                            <th width="30%">Ano de Produção:</th>
                                            <td><?= $filme['ano_producao'] ?></td>
                                        </tr>
                                        <tr>
                                            <th width="30%">Gênero:</th>
                                            <td><?= checaCampo($filme['genero']) ?></td>
                                        </tr>
                                        <tr>
                                            <th width="30%">Sinopse:</th>
                                            <td><?= checaCampo($filme['sinopse']) ?></td>
                                        </tr>
                                        <tr>
                                            <th width="30%">Duração(mins):</th>
                                            <td><?= $filme['duracao'] ?></td>
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
<?php } else if ($evento['tipo_evento_id'] == 1) {
    $atracoes = $con->query("SELECT * FROM atracoes WHERE evento_id = '$idEvento' AND publicado = '1'");
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
                    $idAtracao = $atracao['id'];
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
                                            <td><?= checaCampo($atracao['links']) ?></td>
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
                                            <td><?= checaCampo($produtor['observacao']) ?></td>
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
    <?php
}