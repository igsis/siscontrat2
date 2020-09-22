<?php
$con = bancoMysqli();
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2";


if (isset($_POST['tipoModelo'])) {
    $modelo = $_POST['tipoModelo'];
}

if (isset($_POST['idFormacao'])) {
    $idFormacao = $_POST['idFormacao'];
}

if (isset($_POST['gravar'])) {
    $idPedido = $_POST['idPedido'];
    $amparo = $_POST['amparo'];
    $finalizacao = $_POST['finalizacao'];
    $dotacao = $_POST['dotacao'];

    /// inserindo dados dentro de juridicos ///
    $sql = "SELECT * FROM juridicos where pedido_id = '$idPedido'";
    $query = mysqli_query($con, $sql);
    $num = mysqli_num_rows($query);
    if ($num > 0) {
        $sqlUptate = "UPDATE juridicos SET pedido_id = $idPedido, amparo_legal = '$amparo', finalizacao = '$finalizacao', dotacao ='$dotacao'
    WHERE pedido_id = $idPedido";
        if (mysqli_query($con, $sqlUptate)) {
            $mensagem = mensagem("success", "Gravado com sucesso!");
        } else {
            $mensagem = mensagem("success", "Erro ao gravar");
        }
    } else {
        $sqlInsert = "INSERT INTO juridicos(pedido_id, amparo_legal, finalizacao, dotacao)
        VALUES ('$idPedido','$amparo','$finalizacao','$dotacao')";
        if (mysqli_query($con, $sqlInsert)) {
            $mensagem = mensagem("success", "Gravado com sucesso!");
        } else {
            $mensagem = mensagem("success", "Erro ao gravar");
        }
    }
}

$sqlModelo = "SELECT * FROM modelo_juridicos WHERE id = $modelo";
$mdl = $con->query($sqlModelo)->fetch_assoc();

$sql = "
SELECT p.id, p.numero_processo, fc.protocolo, pf.nome, p.origem_id, p.forma_pagamento, p.valor_total, fc.suplente_id, fc.fiscal_id
FROM pedidos AS p
INNER JOIN formacao_contratacoes AS fc ON p.origem_id = fc.id
INNER JOIN pessoa_fisicas AS pf ON p.pessoa_fisica_id = pf.id
WHERE fc.id = '$idFormacao' AND p.origem_tipo_id = 2 AND p.publicado = 1";

$query = $con->query($sql)->fetch_assoc();

$idPedido = $query['id'];

// pegar periodo da formação ( atraves do id da vigencia )
$fc = recuperaDados('formacao_contratacoes', 'id', $query['origem_id']);
$periodo = retornaPeriodoFormacao_Emia($fc['form_vigencia_id'], "formacao");


// pegar o local
$sqlLocal = "SELECT l.local FROM formacao_locais fl 
INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFormacao'";
$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

// insere o nome fiscal no texto que apresenta na finalização
$fiscal = recuperaDados('usuarios', 'id', $query['fiscal_id']);

$mdl = str_replace("nomeFiscal", $fiscal["nome_completo"], $mdl);
$mdl = str_replace("rfFiscal", $fiscal["rf_rg"], $mdl);

$nomeSuplente = "";
$rfSuplente = "";
if ($query['suplente_id'] != 0) {
    $suplente = recuperaDados('usuarios', 'id', $query['suplente_id']);
    if ($suplente) {
        $mdl = str_replace("nomeSuplente", $suplente["nome_completo"], $mdl);
        $mdl = str_replace("rfSuplente", $suplente["rf_rg"], $mdl);
    }
}

$escondeBotao = 1;

$dotacao = "";
$consultaDotacao = $con->query("SELECT dotacao FROM juridicos WHERE pedido_id = $idPedido");
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
                <h3 class="box-title">Detalhes da formação selecionada</h3>
            </div>
            <div class="box-body">
                <table class="table">
                    <tr>
                        <th width="30%">Protocolo:</th>
                        <td><?= $query['protocolo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Número do Processo:</th>
                        <td><?= $query['numero_processo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Contratado:</th>
                        <td><?= $query['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local:</th>
                        <td>
                            <?php
                            while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
                                $local = $local . $linhaLocal['local'] . ' - ';
                            }

                            $local = substr($local, 0, -3);
                            echo $local;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%">Valor:</th>
                        <td><?= "R$ " . number_format($query['valor_total'], 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Período:</th>
                        <td><?= $periodo ?></td>

                    </tr>
                    <tr>
                        <th width="30%">Forma de pagamento:</th>
                        <td><?= $query['forma_pagamento'] ?></td>
                    </tr>
                    <form action="?perfil=juridico&p=filtrar_formacao&sp=resumo_formacao" role="form" method="post">
                        <tr>
                            <th width="30%">Amparo:</th>
                            <td><textarea name="amparo" rows="6"
                                          cols="85" class="form-control"><?= $mdl['amparo'] ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th width="30%">Dotação Orçamentária</th>
                            <td><textarea name="dotacao" rows="1"
                                          cols="85" class="form-control"><?= $dotacao ?? NULL ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th width="30%">Finalização:</th>
                            <td><textarea name="finalizacao" rows="8"
                                          cols="85" class="form-control"><?= $mdl['finalizacao'] ?></textarea>
                            </td>
                        </tr>
                </table>
                <input type="hidden" name="idFormacao" value="<?= $idFormacao ?>">
                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                <input type="hidden" name="tipoModelo" value="<?= $modelo ?>">
                <button type="submit" name="gravar" class="btn btn-info pull-right">Gravar
                </button>
                </form>
                <form action="?perfil=juridico&p=filtrar_formacao&sp=detalhe_formacao" method="post">
                    <input type="hidden" name="idFormacao" value="<?= $idFormacao ?>">
                    <input type="hidden" name="tipoModelo" value="<?= $modelo ?>">
                    <button type="submit" name="detalhe" class="btn btn-info pull-left">Detalhe Formação
                    </button>
                </form>
            </div>
            <?php if ($escondeBotao == 0) { ?>
                <div class="box-footer">
                    <form action='?perfil=juridico&p=filtrar_formacao&sp=modelo_final_formacao' method='post'
                          target="_blank">
                        <input type='hidden' name='idFormacao' value='<?= $idFormacao ?>'>
                        <input type='hidden' name='idPedido' value='<?= $idPedido ?>'>
                        <button name='doc' class='btn btn-success center-block' style='width: 30%' type='submit'>Gerar
                            documentos
                        </button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </section>
</div>
