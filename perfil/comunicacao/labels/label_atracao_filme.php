<?php
/**
 * Conteúdo da label "#atracao" do arquivo "finalizar.php"
 */

if ($evento['tipo_evento_id'] == 2) {
    ?>

    <div class="box box-solid">
        <div class="box-body">
            <div class="box-group" id="accordionAtracao">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <?php foreach ($filmes as $filme) {
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
                                            <td><?= $filme['genero'] == NULL ? "Não cadastrado" : $filme['genero']?></td>
                                        </tr>
                                        <tr>
                                            <th width="30%">Sinopse:</th>
                                            <td><?= $filme['sinopse'] == NULL ? "Não cadastrado" : $filme['sinopse'] ?></td>
                                        </tr>
                                        <tr>
                                            <th width="30%">Duração:</th>
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
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <span> <?= $atracao['ficha_tecnica'] ?> </span>
                                                    </div>
                                                </div>
                                                <div class="row linha-btnFicha">
                                                    <input type="hidden" id="idAtr" value="<?= $atracao['id'] ?>">
                                                    <div class="col-md-2 col-md-offset-5">
                                                        <button class="btn btn-success btn-small btnModal">Editar ficha tecnica
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
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
                                            <th>Release:</th>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                       <span> <?= $atracao['release_comunicacao'] ?> </span>
                                                    </div>
                                                    <div class="row linha-btnRelease">
                                                        <input type="hidden" id="id" value="<?= $atracao['id'] ?>">
                                                        <div class="col-md-2 col-md-offset-5" style="margin-top: 15px;">
                                                            <button class="btn btn-success btn-small btnModal">
                                                                Editar Release
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="row">

                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="30%">Links:</th>
                                            <td><a href="<?= $atracao['links'] ?>"><?= $atracao['links'] ?></a></td>
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
                                            <td><?= $produtor['observacao'] == NULL ? "Não cadastrado" : $produtor['observacao']?></td>
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

    <div class="modal fade in" id="modal-release">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Alteração de Release</h4>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="release_comunicacao">Release *</label><br/>
                            <i>Esse campo deve abordar informações relacionadas ao artista, abordando breves marcos
                                na carreira e ações realizadas anteriormente.</i>
                            <p align="justify"><span
                                        style="color: gray; "><strong><i>Texto de exemplo:</strong><br/>A cantora e compositora paulistana lançou, em 2007, o seu primeiro disco, "Amor e Caos". Dois anos depois, lançou "Hein?", disco produzido por Liminha e que contou com "Esconderijo", canção composta por Ana, eleita entre as melhores do ano pela revista Rolling Stone e que alcançou repercussão nacional por integrar a trilha sonora da novela "Viver a Vida" de Manoel Carlos, na Rede Globo. Ainda em 2009, grava, a convite do cantor e compositor Nando Reis, a bela canção "Pra Você Guardei o Amor". Em 2012, Ana lança o terceiro disco de inéditas, "Volta", com versões para Led Zeppelin ("Rock'n'Roll") e Edith Piaf ("La Vie en Rose"), além das inéditas autorais "Urubu Rei" (que ganhou clipe dirigido por Vera Egito) e "Será Que Você Me Ama?". Em 2013, veio o primeiro DVD, "Coração Inevitável", registrando o show que contou com a direção e iluminação de Ney Matogrosso.</span></i>
                            </p>
                            <input type="hidden" id="idAtr2">
                            <textarea id="release_comunicacao" name="txtReleaseComunicacao" class="form-control"
                                      rows="5" required><?= $atracao['release_comunicacao'] ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                        <button type="submit" id="alterRelease" name="release" class="btn btn-primary">Alterar</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade in" id="modal-ficha-tecnica">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Edição da ficha tecnica</h4>
                </div>
                <form id="form-ficha" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="ficha_tecnica">Ficha técnica completa *</label><br/>
                                <i>Esse campo deve conter a listagem de pessoas envolvidas no espetáculo, como
                                    elenco, técnicos, e outros profissionais envolvidos na realização do mesmo.</i>
                                <p align="justify"><span
                                            style="color: gray; "><strong><i>Elenco de exemplo:</strong><br/>Lúcio Silva (guitarra e vocal)<br/>Fabio Sá (baixo)<br/>Marco da Costa (bateria)<br/>Eloá Faria (figurinista)<br/>Leonardo Kuero (técnico de som)</span></i>
                                </p>
                                <input type="hidden" id="atracao_id">
                                <textarea id="txtFicha_tecnica" name="txtFichaTecnica" class="form-control" rows="8"
                                          required><?= $atracao['ficha_tecnica'] ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                        <button type="submit" id="alterFicha" name="ficha" class="btn btn-primary">Alterar</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <?php
}