<?php


$con = bancoMysqli();
if (isset($_POST['detalheEvento'])) {
    $idEvento = $_POST['idEvento'];
    $idPedido = $_POST['idPedido'];
}

// ARQUIVOS DE ANEXOS COMUNICAÇÃO/PRODUÇÃO //
$lista_documento_id = $con->query("SELECT id FROM lista_documentos WHERE sigla = 'com_prod'")->fetch_assoc()['id'];


// dados //
$sql = "select p.numero_processo,
e.protocolo,
p.valor_total,
p.forma_pagamento,
e.id,
ee.data_envio,
e.nome_evento,
e.nome_responsavel,
e.tipo_evento_id,
e.tel_responsavel,
p.pessoa_tipo_id,
p.pessoa_fisica_id,
p.numero_processo,
p.valor_total,
p.forma_pagamento,
p.observacao,
p.pessoa_juridica_id,
te.tipo_evento,
pe.projeto_especial,
rj.relacao_juridica,
u.nome_completo,
u.email,
u.telefone,
e.suplente_id,
e.sinopse,
p.id,
p.status_pedido_id


from pedidos as p
inner join eventos as e on e.id = p.origem_id
inner join evento_envios as ee on e.id = ee.evento_id
inner join tipo_eventos as te on e.tipo_evento_id = te.id
inner join projeto_especiais as pe on pe.id = e.projeto_especial_id
inner join relacao_juridicas as rj on rj.id = e.relacao_juridica_id
inner join usuarios as u on u.id = e.usuario_id

 AND e.publicado = 1 AND p.publicado = 1 where e.id = $idEvento";
$evento = $con->query($sql)->fetch_array();


if ($evento ['pessoa_tipo_id'] == 1) {
    $pessoa = "Física";
} else if ($evento ['pessoa_tipo_id'] == 2) {
    $pessoa = "Jurídica";
}

if ($evento['nome_responsavel'] != "") {
    $nomeResp = $evento['nome_responsavel'];
} else {
    $nomeResp = "Não cadastrado";
}

if ($evento['tel_responsavel'] != "") {
    $telResp = $evento['tel_responsavel'];
} else {
    $telResp = "Não cadastrado";
}


$suplente = recuperaDados('usuarios', 'id', $evento['suplente_id']);
$espaco = recuperaDados('evento_publico', 'evento_id', $idEvento);
$publico = recuperaDados('publicos', 'id', $espaco['publico_id']);
$pagamento = recuperaDados('pagamentos', 'pedido_id', $evento['id']);
$dotacao = recuperaDados('juridicos', 'pedido_id', $evento['id']);
$statusPedido = recuperaDados('pedido_status', 'id', $evento['status_pedido_id']);


?>


<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Contrato</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h1 class="box-title"><?= $evento['nome_evento'] ?></h1>
            </div>
            <div class="box-body">
                <table class="table">
                    <tr>
                        <th width="30%">ID do evento:</th>
                        <td><?= $evento['id'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Evento enviado em:</th>
                        <td><?= exibirDataHoraBr($evento['data_envio']) ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Tipo de evento:</th>
                        <td><?= $evento['tipo_evento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Projeto especial:</th>
                        <td><?= $evento['projeto_especial'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Relação jurídica:</th>
                        <td><?= $evento['relacao_juridica'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Usuário que cadastrou o evento:</th>
                        <td><?= $evento['nome_completo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $evento['telefone'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $evento['email'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Reponsável pelo evento:</th>
                        <td><?= $nomeResp ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $telResp ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Suplente:</th>
                        <td><?= $suplente['nome_completo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $suplente['telefone'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $suplente['email'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Público / Representatividade social:</th>
                        <td><?= $publico['publico'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Sinopse:</th>
                        <td><?= $evento['sinopse'] ?></td>
                    </tr>

                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>

                    <?php
                    if ($evento['tipo_evento_id'] == 1) {
                        $sqlAtracao = $con->query("SELECT a.nome_atracao, a.id, a.ficha_tecnica, c.classificacao_indicativa, a.release_comunicacao,
                                                   p.nome, p.telefone1, p.telefone2, p.email
                                                   FROM atracoes AS a
                                                   INNER JOIN eventos AS e ON a.evento_id = e.id
                                                   INNER JOIN classificacao_indicativas AS c ON a.classificacao_indicativa_id = c.id
                                                   INNER JOIN produtores AS p ON a.produtor_id = p.id
                                                   WHERE a.evento_id = $idEvento AND e.publicado = 1 AND a.publicado = 1");
                        $a = 1;
                        while ($atracao = mysqli_fetch_array($sqlAtracao)) {
                            $acoes = $con->query("SELECT a.acao FROM acoes AS a INNER JOIN acao_atracao AS at ON at.acao_id = a.id WHERE at.atracao_id = " . $atracao['id'])->fetch_array();
                            ?>
                            <tr>
                                <th width="30%">Atração #<?= $a ?>: <?= $atracao['nome_atracao'] ?></th>
                            </tr>

                            <tr>
                                <th width="30%">Ficha Técnica:</th>
                                <td><?= $atracao['ficha_tecnica'] ?></td>
                            </tr>

                            <th width="30%">Linguagem / Expressão artística:</th>
                            <td><?= $acoes['acao'] ?></td>
                            </tr>

                            <tr>
                                <th width="30%">Faixa ou indicação etária:</th>
                                <td><?= $atracao['classificacao_indicativa'] ?></td>
                            </tr>

                            <tr>

                                <th width="30%">Release:</th>
                                <td><?= $atracao['release_comunicacao'] ?></td>
                            </tr>

                            <tr>
                                <th><br/></th>
                                <td></td>
                            </tr>

                            <tr>
                                <th width="30%">Produtor:</th>
                                <td><?= $atracao['nome'] ?> </td>
                            </tr>
                            <tr>
                                <th width="30%">Email:</th>
                                <td><?= $atracao['email'] ?> </td>
                            </tr>
                            <tr>
                                <th width="30%">Telefone 1:</th>
                                <td><?= $atracao['telefone1'] ?> </td>
                            </tr>

                            <tr>
                                <th width="30%">Telefone 2:</th>
                                <td><?= $atracao['telefone2'] ? "" : "Não cadastrado" ?></td>
                            </tr>

                            <tr>
                                <th><br/></th>
                                <td></td>
                            </tr>

                            <?php $a++;
                        }
                    } else if ($evento['tipo_evento_id'] == 2) {
                        $sqlFilmes = $con->query("SELECT f.titulo, f.ano_producao, f.genero, f.direcao, f.duracao, c.classificacao_indicativa
                                                 FROM filme_eventos AS fe
                                                 INNER JOIN filmes AS f ON fe.filme_id = f.id
                                                 INNER JOIN eventos AS e ON e.id = fe.evento_id
                                                 INNER JOIN classificacao_indicativas AS c ON c.id = f.classificacao_indicativa_id
                                                 WHERE fe.evento_id = $idEvento AND f.publicado = 1 AND e.publicado = 1");
                        $acoes = $con->query("SELECT acao FROM acoes WHERE id = 1 AND publicado = 1")->fetch_array();
                        $f = 1;
                        while ($filmes = mysqli_fetch_array($sqlFilmes)) {
                            ?>
                            <tr>
                                <th width="30%">Filme #<?= $f ?>: <?= $filmes['titulo'] ?></th>
                            </tr>

                            <tr>
                                <th width="30%">Gênero:</th>
                                <td><?= $filmes['genero'] == NULL ? "Não Cadastrado" :  $filmes['genero']?></td>
                            </tr>

                            <th width="30%">Linguagem / Expressão artística:</th>
                            <td><?= $acoes['acao'] ?></td>
                            </tr>

                            <tr>
                                <th width="30%">Faixa ou indicação etária:</th>
                                <td><?= $filmes['classificacao_indicativa'] ?></td>
                            </tr>

                            <tr>

                                <th width="30%">Ano de produção:</th>
                                <td><?= $filmes['ano_producao'] ?></td>
                            </tr>

                            <tr>
                                <th width="30%">Direção:</th>
                                <td><?= $filmes['direcao'] ?> </td>
                            </tr>

                            <tr>
                                <th width="30%">Duração(min):</th>
                                <td><?= $filmes['duracao'] ?> </td>
                            </tr>

                            <tr>
                                <th><br/></th>
                                <td></td>
                            </tr>


                            <?php $f++;
                        }
                    } ?>


                </table>
                <h1>Especificidades</h1>
                <h3>Ocorrências</h3>
                <?php

                if ($evento['tipo_evento_id'] == 1) {
                $i = 1;
                $sqlOcorrenciaAtracao = "SELECT a.produtor_id,pro.nome,pro.email,pro.telefone1,
                pro.telefone2,o.atracao_id,o.periodo_id,o.local_id,o.horario_fim,
                o.virada,o.data_inicio,o.data_fim,retirada_ingresso_id,
                o.instituicao_id,espaco_id,o.observacao,o.horario_inicio,e.id, o.valor_ingresso
                FROM ocorrencias  as o
                INNER JOIN atracoes as a on a.id = o.atracao_id
                INNER JOIN eventos as e on e.id = a.evento_id
                INNER JOIN produtores as pro on pro.id = a.produtor_id
                INNER JOIN locais as l on l.id = o.local_id
                INNER JOIN periodos as p on p.id = o.periodo_id
                WHERE e.id = '$idEvento' AND e.publicado = 1 AND o.publicado = 1 AND o.tipo_ocorrencia_id = 1";
                $ocorrencias = $con->query($sqlOcorrenciaAtracao);
                ?>
                </table>
                <table class="table">
                    <?php
                    if ($ocorrencias->num_rows > 0) {
                        foreach ($ocorrencias as $ocorrencia) {
                            $atracao = recuperaDados('atracoes', 'evento_id', $idEvento);
                            $retiradaIngresso = recuperaDados('retirada_ingressos', 'id', $ocorrencia['retirada_ingresso_id'])['retirada_ingresso'];
                            $instituicao = recuperaDados('instituicoes', 'id', $ocorrencia['instituicao_id'])['nome'];
                            $local = recuperaDados('locais', 'id', $ocorrencia['local_id']);
                            if ($ocorrencia['espaco_id'] != 0) {
                                $espaco = recuperaDados('espacos', 'id', $ocorrencia['espaco_id'])['espaco'];
                            } else {
                                $espaco = "Não cadastrado";
                            }
                            $periodo = recuperaDados('periodos', 'id', $ocorrencia['periodo_id']);
                            $nomeAtracao = recuperaDados('atracoes', 'id', $ocorrencia['atracao_id'])['nome_atracao'];
                            $produtor = recuperaDados('produtores', 'id', $ocorrencia['produtor_id'])['nome'];
                            ?>
                            <tr>
                                <th>Atração:</th>
                                <td><?= $nomeAtracao ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Ocorrência #<?= $i ?></th>
                            </tr>
                            <tr>
                                <th width="30 % ">Data de Inicio:</th>
                                <td><?= exibirDataBr($ocorrencia['data_inicio']) ?></td>
                            </tr>
                            <?php
                            if ($ocorrencia['virada'] != 1) {
                                ?>
                                <tr>
                                    <th width="30%">Data de Encerramento:</th>
                                    <td><?= $ocorrencia['data_fim'] == "0000-00-00" ? "Não é Temporada" : exibirDataBr($ocorrencia['data_fim']) ?></td>
                                </tr>

                                <tr>
                                    <th width="30%">Hora de Início:</th>
                                    <td><?= date("H:i", strtotime($ocorrencia['horario_inicio'])) ?></td>
                                </tr>
                                <tr>
                                    <th width="30%">Hora de Encerramento:</th>
                                    <td><?= date("H:i", strtotime($ocorrencia['horario_fim'])) ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th width="30 % ">Período:</th>
                                <td><?= $periodo['periodo'] ?></td>
                            </tr>

                            <tr>
                                <th width="30 % ">Retirada de Ingresso:</th>
                                <td><?= $retiradaIngresso ?></td>
                            </tr>

                            <tr>
                                <th width="30%">Valor do Ingresso:</th>
                                <?php
                                if ($ocorrencia['retirada_ingresso_id'] != 2) { ?>

                                    <td><?= "R$" . dinheiroParaBr($ocorrencia['valor_ingresso']) ?></td>

                                <?php } else {
                                    ?>

                                    <td><?= "Ingresso Gratuito" ?></td>

                                <?php } ?>
                            </tr>

                            <?php
                            if ($ocorrencia['instituicao_id'] != 10) {
                                ?>
                                <tr>
                                    <th width="30 % ">Instituição:</th>
                                    <td><?= $instituicao ?></td>
                                </tr>
                                <?php
                            } else {
                                ?>
                                <tr>
                                    <th width="30 % ">É virada?</th>
                                    <td>Sim</td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th width="30 % ">Local:</th>
                                <td><?= $local['local'] ?></td>
                            </tr>
                            <tr>
                                <th width="30 % ">Espaço:</th>
                                <td><?= $espaco ?></td>
                            </tr>
                            <tr>
                                <th width="30% ">Observação:</th>
                                <td><?= $ocorrencia['observacao'] == NULL ? "Não cadastrado" : $ocorrencia['observacao'] ?></td>
                            </tr>

                            <tr>
                                <td>
                                    <br>
                                </td>
                            </tr>
                            <?php
                            $i++;
                        }
                    } else {
                        echo "Evento não possui ocorrências";
                    }
                    } ?>
                    <h1>Arquivos Comunicação/Produção anexos</h1>
                    <?php
                    //lista arquivos de arquivo comunicação/produção//
                    $sql = "SELECT * FROM arquivos as arq
                                    WHERE arq.origem_id = '$idEvento' AND lista_documento_id = '$lista_documento_id'
                                    AND arq.publicado = '1' ORDER BY arq.id";
                    $query = mysqli_query($con, $sql);
                    $linhas = mysqli_num_rows($query);

                    if ($linhas > 0) {
                        echo "
                                <table class='table table-striped'>
                                    <thead>
                                            <tr class='bg-info text-bold'>
                                                <td>Nome do documento</td>
                                                <td width='10%'>Data de envio</td>
                                            </tr>
                                        </thead>
                                        <tbody>";
                        while ($arquivo = mysqli_fetch_array($query)) {
                            echo "<tr>";
                            echo "<td class='list_description'><a href='../uploadsdocs/" . $arquivo['arquivo'] . "' target='_blank'>" . mb_strimwidth($arquivo['arquivo'], 15, 25, "...") . "</a></td>";
                            echo "<td class='list_description'>(" . exibirDataBr($arquivo['data']) . ")</td>";
                            echo "</tr>";
                        }
                        echo "
                                        </tbody>
                                        </table>";
                    } else {
                        echo "<p>Não há listas disponíveis no momento.<p/><br/>";
                    }
                    ?>
                    <br>
                    <br>
                    <h3>Pedidos de contratação</h3>
                    <table class="table">
                        <tr>
                            <th width="30%">Protocolo:</th>
                            <td><?= $evento['protocolo'] ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Número do processo:</th>
                            <td><?= $evento['numero_processo'] ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Tipo de pessoa:</th>
                            <td><?= $pessoa ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Objeto:</th>
                            <td><?= $objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento']; ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Valor:</th>
                            <td><?= "R$" . $evento['valor_total'] ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Forma de Pagamento:</th>
                            <td><?= $evento['forma_pagamento'] ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Data:</th>
                            <td><?= retornaPeriodoNovo($idEvento, 'ocorrencias') ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Data de Emissão da N.E:</th>
                            <td><?php
                                if ($pagamento['emissao_nota_empenho'] = 'null') {
                                    echo "Não cadastrado";
                                } else $pagamento['emissao_nota_empenho']
                                ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Data de Entrega da N.E:</th>
                            <td><?php
                                if ($pagamento['entrega_nota_empenho'] = 'null') {
                                    echo "Não cadastrado";
                                } else $pagamento['entrega_nota_empenho']
                                ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Dotação Orçamentária:</th>
                            <td><?php
                                if ($dotacao['dotacao'] = 'null') {
                                    echo "Não cadastrado";
                                } else $dotacao['dotacao']
                                ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Observação:</th>
                            <td><?= $evento['observacao'] == NULL ? "Não cadastrado" : $evento['observacao'] ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Último status do Pedido:</th>
                            <td><?= $statusPedido['status'] ?></td>
                        </tr>
                    </table>
                    <br/>
                    <div class="pull-left">
                        <form action="?perfil=contrato&p=resumo" method="post">
                            <input type="hidden" value="<?= $idPedido ?>" name="idPedido">
                            <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                            <button type="submit" name="Voltar" class="btn btn-default pull-left">Voltar</button>
                        </form>
                    </div>
            </div>
        </div>
    </section>
</div>
