<?php

$con = bancoMysqli();
$idEvento = $_SESSION['eventoId'];


// para inserir a informação em Dotação //
$sql = "SELECT * FROM juridicos where pedido_id = '$idEvento'";
$query = mysqli_query($con, $sql);
$num = mysqli_num_rows($query);


// dados //
$sql = "SELECT
    p.numero_processo,
    e.protocolo,
    e.id,
    e.nome_evento,
    ev.data_envio,
    te.tipo_evento,
    pe.projeto_especial,
    rj.relacao_juridica,
    u.nome_completo,
    u.telefone,
    u.email,
    a.ficha_tecnica,
    ci.classificacao_indicativa,
    e.sinopse,
    a.release_comunicacao,
    oc.horario_inicio,
    oc.observacao,
    i.nome,
    i.sigla,
    ri.retirada_ingresso,
    pr.nome,
    pr.email,
    pr.telefone1
    
    FROM pedidos as p
    INNER JOIN pessoa_fisicas pf on p.pessoa_fisica_id = pf.id
    INNER JOIN eventos e on e.id = p.origem_id
    INNER JOIN evento_envios ev on e.id = ev.evento_id
    INNER JOIN tipo_eventos te on e.tipo_evento_id = te.id
    INNER JOIN projeto_especiais pe on e.projeto_especial_id = pe.id
    INNER JOIN relacao_juridicas rj on e.relacao_juridica_id = rj.id
    INNER JOIN usuarios u on u.id = e.usuario_id
    INNER JOIN atracoes a on a.evento_id = e.id
    INNER JOIN produtores pr on a.produtor_id = pr.id
    INNER JOIN classificacao_indicativas ci on a.classificacao_indicativa_id = ci.id
    INNER JOIN ocorrencias oc on oc.atracao_id = a.id
    INNER JOIN instituicoes i on oc.instituicao_id = i.id
    INNER JOIN retirada_ingressos ri on oc.retirada_ingresso_id = ri.id
    
    WHERE p.publicado = 1 AND p.origem_tipo_id = 1 AND e.id = $idEvento";
$evento = $con->query($sql)->fetch_array();
$dotacao = $con->query("SELECT * FROM juridicos WHERE pedido_id = 1")->fetch_array();
?>


<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Jurídico</h2>
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
                        <td><?= $evento['data_envio'] ?></td>
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
                        <th width="30%">Suplente:</th>
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
                        <th width="30%">Ficha técnica:</th>
                        <td><?= $evento['ficha_tecnica'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Faixa ou indicação etária:</th>
                        <td><?= $evento['classificacao_indicativa'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Linguagem / Expressão artística:</th>
                        <td>#</td>
                    </tr>
                    <tr>
                        <?php
                        $sqlPublico = "SELECT * 
                        FROM publicos where id = $idEvento";
                        $pub = $con->query($sqlPublico)->fetch_assoc();
                        ?>
                        <th width="30%">Público / Representatividade social:</th>
                        <td><?= $pub['publico'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Sinopse:</th>
                        <td><?= $evento['sinopse'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Release</th>
                        <td><?= $evento['release_comunicacao'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                </table>
                <h1>Especificidades</h1>
                <h3>Ocorrências</h3>
                De <?= retornaPeriodoNovo($idEvento, 'ocorrencias') ?>
                <br>
                <tr>
                    <td><?= $evento['nome'] ?> (<?= $evento['sigla'] ?>)</td>
                </tr>
                <br>
                <br>
                <table class="table">
                    <tr>
                        <th width="30%">Evento de temporada</th>
                    </tr>
                    <tr>
                        <th width="30%">Data</th>
                        <td><?= retornaPeriodoNovo($idEvento, 'ocorrencias') ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Horário</th>
                        <td><?= $evento['horario_inicio'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
                        <td><?= $evento['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Retirada de ingressos:</th>
                        <td><?= $evento['retirada_ingresso'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Observações:</th>
                        <td><?= $evento['observacao'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Produtor responsavel:</th>
                        <td><?= $evento['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $evento['email'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $evento['telefone1'] ?></td>
                    </tr>
                </table>
                <h1>Arquivos Comunicação/Produção anexos</h1>
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
                        <?php
                        $tipo_pessoa = "SELECT pt.pessoa FROM
                        pedidos as p 
                        INNER JOIN pessoa_tipos pt on p.pessoa_tipo_id = pt.id
                        WHERE p.publicado = 1";
                        $tpQuerry = $con->query($tipo_pessoa)->fetch_assoc();
                        ?>
                        <th width="30%">Tipo de pessoa</th>
                        <td><?= $tpQuerry['pessoa'] ?></td>
                    </tr>
                    <tr>
                        <?php
                        $pedido = "SELECT * FROM PEDIDOS WHERE id = $idEvento AND publicado = 1";
                        $query = mysqli_query($con, $pedido);
                        $pessoa = mysqli_num_rows($query);

                        if ($pessoa['pessoa_tipo_id'] == 2) {
                            $pj = recuperaDados("pessoa_juridicas", "id", $pessoa['pessoa_juridica_id']);
                            echo "<td>" . $pj['razao_social'] . "</td>";
                        } else {
                            $pf = recuperaDados("pessoa_fisicas", "id", $pessoa['pessoa_fisica_id']);
                            echo "<td>" . $pf['nome'] . "</td>";
                        }
                        ?>
                    </tr>
                    <tr>
                        <th width="30%">Objeto</th>
                        <td><?= $evento['nome_evento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
<!--                        <td><?/*= $instituicao['nome'] */?> (<?/*= $instituicao['sigla'] */?>)</td>
-->                    </tr>
                    <tr>
                        <th width="30%">Valor</th>
                        <td><?= $pedidos['valor_total'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Forma de Pagamento</th>
                        <td><?= $pedidos['forma_pagamento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Data</th>
                        <td><?= retornaPeriodoNovo($idEvento, 'ocorrencias') ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Data de Emissão da N.E:</th>
                        <td><?= $pagamento['emissao_nota_empenho'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Data de Entrega da N.E</th>
                        <td><?= $pagamento['entrega_nota_empenho'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Dotação Orçamentária:</th>
                        <td><?= $dotacao['dotacao'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Observação:</th>
                        <td><?= $pedidos['observacao'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Último status:</th>
                        <td><?= $statusPedido['status'] ?></td>
                    </tr>
                </table>
                <br/>
                <div class="pull-left">
                    <a href="?perfil=juridico">
                        <button type="button" class="btn btn-default">Voltar a pesquisa</button>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblFormacao').DataTable({
            "language": {
                "url": 'bower_components/datatables.net/Portuguese-Brasil.json'
            },
            "responsive": true,
            "dom": "<'row'<'col-sm-6'l><'col-sm-6 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>",
        });
    });
</script>