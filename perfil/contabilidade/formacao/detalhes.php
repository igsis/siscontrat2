<?php
$idPedido = $_POST['idPedido'];

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2/";
$http = $server . "/pdf/";
$link = $http . "exporta_word_contabilidade_formacao.php";

$con = bancoMysqli();

$sql = "SELECT  p.id,
                p.origem_id,
                fc.protocolo,
		        p.numero_processo,
                pf.nome,
                p.valor_total,
                p.forma_pagamento,
                fc.ano,
                p.justificativa,
                fc.observacao,
                fc.data_envio
        FROM pedidos AS p
        INNER JOIN formacao_contratacoes AS fc ON fc.id = p.origem_id
        INNER JOIN pessoa_fisicas AS pf ON pf.id = p.pessoa_fisica_id
        WHERE p.id = '$idPedido' AND p.publicado = '1' AND p.origem_tipo_id = 2";
$pedido = $con->query($sql)->fetch_assoc();

$fc = recuperaDados('formacao_contratacoes', 'id', $pedido['origem_id']);
$fiscal = recuperaDados('usuarios', 'id', $fc['fiscal_id'])['nome_completo'];
$suplente = recuperaDados('usuarios', 'id', $fc['suplente_id'])['nome_completo'];
$programa = recuperaDados('programas', 'id', $fc['programa_id'])['programa'];
$linguagem = recuperaDados('linguagens', 'id', $fc['linguagem_id'])['linguagem'];

?>

<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Contabilidade - Formação</h2>
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
                            <th width="30%">Proponente:</th>
                            <td><?= $pedido['nome'] ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Programa & Linguagem:</th>
                            <td><?= $programa . " - " . $linguagem ?></td>
                        </tr>

                        <?php
                        $idFc = $fc['id'];
                        $sqlLocal = "SELECT l.local FROM formacao_locais fl INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFc'";
                        $local = "";
                        $queryLocal = mysqli_query($con, $sqlLocal);
                        while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
                            $local = $local . $linhaLocal['local'] . ' | ';
                        }
                        $local = substr($local, 0, -2);
                        ?>

                        <tr>
                            <th width="30%">Local(s):</th>
                            <td><?= $local ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Valor:</th>
                            <td><?= "R$" . dinheiroParaBr($pedido['valor_total']) ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Forma de Pagamento:</th>
                            <td><?= $pedido['forma_pagamento'] ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Ano:</th>
                            <td><?= $pedido['ano'] ?></td>
                        </tr>

                        <?php
                        $idVigencia = $fc['form_vigencia_id'];

                        $carga = null;
                        $sqlCarga = "SELECT carga_horaria FROM formacao_parcelas WHERE formacao_vigencia_id = '$idVigencia'";
                        $queryCarga = mysqli_query($con, $sqlCarga);

                        while ($countt = mysqli_fetch_array($queryCarga))
                            $carga += $countt['carga_horaria'];
                        ?>

                        <tr>
                            <th width="30%">Carga Horária:</th>
                            <td><?= $carga ?></td>
                        </tr>

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
                            <td><?= checaCampo($pedido['observacao']) ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Data do Cadastro:</th>
                            <td><?= exibirDataBr($pedido['data_envio']) ?></td>
                        </tr>

                    </table>
                </div>
            </div>
            <div class="box-footer">
                <form action="<?= $link ?>" role="form" target="_blank" method="POST">
                    <a href="?perfil=contabilidade&p=formacao&sp=pesquisa">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <input type="hidden" value="<?= $pedido['id'] ?>" id="idPedido" name="idPedido">
                    <button type="submit" class="btn btn-success pull-right">Gerar Word</button>
                </form>
            </div>
        </div>
    </section>
</div>
