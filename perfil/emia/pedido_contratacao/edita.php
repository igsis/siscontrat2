<?php

$con = bancoMysqli();

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $idEc = $_POST['idEc'];
    $pf = $_POST['pf'];
    $num_processo = $_POST['numeroProcesso'];
    $verba = $_POST['verba'];
    $num_parcelas = $_POST['numParcelas'];
    $valor = dinheiroDeBr($_POST['valor']);
    $forma_pagamento = addslashes($_POST['forma_pagamento']) ?? null;
    $justificativa = addslashes($_POST['justificativa']) ?? null;
    $obs = addslashes($_POST['observacao']) ?? null;
    $data_kit = $_POST['dataKit'];
    if (isset($_POST['cadastra'])) {
        $sql = "INSERT INTO pedidos (origem_tipo_id, 
                                 origem_id, 
                                 pessoa_tipo_id,  
                                 pessoa_fisica_id, 
                                 numero_processo, 
                                 verba_id, 
                                 numero_parcelas, 
                                 valor_total, 
                                 forma_pagamento, 
                                 data_kit_pagamento, 
                                 justificativa, 
                                 status_pedido_id, 
                                 observacao 
                                )
            VALUES('3',
                   '$idEc',
                   '1',
                   '$pf',
                   '$num_processo',
                   '$verba',
                   '$num_parcelas',
                   '$valor',
                   '$forma_pagamento',
                   '$data_kit',
                   '$justificativa',
                   '2',
                   '$obs')";
        if (mysqli_query($con, $sql)) {
            $idPedido = recuperaUltimo('pedidos');
            gravarLog($sql);
            $sqlInsert = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento) 
                    SELECT p.id, ep.numero_parcelas, ep.valor, ep.data_pagamento 
                    FROM emia_parcelas ep 
                    INNER JOIN emia_contratacao ec ON ec.emia_vigencia_id = ep.emia_vigencia_id 
                    INNER JOIN pedidos p ON p.origem_id = ec.id 
                    WHERE p.origem_tipo_id = 3 AND p.id = '$idPedido'";
            mysqli_query($con, $sqlInsert);
            gravarLog($sqlInsert);

            $mensagem = mensagem("success", "Pedido de contratação cadastrado com sucesso.");

            $sqlUpdate = "UPDATE emia_contratacao SET pedido_id = '$idPedido' WHERE id = '$idEc'";
            mysqli_query($con, $sqlUpdate);
        } else {
            $mensagem = mensagem("danger", "Erro ao Cadastrar! Tente novamente.");
        }
    } else if (isset($_POST['edita'])) {
        $idPedido = $_POST['idEc'];

        $sql = "UPDATE pedidos SET verba_id = '$verba', valor_total = '$valor', data_kit_pagamento = '$data_kit', numero_processo = '$num_processo', forma_pagamento = '$forma_pagamento', justificativa = '$justificativa', observacao = '$obs', numero_parcelas = '$num_parcelas' WHERE id = '$idPedido'";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Pedido de contratação salvo com sucesso.");
        } else {
            $mensagem = mensagem("danger", "Erro ao Salvar! Tente novamente.");
        }

    }
}

if (isset($_POST['carregar'])) {
    $idPedido = $_POST['idEc'];
    unset($_SESSION['idPedido']);
    $_SESSION['idPedido'] = $idPedido;
}

if (isset($_POST['parcelaEditada'])) {
    $idPedido = $_SESSION['idPedido'];
    $parcelas = $_POST['parcela'];
    $valores = dinheiroDeBr($_POST['valor']);
    $data_pagamentos = $_POST['data_pagamento'];

    $pedido = recuperaDados('pedidos', 'id', $idPedido);

    $sql = "DELETE FROM parcelas WHERE pedido_id = '$idPedido'";
    mysqli_query($con, $sql);

    $i = $pedido['numero_parcelas'];

    $baldeValor = 0;

    for ($count = 0; $count < $i; $count++) {
        $parcela = $parcelas[$count] ?? NULL;
        $valor = $valores[$count] ?? NULL;
        $baldeValor += $valor;
        $data_pagamento = $data_pagamentos[$count] ?? NULL;

        $sql = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento) VALUES ('$idPedido', '$parcela', '$valor', '$data_pagamento')";

        if (mysqli_query($con, $sql)) {
            $mensagem = mensagem('success', 'Parcelas Atualizadas!');
        } else {
            $mensagem = mensagem('danger', 'Erro ao atualizar as parcelas! Tente Novamente.');
        }
    }

    $sql = "UPDATE pedidos SET valor_total = '$baldeValor' WHERE id = '$idPedido'";
    mysqli_query($con, $sql);
}

$sql = "SELECT pf.nome,
		       ec.ano,
               l.local,
               c.cargo,
               v.ano AS 'vigencia',
               v.id,
               ec.cronograma,
               ec.observacao,
               ec.pessoa_fisica_id,
               v.numero_parcelas,
               f.nome_completo AS 'fiscal',
               s.nome_completo AS 'suplente',
               verba.verba,
               p.data_kit_pagamento,
               p.numero_processo,
               p.justificativa,
               p.forma_pagamento,
               p.verba_id,
               p.observacao
		FROM pedidos AS p
		INNER JOIN emia_contratacao AS ec ON p.origem_id = ec.id
        INNER JOIN pessoa_fisicas AS pf ON ec.pessoa_fisica_id = pf.id
        INNER JOIN locais AS l ON l.id = ec.local_id
        INNER JOIN emia_cargos AS c ON c.id = ec.emia_cargo_id
        INNER JOIN emia_vigencias AS v ON v.id = ec.emia_vigencia_id
        INNER JOIN usuarios AS f ON ec.fiscal_id = f.id
		INNER JOIN usuarios AS s ON ec.suplente_id = s.id
        INNER JOIN verbas AS verba ON p.verba_id = verba.id
        WHERE p.publicado = 1 AND p.id = '$idPedido' AND p.origem_tipo_id = 3";
$ec = $con->query($sql)->fetch_array();

$aux = $ec['numero_parcelas'];

$valor = 0;
for ($i = 1; $i < $aux + 1; $i++) {
    $sql = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";
    $parcela = mysqli_fetch_array(mysqli_query($con, $sql));
    $valor += $parcela['valor'];
}
$valor = dinheiroParaBr($valor);

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do igsis
$http = $server . "/pdf/";
$link_proposta = $http . "rlt_proposta_emia.php";
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Criar Pedido de Contratação</h2>
        <div class="box box-primary">
            <div class="box-header">
                <h4 class="box-title">Dados para Contratação</h4>
            </div>
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <form method="post" action="?perfil=emia&p=pedido_contratacao&sp=edita" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="pf">Pessoa Física: *</label>
                            <input type="hidden" name="pf" id="pf" value="<?= $ec['pessoa_fisica_id'] ?>">
                            <input type="text" value="<?= $ec['nome'] ?>" disabled class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="ano">Ano: *</label>
                            <input name="ano" id="ano" type="number" required class="form-control"
                                   value="<?= $ec['ano'] ?>" disabled>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="local">Local: *</label>
                            <select name="local" id="local" required class="form-control" disabled>
                                <option><?= $ec['local'] ?></option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="cargo">Cargo: *</label>
                            <select name="cargo" id="cargo" class="form-control" required disabled>
                                <option><?= $ec['cargo'] ?></option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="vigencia">Vigência: *</label>
                            <select name="vigencia" id="vigencia" class="form-control" required disabled>
                                <option><?= $ec['vigencia'] ?></option>
                            </select>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="cronograma">Cronograma: </label>
                            <textarea name="cronograma" id="cronograma" rows="3" type="text" class="form-control"
                                      disabled><?= $ec['cronograma'] ?></textarea>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="observacao">Observação: </label>
                            <textarea name="observacao" id="observacao" rows="3" type="text" class="form-control"
                                      disabled><?= $ec['observacao'] ?></textarea>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="fiscal">Fiscal: </label>
                            <select name="fiscal" id="fiscal" class="form-control" disabled>
                                <option><?= $ec['fiscal'] ?></option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="suplente">Suplente: </label>
                            <select name="suplente" id="suplente" class="form-control" disabled>
                                <option><?= $ec['suplente'] ?></option>
                            </select>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="verba">Verba: *</label>
                            <select name="verba" id="verba" class="form-control">
                                <?php
                                geraOpcao('verbas', $ec['verba_id']);
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="numParcelas">Número de parcelas:</label>
                            <input type="text" name="numParcelas" value="<?= $ec['numero_parcelas'] ?>" readonly
                                   class="form-control" required>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="valor">Valor Total:</label>
                            <input type="text" name="valor" onKeyPress="return(moeda(this,'.',',',event))"
                                   class="form-control" value="<?= $valor ?>" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="dataKit">Data kit pagamento:</label>
                            <input type="date" name="dataKit" class="form-control" id="datepicker10"
                                   placeholder="DD/MM/AAAA" value="<?= $ec['data_kit_pagamento'] ?>">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="numeroProcesso">Número do Processo: *</label>
                            <input type="text" name="numeroProcesso" id="numProcesso" class="form-control"
                                   data-mask="9999.9999/9999999-9" minlength="19" value="<?= $ec['numero_processo'] ?>">
                        </div>

                        <div class="form-group col-md-6">
                            <br>
                            <a href="?perfil=emia&p=pedido_contratacao&sp=edita_parcela">
                                <button type="button" class="btn btn-info btn-block">Editar parcelas</button>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="forma_pagamento">Forma de pagamento: </label>
                            <textarea id="forma_pagamento" name="forma_pagamento" class="form-control"
                                      rows="8"><?= $ec['forma_pagamento'] ?></textarea>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="justificativa">Justificativa: </label>
                            <textarea id="justificativa" name="justificativa" class="form-control"
                                      rows="8"><?= $ec['justificativa'] ?></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="justificativa">Observação: </label>
                            <textarea id="observacao" name="observacao" class="form-control"
                                      rows="8"><?= $ec['observacao'] ?></textarea>
                        </div>
                    </div>
                </div>


                <div class="box-footer">
                    <div class="col-md-5">
                        <a href="?perfil=emia&p=pedido_contratacao&sp=listagem">
                            <button type="button" class="btn btn-default">Voltar</button>
                        </a>
                    </div>

                    <input type="hidden" name="idEc" value="<?= $idPedido ?>" id="idEc">
            </form>
            <button type="submit" name="edita" id="edita" class="btn btn-primary pull-right">
                Salvar
            </button>
            <div class="col-md-1">
                <form action="<?= $link_proposta ?>" target="_blank" method="post">
                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                    <button type="submit" class="btn btn-primary center-block">Gerar Proposta</button>
                </form>
            </div>
        </div>
</div>
</section>
</div>
