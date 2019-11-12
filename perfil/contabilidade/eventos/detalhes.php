<?php
$idPedido = $_POST['idPedido'];

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2/";
$http = $server . "/pdf/";
$linkpf = $http . "exporta_word_contabilidade_pf.php";
$linkpj = $http . "exporta_word_contabilidade_pj.php";

$con = bancoMysqli();
$sql = "SELECT  e.id AS 'idEvento',
                e.nome_evento,
                e.tipo_evento_id,
                p.id,
                i.nome,
                p.origem_id,
                
                e.protocolo,
		        p.numero_processo,
                p.pessoa_tipo_id,
                p.pessoa_fisica_id,
                p.pessoa_juridica_id,
                p.valor_total,
                p.forma_pagamento,
                p.justificativa,
                l.local,
                o.atracao_id,
                e.fiscal_id,
                e.suplente_id,
                p.observacao
        FROM pedidos AS p
        INNER JOIN eventos AS e ON e.id = p.origem_id
        INNER JOIN pessoa_fisicas AS pf ON pf.id = p.pessoa_fisica_id
        INNER JOIN ocorrencias AS o ON e.id = o.origem_ocorrencia_id
        INNER JOIN locais AS l ON l.id = o.local_id
        INNER JOIN instituicoes AS i ON o.instituicao_id = i.id
        WHERE p.id = '$idPedido' AND p.publicado = 1 AND e.publicado = 1";
$pedido = $con->query($sql)->fetch_assoc();


$fiscal = recuperaDados('usuarios', 'id', $pedido['fiscal_id'])['nome_completo'];
$suplente = recuperaDados('usuarios', 'id', $pedido['suplente_id'])['nome_completo'];

?>

<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Contabilidade - Eventos</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Detalhes do pedido selecionado</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th width="30%">Protocolo:</th>
                            <td><?= $pedido['protocolo'] ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Número do Processo:</th>
                            <td><?= $pedido['numero_processo'] ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Setor:</th>
                            <td><?= $pedido['nome'] ?></td>
                        </tr>

                        <?php
                        if ($pedido['pessoa_tipo_id'] == 1) {
                            $pessoa = recuperaDados("pessoa_fisicas", 'id', $pedido['pessoa_fisica_id'])['nome'];
                            $link = $linkpf;
                        } else if ($pedido['pessoa_tipo_id'] == 2) {
                            $pessoa = recuperaDados("pessoa_juridicas", 'id', $pedido['pessoa_juridica_id'])['razao_social'];
                            $link = $linkpj;
                        }
                        ?>

                        <tr>
                            <th width="30%">Proponente:</th>
                            <td><?= $pessoa ?></td>
                        </tr>

                        <?php
                        $objeto = retornaTipo($pedido['tipo_evento_id']) . " - " . $pedido['nome_evento'];
                        ?>

                        <tr>
                            <th width="30%">Objeto</th>
                            <td><?= $objeto ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Local:</th>
                            <td><?= $pedido['local'] ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Valor:</th>
                            <td><?= $pedido['valor_total'] ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Forma de Pagamento:</th>
                            <td><?= $pedido['forma_pagamento'] ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Período:</th>
                            <td><?= retornaPeriodoNovo($pedido['origem_id'], 'ocorrencias') ?></td>
                        </tr>

                        <?php
                        $idAtracao = $pedido['atracao_id'];
                        $sqlCheca = "SELECT oficina FROM atracoes WHERE id = '$idAtracao'";
                        $checa = $con->query($sqlCheca)->fetch_array();

                        if ($checa['oficina'] == 1) {
                            $sqlCarga = "SELECT carga_horaria FROM oficinas WHERE atracao_id = '$idAtracao'";
                            $carga = $con->query($sqlCarga)->fetch_array();
                            echo "<tr>
                                    <th width='30%'>Carga Horária:</th>
                                    <td>" . $carga['carga_horaria'] . "</td>
                                  </tr>";
                        } else if ($checa['oficina'] == 0) {
                            echo "<tr>
                                    <th width='30%'>Não se aplica.</th>
                                    </tr>";
                        }

                        ?>

                        <tr>
                            <th width="30%">Justificativa:</th>
                            <td><?= $pedido['justificativa'] ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Fiscal:</th>
                            <td><?= $fiscal ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Suplente:</th>
                            <td><?= $suplente ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Observação:</th>
                            <td><?= $pedido['observacao'] ?></td>
                        </tr>

                        <?php
                        $idEvento = $pedido['idEvento'];
                        $sqlEnvio = "SELECT data_envio FROM  evento_envios WHERE evento_id = '$idEvento'";
                        $dia = $con->query($sqlEnvio)->fetch_array();
                        ?>

                        <tr>
                            <th width="30%">Data do Cadastro:</th>
                            <td><?= exibirDataBr($dia['data_envio']) ?></td>
                        </tr>

                    </table>
                </div>
            </div>
            <div class="box-footer">
                <form action="<?= $link ?>" role="form" target="_blank" method="POST">
                    <a href="?perfil=contabilidade&p=eventos&sp=pesquisa">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <input type="hidden" value="<?= $pedido['id'] ?>" id="idPedido" name="idPedido">
                    <button type="submit" class="btn btn-success pull-right">Gerar Word</button>
                </form>
            </div>
        </div>
    </section>
</div>

