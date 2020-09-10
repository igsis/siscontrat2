<?php
$idEvento = $_POST['idEvento'];

$con = bancoMysqli();
$evento = $con->query("SELECT  e.protocolo, e.tipo_evento_id, e.nome_evento, e.espaco_publico, f.fomento, rj.relacao_juridica, pe.projeto_especial, e.sinopse, uf.nome_completo AS fiscal_nome, us.nome_completo AS suplente_nome, uf.nome_completo AS user_nome
    FROM eventos AS e
    LEFT JOIN fomentos f on e.fomento = f.fomento
    INNER JOIN relacao_juridicas rj on e.relacao_juridica_id = rj.id
    INNER JOIN projeto_especiais pe on e.projeto_especial_id = pe.id
    INNER JOIN usuarios uf on e.fiscal_id = uf.id
    INNER JOIN usuarios us on e.suplente_id = us.id
    INNER JOIN usuarios ur on e.usuario_id = ur.id
    WHERE e.id = $idEvento
")->fetch_assoc();

$sql_atracao = "SELECT * FROM atracoes WHERE evento_id = '$idEvento'";

?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START ACCORDION-->
        <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/siscontrat2/pdf/resumo_evento.php?id=<?= $idEvento ?>"
           target="_blank"><h2 class="page-header">Informações do Evento</h2></a>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><strong><?= $evento['nome_evento']; ?></strong></h3>
                        <div class="box-body">

                            <div class="form-group col-md-12">
                                <h3 align="center">Informações sobre o evento</h3>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Protocolo: </strong><?= $evento['protocolo'] ?>
                            </div>
                            <div class="form-group col-md-6">
                                <strong>Espaço
                                    público: </strong><?php if ($evento['espaco_publico'] == 1) echo "Sim"; else echo "Não"; ?>
                            </div>
                            <div class="form-group col-md-6">
                                <strong>Fomento: </strong>
                                <?php if ($evento['espaco_publico'] == 1) {
                                    echo $evento['fomento'];
                                } else {
                                    echo "Não";
                                } ?>
                            </div>
                            <div class="form-group col-md-6">
                                <strong>Relação jurídica:</strong> <?= $evento['relacao_juridica'] ?>
                            </div>
                            <div class="form-group col-md-6">
                                <strong>Projeto especial:</strong> <?= $evento['projeto_especial'] ?>
                            </div>
                            <div class="form-group col-md-12">
                                <strong>Sinopse:</strong> <?= $evento['sinopse'] ?>
                            </div>
                            <div class="form-group col-md-4">
                                <strong>Fiscal:</strong> <?= $evento['fiscal_nome'] ?>
                            </div>
                            <div class="form-group col-md-4">
                                <strong>Suplente:</strong> <?= $evento['suplente_nome'] ?>
                            </div>
                            <div class="form-group col-md-4">
                                <strong>Responsável:</strong> <?= $evento['user_nome'] ?>
                            </div>
                            <hr>
                            <?php
                            if ($evento['tipo_evento_id'] == 1) {
                                $query_atracao = mysqli_query($con, $sql_atracao);
                                while ($atracao = mysqli_fetch_array($query_atracao)) {

                                    $classificacao_indicativa = recuperaDados('classificacao_indicativas', 'id', $atracao['classificacao_indicativa_id']);

                                    $idAtracao = $atracao['id'];
                                    $sql_ocorrencia = "SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = '$idEvento' AND publicado = 1";

                                    ?>
                                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                    <div class="form-group col-md-12">
                                        <div align="center">
                                            <h3>Informações sobre a atração</h3>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <strong>Nome da atração: </strong><?= $atracao['nome_atracao']; ?>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <strong>Ação: </strong>
                                        <?php

                                        $sqlAcao = "SELECT a.acao FROM acao_atracao at INNER JOIN acoes a on at.acao_id = a.id WHERE atracao_id = '$idAtracao'";
                                        $queryAcao = mysqli_query($con, $sqlAcao);

                                        $acaoArray[] = '';

                                        while ($acao = mysqli_fetch_array($queryAcao)) {
                                            echo $acao['acao'] . '; ';
                                        }
                                        ?>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <strong>Ficha técnica: </strong><?= $atracao['ficha_tecnica']; ?>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <strong>Integrantes: </strong><?= $atracao['integrantes']; ?>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <strong>Classificação
                                            Indicativa: </strong><?= $classificacao_indicativa['classificacao_indicativa']; ?>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <strong>Quantidade de
                                            Apresentação: </strong><?= $atracao['quantidade_apresentacao']; ?>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <strong>Release: </strong><?= $atracao['release_comunicacao']; ?>
                                    </div>
                                    <?php
                                    if ($atracao['links'] != NULL) {
                                        ?>
                                        <div class="form-group col-md-12">
                                            <strong>Links: </strong><?= $atracao['links']; ?>
                                        </div>
                                    <?php } ?>

                                    <?php
                                    if ($atracao['valor_individual'] != NULL) {
                                        ?>
                                        <div class="form-group col-md-12">
                                            <strong> Valor
                                                individual: </strong>
                                            R$ <?= dinheiroParaBr($atracao['valor_individual']); ?>
                                        </div>
                                    <?php }

                                    if ($atracao['produtor_id'] != NULL) {
                                        $produtor = recuperaDados('produtores', 'id', $atracao['produtor_id']);
                                        ?>
                                        <div class="form-group col-md-12">
                                            <div align="center">
                                                <h3>Informações sobre a produção</h3>
                                                <hr>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Nome Produtor: </strong><?= $produtor['nome']; ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Email do Produtor: </strong><?= $produtor['email']; ?>
                                        </div>
                                        <?php
                                        if ($produtor['telefone1'] != '') {
                                            ?>
                                            <div class="form-group col-md-12">
                                                <strong>Telefone 1: </strong><?= $produtor['telefone1']; ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($produtor['telefone2'] != '') {
                                            ?>
                                            <div class="form-group col-md-12">
                                                <strong>Telefone 2: </strong><?= $produtor['telefone2']; ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <hr>
                                        <div class="form-group col-md-12">
                                            <strong>Observação:</strong><?= $produtor['observacao']; ?>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    $query_ocorrencia = mysqli_query($con, $sql_ocorrencia);
                                    while ($ocorrencia = mysqli_fetch_array($query_ocorrencia)) {

                                        $local = recuperaDados('locais', 'id', $ocorrencia['local_id']);
                                        $retirada_ingresso = recuperaDados('retirada_ingressos', 'id', $ocorrencia['retirada_ingresso_id']);
                                        $instituicao = recuperaDados('instituicoes', 'id', $ocorrencia['instituicao_id'])['nome'];
                                        $espaco = recuperaDados('espacos', 'id', $ocorrencia['espaco_id'])['espaco'] ?? NULL;

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


                                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->


                                        <div class="form-group col-md-12">
                                            <div align="center">
                                                <h3>Ocorrências</h3>
                                                <hr>
                                            </div>
                                        </div>

                                        <?php
                                        if ($datas != ""): ?>
                                            <div class="form-group col-md-12">
                                                <strong>Data de Exceção: </strong><?= $datas ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="form-group col-md-12">
                                            <strong>Instituição: </strong><?= $instituicao ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Local: </strong><?= $local['local'] ?? "Não possui" ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Espaço: </strong><?= $espaco ?? "Não possui" ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Valor do
                                                ingresso: </strong>R$<?= dinheiroParaBr($ocorrencia['valor_ingresso']) ?>
                                        </div>
                                        <?php
                                        if ($ocorrencia['horario_inicio'] != NULL) {
                                            ?>
                                            <div class="form-group col-md-12">
                                                <strong>Horário
                                                    Inicial: </strong><?= exibirHora($ocorrencia['horario_inicio']); ?>
                                            </div>
                                        <?php } ?>
                                        <?php
                                        if ($ocorrencia['horario_fim'] != NULL) {
                                            ?>
                                            <div class="form-group col-md-12">
                                                <strong>Horário
                                                    Final: </strong><?= exibirHora($ocorrencia['horario_fim']); ?>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group col-md-12">
                                            <strong>Retirada de
                                                Ingresso:</strong> <?= $retirada_ingresso['retirada_ingresso'] ?>
                                        </div>
                                        <?php
                                        if ($ocorrencia['virada'] != NULL) {
                                            ?>
                                            <div class="form-group col-md-12">
                                                <strong>Virada:</strong> <?= $ocorrencia['virada'] == 1 ? "Sim" : "Não"; ?>
                                            </div>
                                        <?php } ?>
                                    <?php }
                                }
                            } else {
                                $sql_filme = "SELECT f.id, f.titulo, f.ano_producao, f.genero, f.sinopse, f.duracao FROM filme_eventos fe INNER JOIN eventos e on fe.evento_id = e.id INNER JOIN filmes f ON f.id = fe.filme_id WHERE e.id = $idEvento AND e.publicado = 1 AND f.publicado = 1";
                                $query_filme = mysqli_query($con, $sql_filme);
                                $contador = 1;
                                while ($filme = mysqli_fetch_array($query_filme)) {

                                    $idFilme = $filme['id'];
                                    $sql_ocorrencia = "SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = '$idEvento' AND publicado = 1";
                                    ?>
                                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                    <div class="form-group col-md-12">
                                        <div align="center">
                                            <h3>Informações sobre a atração</h3>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <strong>Nome do filme: </strong><?= $filme['titulo']; ?>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <strong>Ano de Produção: </strong><?= $filme['ano_producao']; ?>
                                    </div>


                                    <div class="form-group col-md-12">
                                        <strong>Gênero: </strong><?= $filme['genero']; ?>
                                    </div>


                                    <div class="form-group col-md-12">
                                        <strong>Sinopse: </strong><?= $filme['sinopse']; ?>
                                    </div>


                                    <div class="form-group col-md-12">
                                        <strong>Duração: </strong><?= $filme['duracao']; ?> min
                                    </div>
                                    <?php
                                    $query_ocorrencia = mysqli_query($con, $sql_ocorrencia);
                                    while ($ocorrencia = mysqli_fetch_array($query_ocorrencia)) {

                                        $local = recuperaDados('locais', 'id', $ocorrencia['local_id']);
                                        $retirada_ingresso = recuperaDados('retirada_ingressos', 'id', $ocorrencia['retirada_ingresso_id']);

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
                                        <div class="form-group col-md-12">
                                            <div align="center">
                                                <h3>Ocorrência #<?= $contador ?></h3>
                                                <hr>
                                            </div>
                                        </div>

                                        <?php
                                        if ($datas != ""): ?>
                                            <div class="form-group col-md-12">
                                                <strong>Data de Exceção: </strong><?= $datas ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="form-group col-md-12">
                                            <strong>Local: </strong><?= $local['local']; ?>
                                        </div>
                                        <?php
                                        if ($ocorrencia['horario_inicio'] != NULL) {
                                            ?>
                                            <div class="form-group col-md-12">
                                                <strong>Horário
                                                    Inicial: </strong><?= exibirHora($ocorrencia['horario_inicio']); ?>
                                            </div>
                                        <?php } ?>
                                        <?php
                                        if ($ocorrencia['horario_fim'] != NULL) {
                                            ?>
                                            <div class="form-group col-md-12">
                                                <strong>Horário
                                                    Final: </strong><?= exibirHora($ocorrencia['horario_fim']); ?>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group col-md-12">
                                            <strong>Retirada de
                                                Ingresso:</strong> <?= $retirada_ingresso['retirada_ingresso'] ?>
                                        </div>
                                        <?php
                                        if ($ocorrencia['virada'] != NULL) {
                                            ?>
                                            <div class="form-group col-md-12">
                                                <strong>Virada:</strong> <?= $ocorrencia['virada'] == 1 ? "Sim" : "Não"; ?>
                                            </div>
                                        <?php }
                                        $contador++;
                                        ?>

                                    <?php }
                                }
                            }

                            ?>
                            <div class="box-footer" align="center">
                                <a href="?perfil=pesquisa">
                                    <button type="button" class="btn btn-default">Voltar</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col -->
            <!-- /.row -->
            <!-- END ACCORDION & CAROUSEL-->

    </section>
    <!-- /.content -->
</div>
