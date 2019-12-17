<?php
$con = bancoMysqli();

$idEvento = $_POST['idEvento'];

$_SESSION['idEvento'] = $idEvento;

$evento = recuperaDados('eventos', 'id', $idEvento);
$sql = "SELECT * FROM pedidos where origem_tipo_id = 1 AND origem_id = '$idEvento' AND publicado = 1";
$query = mysqli_query($con, $sql);
$pedido = mysqli_fetch_array($query);
$idPedido = $pedido['id'];

$evento = recuperaDados('eventos', 'id', $idEvento);
$sql = "SELECT * FROM pedidos where origem_tipo_id = 1 AND origem_id = '$idEvento' AND publicado = 1";
$query = mysqli_query($con, $sql);
$pedido = mysqli_fetch_array($query);

if ($pedido['pessoa_tipo_id'] == 1) {
    $proponente = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);
    $idPf = $pedido['pessoa_fisica_id'];
} else {
    $proponente = recuperaDados('pessoa_juridicas', 'id', $pedido['pessoa_juridica_id']);
    $idPj = $pedido['pessoa_juridica_id'];
}

$contrato = recuperaDados('contratos', 'pedido_id', $pedido['id']);
$sqlAtracao = "SELECT * FROM atracoes where evento_id = '$idEvento' AND publicado = 1";
$queryAtracao = mysqli_query($con, $sqlAtracao);
$operador = recuperaDados('usuarios', 'id', $pedido['operador_id']);
$pedidoStatus = recuperaDados('pedido_status', 'id', $pedido['status_pedido_id']);
$verba = recuperaDados('verbas', 'id', $pedido['verba_id']);
$fiscal = recuperaDados('usuarios', 'id', $evento['fiscal_id']);
$suplente = recuperaDados('usuarios', 'id', $evento['suplente_id']);

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
                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-6 from-group">
                                <label for="operador">Operador: </label>
                                <?= $operador['nome_completo'] ?>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="status">Status Contrato: </label>
                                <?= $pedidoStatus['status'] ?>
                            </div>
                        </div>
                        <hr>


                        <?php
                        while ($atracao = mysqli_fetch_array($queryAtracao)) {
                            $_SESSION['idAtracao'] = $atracao['id'];
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <input disabled type="hidden" name="idAtracao[]" value="<?= $atracao['id'] ?>">
                                    <label for="nome_atracao[]">Nome da atração: </label>
                                    <?= $atracao['nome_atracao'] ?>
                                    <br>

                                    <label for="valor">Valor: </label>
                                    R$ <?= dinheiroParaBr($atracao['valor_individual']) ?>

                                    <br>

                                    <label for="integrantes[]">Integrantes</label>
                                    <textarea disabled name="integrantes[]" id="integrantes" required rows="5"
                                              class="form-control"><?= $atracao['integrantes'] ?></textarea>
                                </div>
                            </div>
                            <hr>
                            <?php
                        }
                        ?>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="formaPagamento">Forma de pagamento</label>
                                <textarea disabled name="formaPagamento" id="formaPagamento" rows="5" required
                                          class="form-control"><?= $pedido['forma_pagamento'] ?> </textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="justificativa">Justificativa</label>
                                <textarea disabled name="justificativa" id="justificativa" rows="5" required
                                          class="form-control"><?= $pedido['justificativa'] ?> </textarea>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="verba">Verba: </label>
                                <?= $verba['verba'] ?>

                                <br>

                                <label for="processoMae">Número de processo mãe: </label>
                                <?= $pedido['numero_processo_mae'] ?>

                                <br>

                                <label for="processo">Número de processo: </label>
                                <?= $pedido['numero_processo'] ?>

                                <br>

                                <label for="fiscal">Fiscal: </label>
                                <?= $fiscal['nome_completo'] ?>

                                <br>

                                <label for="suplente">Suplente: </label>
                                <?= $suplente['nome_completo'] ?>
                            </div>
                        </div>
                    </div>
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
                        <tbody>
                        <tr>
                            <td><?= $proponente['nome'] ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        } else if ($pedido['pessoa_tipo_id'] == 2) {
            $sql_atracao = "SELECT a.id, a.nome_atracao, pf.nome, l.pessoa_fisica_id FROM atracoes AS a                                              
                                            LEFT JOIN lideres l on a.id = l.atracao_id
                                            left join pessoa_fisicas pf on l.pessoa_fisica_id = pf.id
                                            WHERE evento_id = '$idEvento' AND a.publicado = 1";
            $query_atracao = mysqli_query($con, $sql_atracao);
            ?>
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Proponente</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td><?= $proponente['razao_social'] ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Líderes</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Atração</th>
                            <th>Proponente</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($atracao = mysqli_fetch_array($query_atracao)) {
                            ?>
                            <tr>
                                <td><?= $atracao['nome_atracao'] ?></td>
                                <?php
                                if ($atracao['pessoa_fisica_id'] > 0) {
                                    ?>
                                    <td><?= $atracao['nome'] ?></td>
                                    <?php
                                } else {
                                    ?>
                                    <td>Sem líder cadastrado</td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        }
        ?>
    </section>
</div>
