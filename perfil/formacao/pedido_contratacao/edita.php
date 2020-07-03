<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $verba = $_POST['verba'];
    $numParcelas = $_POST['numParcelas'];
    $valor = dinheiroDeBr($_POST['valor']);
    $dataKit = $_POST['dataKit'];
    $numeroProcesso = $_POST['numeroProcesso'];
    $forma_pagamento = addslashes($_POST['forma_pagamento']) ?? null;
    $justificativa = addslashes($_POST['justificativa']) ?? null;
    $observacao = addslashes($_POST['observacao']) ?? null;
    $local = $_POST['local'];
    $processoMae = $_POST['processoMae'];
}
if (isset($_POST['cadastra'])) {
    $idPc = $_POST['idPc'];
    $fc = recuperaDados('formacao_contratacoes', 'id', $idPc);
    $idPf = $fc['pessoa_fisica_id'] ?? null;

    $sql = "INSERT INTO pedidos (origem_tipo_id, origem_id, pessoa_tipo_id, pessoa_fisica_id, numero_processo, numero_processo_mae, verba_id, numero_parcelas, valor_total, forma_pagamento, data_kit_pagamento, justificativa, status_pedido_id, observacao)
                         VALUES (2, '$idPc', 1, '$idPf', '$numeroProcesso', '$processoMae' ,'$verba', '$numParcelas', '$valor', '$forma_pagamento', '$dataKit', '$justificativa', 2, '$observacao')";
    if (mysqli_query($con, $sql)) {
        $idPedido = recuperaUltimo('pedidos');
        gravarLog($sql);
        $sqlInsert = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento) 
                    SELECT p.id, fp.numero_parcelas, fp.valor, fp.data_pagamento 
                    FROM formacao_parcelas fp 
                    INNER JOIN formacao_contratacoes fc ON fc.form_vigencia_id = fp.formacao_vigencia_id 
                    INNER JOIN pedidos p ON p.origem_id = fc.id 
                    WHERE p.origem_tipo_id = 2 AND p.id = '$idPedido'";
        mysqli_query($con, $sqlInsert);
        gravarLog($sqlInsert);

        $formaCompleta = "";

        $consultaParcelas = $con->query("SELECT * FROM parcelas WHERE pedido_id = $idPedido AND publicado = 1 ORDER BY numero_parcelas");

        $countForma = 0;

        while ($parcelasArray = mysqli_fetch_array($consultaParcelas)) {
            $forma = $countForma + 1 . "º parcela R$ " . dinheiroParaBr($parcelasArray['valor']) . ". Entrega de documentos a partir de " . exibirDataBr($parcelasArray['data_pagamento']) . ".\n";
            $formaCompleta = $formaCompleta . $forma;

            $countForma += 1;
        }
        $formaCompleta = $formaCompleta . "\nA liquidação de cada parcela se dará em 3 (três) dias úteis após a data de confirmação da correta execução do(s) serviço(s).";

        $sqlForma = "UPDATE pedidos SET forma_pagamento = '$formaCompleta' WHERE id = $idPedido AND origem_tipo_id = 2";
        mysqli_query($con, $sqlForma);

        $sql_delete = "DELETE FROM formacao_locais WHERE form_pre_pedido_id = '$idPc'";
        mysqli_query($con, $sql_delete);

        for ($i = 0; $i < count($local); $i++) {
            $idLocal = $local[$i];

            $sqlLocal = "INSERT INTO formacao_locais (form_pre_pedido_id, local_id) VALUES ('$idPc', '$idLocal')";
            mysqli_query($con, $sqlLocal);
            gravarLog($sqlLocal);
        }

        $mensagem = mensagem("success", "Pedido de contratação cadastrado com sucesso.");

        $sqlUpdate = "UPDATE formacao_contratacoes SET pedido_id = '$idPedido' WHERE id='$idPc'";
        mysqli_query($con, $sqlUpdate);

    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao criar o pedido de contratação. Tente novamente!");
    }
}

if (isset($_POST['edita'])) {
    $idPedido = $_POST['idPedido'];

    $sql = "UPDATE pedidos SET verba_id = '$verba', valor_total = '$valor', data_kit_pagamento = '$dataKit', numero_processo = '$numeroProcesso', numero_processo_mae = '$processoMae',forma_pagamento = '$forma_pagamento', justificativa = '$justificativa', observacao = '$observacao', numero_parcelas = '$numParcelas' WHERE id = '$idPedido'";

    if (mysqli_query($con, $sql)) {
        gravarLog($sql);

        $pedido = recuperaDados('pedidos', 'id', $idPedido);
        $idPc = $pedido['origem_id'];
        $fc = recuperaDados('formacao_contratacoes', 'id', $idPc);

        $sql_delete = "DELETE FROM formacao_locais WHERE form_pre_pedido_id = '$idPc'";
        mysqli_query($con, $sql_delete);

        for ($i = 0; $i < count($local); $i++) {
            $idLocal = $local[$i];

            $sqlLocal = "INSERT INTO formacao_locais (form_pre_pedido_id, local_id) VALUES ('$idPc', '$idLocal')";
            mysqli_query($con, $sqlLocal);
            gravarLog($sqlLocal);
        }

        $mensagem = mensagem("success", "Pedido de contratação salvo com sucesso.");

    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao salvar o pedido de contratação. Tente novamente!");
    }
}


if (isset($_POST['carregar']))
    $idPedido = $_POST['idPedido'];

if (isset($_POST['parcelaEditada'])) {
    $idPedido = $_POST['idPedido'];
    $numParcelas = $_POST['numParcelas'];

    $parcelas = $con->query("SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND publicado = 1")->fetch_all(MYSQLI_ASSOC);

    if (count($parcelas) > 0) {
        foreach ($parcelas as $parcela) {
            $sqlDeletaParcela = "UPDATE parcelas SET publicado = '0' WHERE pedido_id = '$idPedido' AND numero_parcelas = '{$parcela['numero_parcelas']}'";
            if ($con->query($sqlDeletaParcela)) {
                gravarLog($sqlDeletaParcela);
            }
        }
    }

    if (isset($_POST['parcelaEditada']) && $numParcelas != NULL) {

        foreach ($_POST['parcela'] AS $countPost => $parcela) {
            $valor = dinheiroDeBr($_POST['valor'][$countPost]) ?? NULL;
            $data_pagamento = $_POST['data_pagamento'][$countPost] ?? NULL;

            $sqlParcelas = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento) VALUES ('$idPedido', '$parcela', '$valor', '$data_pagamento')";

            if ($con->query($sqlParcelas)) {
                $mensagem = mensagem('success', 'Parcelas Atualizadas!');
            } else {
                $mensagem = mensagem('danger', 'Erro ao atualizar as parcelas! Tente Novamente.');
            }
        }
    }

    $pedido = recuperaDados('pedidos', 'id', $idPedido);
    $i = $pedido['numero_parcelas'];

    $formaCompleta = "";

    $consultaParcelas = $con->query("SELECT * FROM parcelas WHERE pedido_id = $idPedido AND publicado = 1 ORDER BY numero_parcelas");

    $countForma = 0;

    while ($parcelasArray = mysqli_fetch_array($consultaParcelas)) {
        $forma = $countForma + 1 . "º parcela R$ " . dinheiroParaBr($parcelasArray['valor']) . ". Entrega de documentos a partir de " . exibirDataBr($parcelasArray['data_pagamento']) . ".\n";
        $formaCompleta = $formaCompleta . $forma;

        $countForma += 1;
    }
    $formaCompleta = $formaCompleta . "\nA liquidação de cada parcela se dará em 3 (três) dias úteis após a data de confirmação da correta execução do(s) serviço(s).";

    $sqlForma = "UPDATE pedidos SET forma_pagamento = '$formaCompleta' WHERE id = $idPedido AND origem_tipo_id = 2";
    mysqli_query($con, $sqlForma);
}

$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idPc = $pedido['origem_id'];

$fc = recuperaDados('formacao_contratacoes', 'id', $idPc);

$pessoa_fisica = recuperaDados('pessoa_fisicas', 'id', $fc['pessoa_fisica_id'])['nome'];
$classificacao = recuperaDados('classificacao_indicativas', 'id', $fc['classificacao'])['classificacao_indicativa'];
$territorio = recuperaDados('territorios', 'id', $fc['territorio_id'])['territorio'];
$coordenadoria = recuperaDados('coordenadorias', 'id', $fc['coordenadoria_id'])['coordenadoria'];
$subprefeitura = recuperaDados('subprefeituras', 'id', $fc['subprefeitura_id'])['subprefeitura'];
$programa = recuperaDados('programas', 'id', $fc['programa_id'])['programa'];
$linguagem = recuperaDados('linguagens', 'id', $fc['linguagem_id'])['linguagem'];
$projeto = recuperaDados('projetos', 'id', $fc['projeto_id'])['projeto'];
$cargo = recuperaDados('formacao_cargos', 'id', $fc['form_cargo_id'])['cargo'];
$vigencia = recuperaDados('formacao_vigencias', 'id', $fc['form_vigencia_id']);
$numParcelas = $pedido['numero_parcelas'];
$fiscal = recuperaDados('usuarios', 'id', $fc['fiscal_id'])['nome_completo'];
$suplente = recuperaDados('usuarios', 'id', $fc['suplente_id'])['nome_completo'];

$sqlLocal = "SELECT local_id FROM formacao_locais WHERE form_pre_pedido_id = '$idPc'";
$queryLocais = mysqli_query($con, $sqlLocal);
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
            <form method="post" action="?perfil=formacao&p=pedido_contratacao&sp=edita" role="form" id="formulario">
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="ano">Ano: *</label>
                            <input type="number" min="2018" id="ano" required class="form-control"
                                   value="<?= $fc['ano'] ?>" disabled>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="chamado">Chamado: *</label>
                            <input type="number" min="0" max="127" id="chamado"
                                   value="<?= $fc['chamado'] ?>" required class="form-control" disabled>
                        </div>

                    </div>

                    <div class="row">
                        <div class="from-group col-md-12">
                            <label for="pf">Pessoa Física: *</label>
                            <input type="text" class="form-control" id="pessoa_fisica" required
                                   value="<?= $pessoa_fisica ?>" disabled>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="classificacao">Classificação Indicativa *</label>
                            <input type="text" name="classificacao" value="<?= $classificacao ?>" disabled required
                                   class="form-control">
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="territorio">Território *</label>
                            <input type="text" name="territorio" value="<?= $territorio ?>" disabled required
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="coordenadoria">Coordenadoria *</label>
                            <input type="text" name="coordenadoria" value="<?= $coordenadoria ?>" disabled required
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="subprefeitura">Subprefeitura *</label>
                            <input type="text" name="subprefeitura" value="<?= $subprefeitura ?>" disabled required
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="programa">Programa *</label>
                            <input type="text" name="programa" value="<?= $programa ?>" required disabled
                                   class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="linguagem">Linguagem *</label>
                            <input type="text" name="linguagem" value="<?= $linguagem ?>" required disabled
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="projeto">Projeto *</label>
                            <input type="text" name="projeto" value="<?= $projeto ?>" required disabled
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="cargo">Cargo *</label>
                            <input type="text" name="cargo" value="<?= $cargo ?>" required disabled
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="vigencia">Vigência *</label>
                            <input type="text" name="vigencia" value="<?= $vigencia['ano'] ?>" required disabled
                                   class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="observacao">Observação: </label>
                            <textarea name="observacao" id="observacao" rows="3"
                                      class="form-control" disabled required><?= $fc['observacao'] ?></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="fiscal">Fiscal *</label>
                            <input type="text" name="fiscal" value="<?= $fiscal ?>" disabled required
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="fiscal">Suplente </label>
                            <input type="text" name="suplente" value="<?= $suplente ?>" disabled required
                                   class="form-control">
                        </div>
                    </div>


                    <hr>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="verba">Verba: *</label>
                            <select name="verba" id="verba" class="form-control" required>
                                <?php geraOpcao('verbas', $pedido['verba_id']); ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="numParcelas">Número de parcelas: *</label>
                            <input type="text" name="numParcelas" value="<?= $numParcelas ?>"
                                   class="form-control" required>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="valor">Valor: *</label>
                            <input type="text" name="valor" onKeyPress="return(moeda(this,'.',',',event))"
                                   class="form-control" value="<?= $pedido['valor_total'] ?>" readonly required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="dataKit">Data kit pagamento: *</label>
                            <input type="date" name="dataKit" class="form-control" id="datepicker10"
                                   placeholder="DD/MM/AAAA" value="<?= $pedido['data_kit_pagamento'] ?>" required>
                        </div>

                        <div class="form-group col-md-6">
                            <br>
                            <a href="?perfil=formacao&p=pedido_contratacao&sp=edita_parcelas&idPedido=<?=$idPedido?>">
                                <button type="button" class="btn btn-info btn-block">Editar parcelas</button>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="numeroProcesso">Número do Processo: *</label>
                            <input type="text" name="numeroProcesso" id="numProcesso" class="form-control"
                                   value="<?= $pedido['numero_processo'] ?>" data-mask="9999.9999/9999999-9" required
                                   minlength="19">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="numeroProcesso">Número do Processo Mãe: *</label>
                            <input type="text" name="processoMae" id="processoMae" class="form-control"
                                   value="<?= $pedido['numero_processo_mae'] ?>" data-mask="9999.9999/9999999-9"
                                   required
                                   minlength="19">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="forma_pagamento">Forma de pagamento: *</label>
                            <textarea id="forma_pagamento" name="forma_pagamento" class="form-control" required
                                      readonly
                                      rows="8"><?= $pedido['forma_pagamento'] ?? NULL ?></textarea>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="justificativa">Justificativa: *</label>
                            <textarea id="justificativa" name="justificativa" class="form-control" required
                                      rows="8"><?= $pedido['justificativa'] ?? null ?></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <?php
                        $arrayLocal = array();
                        while ($count = mysqli_fetch_array($queryLocais)) {
                            array_push($arrayLocal, $count['local_id']);
                        }
                        for ($i = 0; $i < 3; $i++) {
                            ?>
                            <div class="form-group col-md-4">
                                <label for="local[]">Local #<?= $i + 1 ?> </label>
                                <select name="local[]" id="local[]" class="form-control">
                                    <option value="0">Selecione uma opção...</option>
                                    <?php
                                    geraOpcao('locais', $arrayLocal[$i]);
                                    ?>
                                </select>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="row" id="msgEsconde" style="display: none;">
                        <div class="col-md-12">
                            <span style="color: red;"><b>Selecione ao menos um local!</b></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="justificativa">Observação *</label>
                            <textarea id="observacao" name="observacao" class="form-control"
                                      rows="8"><?= $pedido['observacao'] ?? null ?></textarea>
                        </div>
                    </div>


                    <div class="box-footer">
                        <div class="col-md-5">
                            <a href="?perfil=formacao&p=pedido_contratacao&sp=listagem">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                        </div>

                        <input type="hidden" name="idPedido" value="<?= $pedido['id'] ?>" id="idPedido">

                        <button type="submit" name="edita" id="edita" class="btn btn-primary pull-right">
                            Salvar
                        </button>

                    </div>
            </form>
            <hr/>
            <div class="row">
                <div class="col-md-12" style="text-align:center">
                    <form action="?perfil=formacao&p=pedido_contratacao&sp=area_impressao" method="post" role="form">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-default" style="width: 35%;">Ir para a área de impressão
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    let local = document.getElementsByName("local[]");
    const idLocal = "Selecione uma opção... ";
    const nenhumaOpcao = local[0];
    var isMsg = $('#msgEsconde');
    isMsg.hide();

    $('#formulario').submit(function (event) {
        let count = 0;

        for (let i = 0; i < local.length; i++) {
            if (local[i].value == 0)
                count++;
        }

        if (count == 3) {
            event.preventDefault()
            isMsg.show();
            nenhumaOpcao.focus()
        }
    })

</script>
