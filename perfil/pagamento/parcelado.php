<?php
$con = bancoMysqli();

$link_api = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_docs_pagamentos.php';

$idPedido = $_POST['idPedido'];
$botao = false;

if(isset($_POST['operador'])){
    $operador = $_POST['operador'];
    $data_kit_pagamento = $_POST['data_kit_pagamento'];
    $sql = "UPDATE pedidos SET operador_pagamento_id = '$operador', data_kit_pagamento = '$data_kit_pagamento' WHERE id = '$idPedido'";
    $cadastra = $con->query($sql);
    if($cadastra){
        $botao = true;
        $existeEtapa = $con->query("SELECT pedido_id, data_pagamento FROM pedido_etapas WHERE pedido_id = '$idPedido'")->fetch_assoc();
        $now = dataHoraNow();
        if($existeEtapa != NULL && $existeEtapa['data_pagamento'] == NULL){
            $con->query("UPDATE pedido_etapas SET data_pagamento = '$now' WHERE pedido_id = '$idPedido'");
        }
        if($existeEtapa == NULL){
            $con->query("INSERT INTO pedido_etapas (pedido_id,data_pagamento) VALUES ('$idPedido','$now')");
        }
        $mensagem = mensagem("success", "Cadastrado com sucesso!");
    } else{
        $mensagem = mensagem("danger", "Erro ao cadastrar.");
    }
}

if(isset($_POST['cadastrar'])){
    $cadastra = $con->query("UPDATE pedidos SET status_pedido_id = 19 WHERE id = '$idPedido'");
    if($cadastra){
        $botao = true;
        $existeEtapa = $con->query("SELECT pedido_id, data_pagamento FROM pedido_etapas WHERE pedido_id = '$idPedido'")->fetch_assoc();
        $now = dataHoraNow();
        if($existeEtapa != NULL && $existeEtapa['data_pagamento'] == NULL){
            $con->query("UPDATE pedido_etapas SET data_pagamento = '$now' WHERE pedido_id = '$idPedido'");
        }
        if($existeEtapa == NULL){
            $con->query("INSERT INTO pedido_etapas (pedido_id,data_pagamento) VALUES ('$idPedido','$now')");
        }
        $mensagem = mensagem("success", "Cadastrado com sucesso!");
    } else{
        $mensagem = mensagem("danger", "Erro ao cadastrar.");
    }
}

if(isset($_POST['concluir'])){
    $cadastra = $con->query("UPDATE pedidos SET status_pedido_id = 21 WHERE id = '$idPedido'");
    if($cadastra){
        $botao = true;
        $mensagem = mensagem("success", "Evento concluído com sucesso!");
        echo "<meta http-equiv='refresh' content='3;url=?perfil=pagamento' />";
    } else{
        $mensagem = mensagem("danger", "Erro ao concluir evento.");
    }
}

$sql = "SELECT e.id, p.id AS idPedido, e.protocolo, p.numero_processo, p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, p.forma_pagamento, p.valor_total, ps.status, u.nome_completo, p.data_kit_pagamento, uf.nome_completo AS fiscal, us.nome_completo AS suplente, p.operador_pagamento_id
    FROM eventos e 
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN pedido_status ps on p.status_pedido_id = ps.id
    LEFT JOIN usuario_pagamentos up on p.operador_pagamento_id = up.usuario_id
    LEFT JOIN usuarios u on up.usuario_id = u.id
    LEFT JOIN usuarios uf on e.fiscal_id = uf.id
    LEFT JOIN usuarios us on e.suplente_id = us.id
    WHERE e.publicado = 1 
    AND p.publicado = 1 
    AND p.origem_tipo_id = 1
    AND e.evento_status_id = 3
    AND p.status_pedido_id NOT IN (1,3,20,21)
    AND p.id = '$idPedido'";
$pedido = $con->query($sql)->fetch_array();
if ($pedido['pessoa_tipo_id'] == 2) {
    $idPj = $pedido['pessoa_juridica_id'];
    $proponente = $con->query("SELECT razao_social FROM pessoa_juridicas WHERE id = '$idPj'")->fetch_assoc()['razao_social'];
} else {
    $idPf = $pedido['pessoa_fisica_id'];
    $proponente = $con->query("SELECT nome FROM pessoa_fisicas WHERE id = '$idPf'")->fetch_assoc()['nome'];
}

$idUser = $_SESSION['idUser'];
$acesso = $con->query("SELECT * FROM usuario_pagamentos WHERE usuario_id = '$idUser'")->fetch_array();
?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3>Pagamento</h3>
        </div>
        <div class="box">
            <div class="box-header">
                <h2 class="box-title">Cadastro de Pagamento</h2>
            </div>
            <div class="row" align="center">
                <?= $mensagem ?? NULL; ?>
            </div>
            <div class="box-body">
                <?php
                if($acesso['nivel_acesso'] == 1 || $acesso['nivel_acesso'] == 2) {
                    ?>
                    <div class="row">
                        <form action="#" method="post" role="form">
                            <div class="col-md-5 from-group">
                                <label for="operador">Operador</label>
                                <select name="operador" id="operador" class="form-control" required>
                                    <option value="">Selecione um operador</option>
                                    <?php
                                    geraOpcao('usuarios u INNER JOIN usuario_pagamentos up on up.usuario_id = u.id', $pedido['operador_pagamento_id']);
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-5 form-group">
                                <label for="data_kit_pagamento">Data de Entrega do Kit de Pagamentos:</label>
                                <input type="date" class="form-control" name="data_kit_pagamento"
                                       id="data_kit_pagamento" value="<?= $pedido['data_kit_pagamento'] ?>" required>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label><br>
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-primary btn-block">Gravar</button>
                            </div>
                        </form>
                    </div>
                    <hr>
                    <?php
                }
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <label>Protocolo:</label> <?= $pedido['protocolo'] ?>
                    </div>
                    <div class="col-md-6">
                        <label>Número de processo:</label> <?= $pedido['numero_processo'] ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Proponente:</label> <?= $proponente ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Nome do evento:</label> <?= $pedido['nome_evento'] ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Local:</label> <?php retornaLocal($pedido['id']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Período:</label> <?= retornaPeriodoNovo($pedido['id'], 'ocorrencias') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Forma de pagamento:</label> <?= $pedido['forma_pagamento'] ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>Valor:</label> R$ <?= dinheiroParaBr($pedido['valor_total']) ?>
                    </div>
                    <div class="col-md-4">
                        <label>Fiscal:</label> <?= $pedido['fiscal'] ?>
                    </div>
                    <div class="col-md-4">
                        <label>Suplente:</label> <?= $pedido['suplente'] ?>
                    </div>
                </div>

                <br>

                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>Nº Parcela</th>
                        <th>Valor</th>
                        <th>Data de pagamento</th>
                        <th>Documentos</th>
                    </tr>
                    </thead>
<!--                    <tbody>-->
                    <?php
                    $sqlParcela = $con->query("SELECT id, numero_parcelas, valor, data_pagamento FROM parcelas WHERE pedido_id = '$idPedido' AND publicado = 1 GROUP BY numero_parcelas ORDER BY numero_parcelas");
                    while($parcela = mysqli_fetch_array($sqlParcela)){
                        ?>
                        <tr>
                            <td><?= $parcela['numero_parcelas'] ?></td>
                            <td><?= dinheiroParaBr($parcela['valor']) ?></td>
                            <td><?= exibirDataBr($parcela['data_pagamento']) ?></td>
                            <td width="15%">
                                <button type="button" class="btn btn-primary btn-block" id="exibirDocumentos"
                                                data-toggle="modal" data-target="#modalDocumento"
                                                data-id="<?= $parcela['id'] ?>"
                                                name="exibirDocumentos"><span class="glyphicon glyphicon-paperclip"></span>
                                            
                                </button>
                            </td>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>

            </div>
            <div class="box-footer">
                <form action="#" method="post" role="form">
                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                    <button type="submit" class="btn btn-warning pull-left" name="concluir">Concluir evento</button>
                    <button type="submit" class="btn btn-primary pull-right" name="cadastrar">Gravar</button>
                </form>
            </div>
        </div>

    </section>
</div>

<div id="modalDocumento" class="modal modal fade in" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Lista de Documentos</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered">
                    <tbody id="conteudoModal">
                            
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const link = `<?=$link_api?>`;
    const tipo = `<?=$pedido['pessoa_tipo_id']?>`;
    const idPedido = `<?=$idPedido?>`;
    $('#modalDocumento').on('show.bs.modal', function (e) {
        $('#modalDocumento').find('#conteudoModal').empty();
        let id = $(e.relatedTarget).attr('data-id');
       $.ajax({
            method: "GET",
            url: link + "?idParcela=" + id + "&tipo=" + tipo + "&idPedido=" + idPedido
       })
       .done(function (content){
        $('#modalDocumento').find('#conteudoModal').append(`<tr><td>${content}</td></tr>`);
       });
    })
</script>