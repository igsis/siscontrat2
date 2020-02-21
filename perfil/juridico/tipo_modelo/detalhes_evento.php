<?php

$con = bancoMysqli();
isset($_POST['idEvento']);
$idEvento = $_POST['idEvento'];

// para inserir a informação em Dotação //
$sql = "SELECT * FROM juridicos where pedido_id = '$idEvento'";
$query = mysqli_query($con, $sql);
$num = mysqli_num_rows($query);


// dados //
$evento = recuperaDados('eventos', 'id', $idEvento);
$espaco = recuperaDados('evento_publico', 'evento_id', $idEvento);
$publico = recuperaDados('publicos', 'id', $espaco['publico_id']);
$tipo_evento = recuperaDados('tipo_eventos', 'id', $evento['tipo_evento_id']);
$projeto_especiais = recuperaDados('projeto_especiais', 'id', $evento['projeto_especial_id']);
$relacao_juridica = recuperaDados('relacao_juridicas', 'id', $evento['relacao_juridica_id']);
$atracao = recuperaDados('atracoes', 'evento_id', $idEvento);
$acao_atracao = recuperaDados('acao_atracao', 'atracao_id', $atracao['id']);
$acao = recuperaDados('acoes', 'id', $acao_atracao['acao_id']);
$classificacao = recuperaDados('classificacao_indicativas', 'id', $atracao['classificacao_indicativa_id']);
$suplente = recuperaDados('usuarios', 'id', $evento['suplente_id']);
$ocorrencia = recuperaDados('ocorrencias', 'atracao_id', $atracao['id']);
$retirada_ingresso = recuperaDados('retirada_ingressos', 'id', $ocorrencia['retirada_ingresso_id']);
$pedidos = recuperaDados('pedidos', 'origem_id', $evento['id']);
$pagamento = recuperaDados('pagamentos', 'pedido_id', $pedidos['id']);
$statusPedido = recuperaDados('pedido_status', 'id', $pedidos['status_pedido_id']);
$produtor = recuperaDados('produtores', 'id', $atracao['produtor_id']);
$usuarios = recuperaDados('usuarios', 'id', $evento['usuario_id']);
$dataEvento = recuperaDados('evento_envios', 'evento_id', $idEvento);
$dotacao = recuperaDados('juridicos', 'pedido_id', $pedidos['id']);
$tipo_pessoa = recuperaDados('pessoa_tipos', 'id', $pedidos['pessoa_tipo_id']);

if ($evento['nome_responsavel'] != "") {
    $nome_resp = $evento['nome_responsavel'];
} else {
    $nome_resp = "Não cadastrado";
}

if ($evento['tel_responsavel'] != "") {
    $tel_resp = $evento['tel_responsavel'];
} else {
    $tel_resp = "Não cadastrado";
}

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
                        <td><?= exibirDataHoraBr($dataEvento['data_envio']) ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Tipo de evento:</th>
                        <td><?= $tipo_evento['tipo_evento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Projeto especial:</th>
                        <td><?= $projeto_especiais['projeto_especial'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Relação jurídica:</th>
                        <td><?= $relacao_juridica['relacao_juridica'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Usuário que cadastrou o evento:</th>
                        <td><?= $usuarios['nome_completo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $usuarios['telefone'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $usuarios['email'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Reponsável pelo evento:</th>
                        <td><?= $nome_resp ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $tel_resp ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $usuarios['email'] ?></td>
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
                        <th width="30%">Ficha técnica:</th>
                        <td><?= $atracao['ficha_tecnica'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Faixa ou indicação etária:</th>
                        <td><?= $classificacao['classificacao_indicativa'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Linguagem / Expressão artística:</th>
                        <td><?= $acao['acao'] ?></td>
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
                        <th width="30%">Release</th>
                        <td><?= $atracao['release_comunicacao'] ?></td>
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
                <?php
                $instituicao = recuperaDados('instituicoes', 'id', $ocorrencia['instituicao_id']);
                ?>
                <tr>
                    <td><?= $instituicao['nome'] ?> (<?= $instituicao['sigla'] ?>)</td>
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
                        <td><?= $ocorrencia['horario_inicio'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
                        <td><?= $instituicao['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Retirada de ingressos:</th>
                        <td><?= $retirada_ingresso['retirada_ingresso'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Observações:</th>
                        <td><?= $ocorrencia['observacao'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Produtor responsavel:</th>
                        <td><?= $produtor['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $produtor['email'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $produtor['telefone1'] ?></td>
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
                        <td><?= $pedidos['numero_processo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Tipo de pessoa</th>
                        <td>
                            <?php if ($tipo_pessoa == 1) {
                                echo "Física";
                            } else if ($tipo_pessoa == 2) {
                                echo "Jurídica";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%">Objeto</th>
                        <td><?= $evento['nome_evento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
                        <td><?= $instituicao['nome'] ?> (<?= $instituicao['sigla'] ?>)</td>
                    </tr>
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