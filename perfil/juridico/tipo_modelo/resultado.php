<?php
$con = bancoMysqli();
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2";
$http = $server . "/pdf/";

if (isset($_POST['tipoModelo'])) {
    $modelo = $_POST['tipoModelo'];
}
if (isset($_POST['idEvento'])) {
    $idEvento = $_POST['idEvento'];
}

if (isset($_POST['gravar'])) {
    $idPedido = $_POST['idPedido'];
    $idEvento = $_POST['idEvento'];
    $amparo = $_POST['amparo'];
    $dotacao = $_POST['dotacao'];
    $finalizacao = $_POST['finalizacao'];

    $sql = "SELECT * FROM juridicos where pedido_id = '$idPedido'";
    $query = mysqli_query($con, $sql);
    $num = mysqli_num_rows($query);
    if ($num > 0) {
        $sqlUptate = "UPDATE juridicos SET amparo_legal = '$amparo', finalizacao = '$finalizacao', dotacao = '$dotacao' WHERE pedido_id = $idPedido";
        if (mysqli_query($con, $sqlUptate)) {
            $mensagem = mensagem("success", "Gravado com sucesso!");
        } else {
            $mensagem = mensagem("success", "Erro ao gravar");
        }
    } else {
        $sqlInsert = "INSERT INTO juridicos(pedido_id, amparo_legal, finalizacao, dotacao) VALUES ('$idPedido','$amparo','$finalizacao','$dotacao')";
        if (mysqli_query($con, $sqlInsert)) {
            $mensagem = mensagem("success", "Gravado com sucesso!");
        } else {
            $mensagem = mensagem("success", "Erro ao gravar");
        }
    }

    //insere ou atualiza a tabela de pedido e de pedido_etapas
    $update = "UPDATE pedidos SET status_pedido_id = 15 WHERE id = $idPedido";
    if (mysqli_query($con, $update)) {
        $testaEtapa = $con->query("SELECT pedido_id, data_juridico FROM pedido_etapas WHERE pedido_id = $idPedido")->fetch_assoc();
        $data = dataHoraNow();
        if ($testaEtapa == NULL) {
            $insereEtapa = $con->query("INSERT INTO pedido_etapas (pedido_id, data_juridico) VALUES ('$idPedido', '$data')");
        } else if ($testaEtapa != NULL && $testaEtapa['data_juridico'] == "0000-00-00 00:00:00" || $testaEtapa['data_juridico'] != "0000-00-00 00:00:00") {
            $updateEtapa = $con->query("UPDATE pedido_etapas SET data_juridico = '$data' WHERE pedido_id = '$idPedido'");
        }
    }
}

$mdl = recuperaDados('modelo_juridicos', 'id', $modelo);
$eve = recuperaDados('eventos', 'id', $idEvento);

$sqlLocal = "SELECT e.id,l.local FROM eventos as e
             INNER JOIN ocorrencias as o on o.origem_ocorrencia_id = e.id
             INNER JOIN locais as l on o.local_id = l.id
             WHERE e.publicado = 1 AND e.id = '$idEvento'";
$queryLocal = mysqli_query($con, $sqlLocal);
$local = '';
while ($locais = mysqli_fetch_array($queryLocal)) {
    $local = $local . " - " . $locais['local'];
}
$local = substr($local, 3);


$fiscal = recuperaDados('usuarios', 'id', $eve['fiscal_id'])['nome_completo'];
$suplente = recuperaDados('usuarios', 'id', $eve['suplente_id'])['nome_completo'];
$rfFiscal = recuperaDados('usuarios', 'id', $eve['fiscal_id'])['rf_rg'];
$rfSuplente = recuperaDados('usuarios', 'id', $eve['suplente_id'])['rf_rg'];
$mdl = str_replace("nomeFiscal", $fiscal, $mdl);
$mdl = str_replace("rfFiscal", $rfFiscal, $mdl);
$mdl = str_replace("nomeSuplente", $suplente, $mdl);
$mdl = str_replace("rfSuplente", $rfSuplente, $mdl);


$sql = "select p.numero_processo, p.id as pedido_id,
e.protocolo,
p.valor_total,
p.forma_pagamento,
e.id,
p.pessoa_tipo_id,
p.pessoa_fisica_id,
p.pessoa_juridica_id
from pedidos as p
inner join eventos as e on e.id = p.origem_id
 AND e.publicado = 1
AND p.status_pedido_id != 1
AND p.status_pedido_id != 3
AND p.publicado = 1 where e.id = $idEvento";
$evento = $con->query($sql)->fetch_array();

$dotacao = "";
$escondeBotao = 1;
$consultaDotacao = $con->query("SELECT dotacao FROM juridicos WHERE pedido_id = " . $evento['pedido_id']);
if ($consultaDotacao->num_rows > 0) {
    $dotacao = mysqli_fetch_array($consultaDotacao)['dotacao'];
    $escondeBotao = 0;
}

?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Jurídico</h2>
        </div>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Detalhes do evento selecionado</h3>
            </div>
            <div class="box-body">
                <table class="table">
                    <tr>
                        <th width="30%">Protocolo:</th>
                        <td><?= $evento['protocolo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Número do Processo:</th>
                        <td><?= $evento['numero_processo'] ?></td>
                    </tr>
                    <tr>
                        <?php
                        if ($evento['pessoa_tipo_id'] == 1) {
                            $tipo = "Física";
                            $pessoa = recuperaDados("pessoa_fisicas", "id", $evento ['pessoa_fisica_id'])['nome'];
                        } else if ($evento['pessoa_tipo_id'] == 2) {
                            $tipo = "Jurídico";
                            $pessoa = recuperaDados('pessoa_juridicas', "id", $evento['pessoa_juridica_id'])['razao_social'];
                        } ?>
                        <th width="30%">Contratado:</th>
                        <td><?= $pessoa ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local(ais):</th>
                        <td><?= $local ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Valor:</th>
                        <td><?= "R$" . dinheiroParaBr($evento['valor_total']) ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Período:</th>
                        <td><?= retornaPeriodoNovo($idEvento, 'ocorrencias'); ?></td>

                    </tr>
                    <tr>
                        <th width="30%">Forma de pagamento:</th>
                        <td><?= $evento['forma_pagamento'] ?></td>
                    </tr>
                    <form action="?perfil=juridico&p=tipo_modelo&sp=resultado" role="form" method="post">
                        <tr>
                            <th width="30%">Amparo:</th>
                            <td><textarea class="form-control" name="amparo" rows="6"
                                          cols="85"><?= $mdl['amparo'] ?></textarea></td>
                        </tr>
                        <tr>
                            <th width="30%">Dotação Orçamentária:</th>
                            <td><textarea class="form-control" name="dotacao" rows="1"
                                          cols="85"><?= $dotacao ?? NULL ?></textarea></td>
                        </tr>
                        <tr>
                            <th width="30%">Finalização:</th>
                            <td><textarea class="form-control" name="finalizacao" rows="8"
                                          cols="85"><?= $mdl['finalizacao'] ?></textarea>
                            </td>
                        </tr>
                </table>
            </div>
            <div class="box-footer">
                <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                <input type="hidden" name="idPedido" value="<?= $evento['pedido_id'] ?>">
                <input type="hidden" name="tipoModelo" value="<?= $modelo ?>">
                <button type="submit" name="gravar" class="btn btn-primary pull-right">Gravar
                </button>
                </form>
                <form action="?perfil=juridico&p=tipo_modelo&sp=detalhes_evento" role="form" method="post">
                    <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                    <input type="hidden" name="tipoModelo" value="<?= $modelo ?>">
                    <button type="submit" class="btn btn-primary pull-left">Detalhes evento</button>
                </form>
            </div>
            <?php if ($escondeBotao == 0) { ?>
                <div class="box-footer">
                    <form action='?perfil=juridico&p=tipo_modelo&sp=dados_modelo' method='post'>
                        <input type='hidden' name='idEvento' value='<?= $idEvento ?>'>
                        <input type='hidden' name='idPedido' value='<?= $evento['pedido_id'] ?>'>
                        <input type="hidden" name="tipoModelo" value="<?= $modelo ?>">
                        <button name='doc' class='btn btn-success center-block' style='width: 30%' type='submit'>Gerar
                            documentos
                        </button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </section>
</div>