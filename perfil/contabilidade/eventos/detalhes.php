<?php

$idPedido = $_POST['idPedido'];

$con = bancoMysqli();
$sql = "SELECT  e.id AS 'idEvento',
                e.nome_evento,
                e.tipo_evento_id,
                p.id,
                i.nome,
                e.protocolo,
		        p.numero_processo,
                p.pessoa_tipo_id,
                p.pessoa_fisica_id,
                p.pessoa_juridica_id,
                p.valor_total,
                p.forma_pagamento,
                p.justificativa,
                o.atracao_id,
                e.fiscal_id,
                e.suplente_id,
                p.observacao
        FROM pedidos AS p
        INNER JOIN eventos AS e ON e.id = p.origem_id
        INNER JOIN ocorrencias AS o ON e.id = o.origem_ocorrencia_id
        INNER JOIN instituicoes AS i ON o.instituicao_id = i.id 
        WHERE p.id = '$idPedido' AND p.publicado = 1 AND e.publicado = 1";
$pedido = $con->query($sql)->fetch_array();

$sqlLocal = "SELECT l.local FROM locais l INNER JOIN ocorrencias o ON o.local_id = l.id WHERE o.origem_ocorrencia_id = " . $pedido['idEvento'] . " AND o.publicado = 1";
$queryLocal = mysqli_query($con, $sqlLocal);
$local = '';
while ($locais = mysqli_fetch_array($queryLocal)) {
    $local = $local . '; ' . $locais['local'];
}
$local = substr($local, 1);

$fiscal = recuperaDados('usuarios', 'id', $pedido['fiscal_id'])['nome_completo'];
$suplente = recuperaDados('usuarios', 'id', $pedido['suplente_id'])['nome_completo'];

$btnWord = "";

if(isset($_POST['gravar'])){
    $server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2/";
    $http = $server . "/pdf/";

    //confere qual link correto baseado no tipo da pessoa
    if($pedido['pessoa_tipo_id'] == 1){
        $link = $http . "exporta_word_contabilidade_pf.php";
    }else{
        $link = $http . "exporta_word_contabilidade_pj.php";
    }

    $cadastra = $con->query("UPDATE pedidos SET status_pedido_id = 17 WHERE id = '$idPedido'");
    if($cadastra){
        $existeEtapa = $con->query("SELECT pedido_id, data_contabilidade FROM pedido_etapas WHERE pedido_id = '$idPedido'")->fetch_assoc();
        $hoje = dataHoraNow();
        if($existeEtapa != NULL && $existeEtapa['data_contabilidade'] == "0000-00-00 00:00:00"){
            $con->query("UPDATE pedido_etapas SET data_contabilidade = '$hoje' WHERE pedido_id = '$idPedido'");
        }
        if($existeEtapa == NULL){
            $con->query("INSERT INTO pedido_etapas (pedido_id,data_contabilidade) VALUES ('$idPedido','$hoje')");
        }
        $mensagem = mensagem("success", "Cadastrado com sucesso!");

        $btnWord = "<hr>
                    <div class='col-md-12'>
                        <form action='$link' method='POST' target='_blank'>
                            <input type='hidden' value='" .  $pedido['id'] . "' id='idPedido' name='idPedido'>
                            <button style='width:25%' type='submit' class='btn btn-success btn-block center-block'>Gerar Word</button>
                        </form>
                    </div>";
    } else{
        $mensagem = mensagem("danger", "Erro ao cadastrar.");
    }
}

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
            <div class="row" align="center">
                <?= $mensagem ?? NULL; ?>
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
                        } else if ($pedido['pessoa_tipo_id'] == 2) {
                            $pessoa = recuperaDados("pessoa_juridicas", 'id', $pedido['pessoa_juridica_id'])['razao_social'];
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
                            <th width="30%">Objeto:</th>
                            <td><?= $objeto ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Local(ais):</th>
                            <td><?= $local ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Valor:</th>
                            <td><?= "R$". dinheiroParaBr($pedido['valor_total']) ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Forma de Pagamento:</th>
                            <td><?= $pedido['forma_pagamento'] ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Período:</th>
                            <td><?= retornaPeriodoNovo($pedido['idEvento'], 'ocorrencias') ?></td>
                        </tr>

                        <?php
                        $idAtracao = $pedido['atracao_id'];
                        $sqlCheca = $con->query("SELECT * FROM acao_atracao WHERE atracao_id = '$idAtracao' AND acao_id = 8");
                        $checa = mysqli_num_rows($sqlCheca);

                        if ($checa != 0) {
                            $sqlCarga = "SELECT carga_horaria FROM oficinas WHERE atracao_id = '$idAtracao'";
                            $carga = $con->query($sqlCarga)->fetch_array();
                            echo "<tr>
                                    <th width='30%'>Carga Horária:</th>
                                    <td>" . $carga['carga_horaria'] . " Hora(s)" ."</td>
                                  </tr>";
                        } else if ($checa == 0) {
                            echo "<tr>
                                    <th width='30%'>Carga Horária:</th>
                                    <td>Não se aplica.</td>
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
                            <td><?= $pedido['observacao'] == NULL ? "Não cadastrado" : $pedido['observacao'] ?></td>
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
                <form action="?perfil=contabilidade&p=eventos&sp=detalhes" role="form" method="POST">
                    <a href="?perfil=contabilidade&p=eventos&sp=pesquisa">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <input type="hidden" value="<?= $pedido['id'] ?>" id="idPedido" name="idPedido">
                    <button type="submit" name="gravar" class="btn btn-primary pull-right">Gravar</button>
                </form>
                <?= $btnWord ?>
            </div>
        </div>
    </section>
</div>

