<?php
$con = bancoMysqli();
$idEvento = $_POST['idEvento'];

$evento = recuperaDados('eventos', 'id', $idEvento);
$pedido = recuperaDados('pedidos', 'origem_id', $idEvento . " AND origem_tipo_id = 1");

$idPedido = $pedido['id'];
if (isset($_POST['salvar'])) {
    if ($nivelUsuario == 1) { // alterar o operador e/ou o status do pedido
        $operador = $_POST['operador'];
        $status = $_POST['status'];

        $sql = "UPDATE contratos SET usuario_contrato_id = '$operador' WHERE pedido_id = '$idPedido'";
        if (mysqli_query($con, $sql))
            gravarLog($sql);

        $sql = "UPDATE pedidos SET status_pedido_id = '$status' WHERE id = '$idPedido'";
        if (mysqli_query($con, $sql))
            gravarLog($sql);
    }

    $idAtracao = $_POST['idAtracao'];
    $nome_atracao = $_POST['nome_atracao'];
    $integrantes = $_POST['integrantes'];

    for ($i = 0; $i < count($idAtracao); $i++) { // altera de uma ou de todas as atracoes (nome da atracao e integrantes)
        $baldeId = $idAtracao[$i];
        $baldeNome = $nome_atracao[$i];
        $baldeIntegrantes = $integrantes[$i];

        $sql = "UPDATE atracoes SET 
                    nome_atracao = '$baldeNome', 
                    integrantes = '$baldeIntegrantes' 
                    WHERE id = '$baldeId'";

        mysqli_query($con, $sql);
    }

    $formaPagamento = $_POST['formaPagamento'];
    $verba = $_POST['verba'];
    $processoMae = $_POST['processoMae'];


}

$evento = recuperaDados('eventos', 'id', $idEvento);
$pedido = recuperaDados('pedidos', 'origem_id', $idEvento . " AND origem_tipo_id = 1");

if ($pedido['pessoa_tipo_id'] == 1)
    $proponente = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);
else
    $proponente = recuperaDados('pessoa_juridicas', 'id', $pedido['pessoa_juridica_id']);

$contrato = recuperaDados('contratos', 'pedido_id', $pedido['id']);
$sqlAtracao = "SELECT * FROM atracoes where evento_id = '$idEvento' AND publicado = 1";
$queryAtracao = mysqli_query($con, $sqlAtracao);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Contrato</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Contrato do evento: <?= $evento['nome_evento'] ?></h3>
                    </div>
                    <form method="POST" action="?perfil=contrato&p=filtrar_contratos&sp=resumo"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <?php
                                if ($nivelUsuario == 1) {
                                ?>
                                <div class="col-md-6 from-group">
                                    <label for="operador">Operador</label>
                                    <select name="operador" id="operador" class="form-control">
                                        <?php
                                        $sqlWhere = "usuarios u INNER JOIN usuario_contratos uc on uc.usuario_id = u.id";
                                        geraOpcao($sqlWhere, $contrato['usuario_contrato_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="status">Status Contrato</label>
                                    <select name="status" id="status" class="form-control">
                                        <?php
                                        geraOpcao('pedido_status', $pedido['status_pedido_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <?php
                            }
                            ?>

                            <?php
                            while ($atracao = mysqli_fetch_array($queryAtracao)) {
                                ?>
                                <div class="row">
                                    <input type="hidden" name="idAtracao[]" value="<?= $atracao['id'] ?>">

                                    <div class="form-group col-md-6">
                                        <label for="nome_atracao[]">Nome da atração *</label>
                                        <input type="text" name="nome_atracao[]" id="nome_atracao"
                                               value="<?= $atracao['nome_atracao'] ?>"
                                               class="form-control" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="integrantes[]">Integrantes* </label>
                                        <textarea name="integrantes[]" id="integrantes" required rows="8"
                                                  class="form-control"><?= $atracao['integrantes'] ?></textarea>
                                    </div>
                                </div>
                                <hr>
                                <?php
                            }
                            ?>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="formaPagamento">Forma de pagamento</label>
                                    <input type="text" name="formaPagamento" id="formaPagamento"
                                           value="<?= $pedido['forma_pagamento'] ?>" class="form-control">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="verba">Verba </label>
                                    <select name="verba" id="verba" class="form-control">
                                        <?php
                                        geraOpcao('verbas', $pedido['verba_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="processoMae">Número de processo mãe</label>
                                    <input type="text" class="form-control" name="processoMae" id="processoMae"
                                           data-mask="9999.9999/9999999-9" minlength="19"
                                           value="<?= $pedido['numero_processo_mae'] ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="processo">Número de processo</label>
                                    <input type="text" class="form-control" name="processo" id="processo"
                                           data-mask="9999.9999/9999999-9" minlength="19"
                                           value="<?= $pedido['numero_processo'] ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="fiscal">Fiscal *</label>
                                    <select class="form-control" id="fiscal" name="fiscal" required>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, $evento['fiscal_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="suplente">Suplente</label>
                                    <select class="form-control" id="suplente" name="suplente">
                                        <option value="">Selecione um suplente...</option>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, $evento['suplente_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <input type="hidden" name="idEvento" class="idEvento" value="<?= $idEvento ?>">
                            <button type="submit" name="salvar" id="salvar" class="btn btn-primary pull-right">
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php
        if ($pedido['pessoa_tipo_id'] == 1) {
        ?>
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Proponente</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Proponente</th>
                        <th width="5%">Editar</th>
                        <th width="5%">Trocar</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?= $proponente['nome'] ?></td>
                        <td>
                            <form action="#" method="POST">
                                <input type="hidden" name="idPedido" id="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-primary btn-block"><span
                                            class="glyphicon glyphicon-pencil"></span></button>
                            </form>
                        </td>
                        <td>
                            <form action="?perfil=contrato&p=filtrar_contratos&sp=pesquisa_pf"
                                  method="POST">
                                <input type="hidden" name="idPedido" id="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-info btn-block"><span
                                            class="glyphicon glyphicon-random"></span></button>
                            </form>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php
                }
                ?>
            </div>
        </div>
</div>
</section>
</div>