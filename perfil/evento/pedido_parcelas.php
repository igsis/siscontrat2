<?php
include "includes/menu_interno.php";

$idPedido = $_SESSION['idPedido'];
$idEvento = $_SESSION['idEvento'];

$pedido = recuperaDados("pedidos", "id", $idPedido);
$evento = recuperaDados('eventos', 'id', $idEvento);

$tipoEvento = $evento['tipo_evento_id'];

if ($pedido['origem_tipo_id'] != 2 && $tipoEvento != 2) {
    $readonlyValorTotal = 'readonly';

    $query_data = "SELECT MIN(o.data_inicio)
                    FROM eventos AS e INNER JOIN atracoes AS a ON a.evento_id = e.id
                    INNER JOIN  ocorrencias AS o ON a.id = o.atracao_id 
                    INNER JOIN pedidos AS p ON p.origem_id = e.id 
                    WHERE p.origem_tipo_id = 1 AND p.id = '$idPedido' AND p.publicado = 1 AND a.publicado = 1 AND o.publicado = 1";
} else {
    $readonlyValorTotal = '';

    $query_data = "SELECT count(*) FROM ocorrencias AS o INNER JOIN filme_eventos AS fe ON fe.id = o.atracao_id 
                    INNER JOIN eventos AS e ON fe.evento_id = e.id WHERE e.id = '$idEvento' AND e.publicado = 1 AND o.publicado = 1";
}

$tipoPessoa = $pedido['pessoa_tipo_id'];
if ($tipoPessoa == 2) {
    $idProponente = $pedido['pessoa_juridica_id'];
} else {
    $idProponente = $pedido['pessoa_fisica_id'];
}


$sqlOficina = "SELECT aa.acao_id FROM eventos e
                INNER JOIN atracoes a on e.id = a.evento_id
                INNER JOIN acao_atracao aa on a.id = aa.atracao_id
                WHERE e.id = '$idEvento' and a.publicado = 1";
$queryOficina = $con->query($sqlOficina);

while ($atracoes = $queryOficina->fetch_assoc()) {
    if ($atracoes['acao_id'] == 8) {
        $oficina = 1;
    }
}

if (isset($_POST['gravarParcelas']) || isset($_POST['editarParcelas'])) {
    $num_parcelas = $_POST['nParcelas'] ?? NULL;
    $idPedido = $_POST["idPedido"];

    if (isset($_POST['gravarParcelas']) && $num_parcelas != NULL) {
        foreach ($_POST['parcela'] as $key => $parcela) {
            $valor = dinheiroDeBr($_POST['valor'][$key]);
            $data_kit_pagamento = $_POST['data_pagamento'][$key];

            $sqlInsertParcela = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento)
                                    VALUES ('$idPedido', '$parcela', '$valor', '$data_kit_pagamento')";

            if ($con->query($sqlInsertParcela)) {
                gravarLog($sqlInsertParcela);
                if (isset($_POST['oficina'])) {
                    $parcela_id = $con->insert_id;
                    $data_inicial = $_POST['data_inicial'][$key];
                    $data_final = $_POST['data_final'][$key];
                    $carga_horaria = $_POST['carga_horaria'][$key];

                    $sqlInsertComplemento = "INSERT INTO parcela_complementos
                                            (parcela_id, data_inicio, data_fim, carga_horaria) VALUES 
                                            ('$parcela_id', '$data_inicial', '$data_final', '$carga_horaria')";

                    if ($con->query($sqlInsertComplemento)) {
                        gravarLog($sqlInsertComplemento);
                    } else {
                        $erro = true;
                    }
                }
            } else {
                $erro = true;
            }
        }
    } else {
        $parcelas = $con->query("SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND publicado = '1'")->fetch_all(MYSQLI_ASSOC);

        foreach ($_POST['parcela'] as $key => $parcela) {
            $valor = dinheiroDeBr($_POST['valor'][$key]);
            $data_kit_pagamento = $_POST['data_pagamento'][$key];

            if (isset($parcelas[$key])) {
                $id = $parcelas[$key]['id'];
                unset($parcelas[$key]);

                $sqlAtualizaParcela = "UPDATE parcelas SET 
                                    valor = '$valor',
                                    data_pagamento = '$data_kit_pagamento'
                                WHERE pedido_id = '$idPedido' AND numero_parcelas = '$parcela'";
            } else {
                $sqlAtualizaParcela = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento)
                                    VALUES ('$idPedido', '$parcela', '$valor', '$data_kit_pagamento')";
            };

            if ($con->query($sqlAtualizaParcela)) {
                gravarLog($sqlAtualizaParcela);

                if (isset($_POST['oficina'])) {
                    $parcela_id = $id ?? $con->insert_id;
                    $data_inicial = $_POST['data_inicial'][$key];
                    $data_final = $_POST['data_final'][$key];
                    $carga_horaria = $_POST['carga_horaria'][$key];

                    if (isset($id)) {
                        $sqlAtualizaComplemento = "UPDATE parcela_complementos SET
                                                    data_inicio = '$data_inicial',
                                                    data_fim = '$data_final',
                                                    carga_horaria = '$carga_horaria'
                                                    WHERE parcela_id = '$parcela_id'";
                        unset($id);
                    } else {
                        $sqlAtualizaComplemento = "INSERT INTO parcela_complementos
                                            (parcela_id, data_inicio, data_fim, carga_horaria) VALUES 
                                            ('$parcela_id', '$data_inicial', '$data_final', '$carga_horaria')";
                    }

                    if ($con->query($sqlAtualizaComplemento)) {
                        gravarLog($sqlAtualizaComplemento);
                    } else {
                        $erro = true;
                    }
                }

            } else {
                $erro = true;
            }
        }
    }

    if (isset($erro)) {
        $mensagem = mensagem('danger', 'Erro ao gravar as parcelas. Tente Novamente');
    } else {
        if (isset($_POST['editarParcelas'])) {
            if (count($parcelas) > 0) {
                foreach ($parcelas as $parcela) {
                    if (isset($_POST['oficina'])) {
                        $sqlDeletaParcela = "UPDATE parcela_complementos SET publicado = '0' WHERE parcela_id = '{$parcela['id']}'";
                        if ($con->query($sqlDeletaParcela)) {
                            gravarLog($sqlDeletaParcela);
                        }
                    }
                    $sqlDeletaParcela = "UPDATE parcelas SET publicado = '0' WHERE pedido_id = '$idPedido' AND numero_parcelas = '{$parcela['numero_parcelas']}'";
                    if ($con->query($sqlDeletaParcela)) {
                        gravarLog($sqlDeletaParcela);
                    }
                }
            }
        }

        $queryParcelas = $con->query("SELECT valor, data_pagamento, numero_parcelas FROM parcelas WHERE pedido_id = '$idPedido' AND publicado = '1' ORDER BY numero_parcelas")->fetch_all(MYSQLI_ASSOC);
        $forma_pagamento = "";

        foreach ($queryParcelas as $parcela) {
            $valor = dinheiroParaBr($parcela['valor']);
            $data = exibirDataBr($parcela['data_pagamento']);
            $forma_pagamento .= "{$parcela['numero_parcelas']}º Parcela R$ $valor. Entrega de Documentos a partir de $data. \n";
        }

        $forma_pagamento .= "\nO pagamento de cada parcela se dará no 20º (vigésimo) dia após a data de entrega de toda documentação correta relativa ao pagamento.";

        $sqlPedidoParcela = "UPDATE pedidos SET
                                    numero_parcelas = '$num_parcelas',
                                    forma_pagamento = '$forma_pagamento'
                                    WHERE id = '$idPedido'";
        if ($con->query($sqlPedidoParcela)) {
            gravarLog($sqlPedidoParcela);
            $mensagem = mensagem('success', 'Parcelas atualizadas com sucesso.');
        }
    }
}

if (isset($_POST['gravar'])) {
    $idVerba = $_POST["verba_id"];
    $valor_total = dinheiroDeBr($_POST["valor_total"]);
    $num_parcelas = $_POST["numero_parcelas"];
    $forma_pagamento = trim(addslashes($_POST["forma_pagamento"]));
    $justificativa = trim(addslashes($_POST["justificativa"]));
    $observacao = trim(addslashes($_POST["observacao"]));
    $idPedido = $_POST["idPedido"];
    $tipoPessoa = $_POST["tipoPessoa"];
    $idProponent = $_POST["idProponente"];
    $data_kit_pagamento = $_POST["data_kit"];

    if ($num_parcelas == 1 || $num_parcelas == 13 || (isset($oficina) && $num_parcelas == 6)) {
        $data_kit_pagamento = date('Y-m-d', strtotime("+1 days", strtotime($data_kit_pagamento)));
    } else {
        $queryParcela = "SELECT data_pagamento FROM parcelas WHERE pedido_id = '$idPedido' AND numero_parcelas = 1";
        $data_kit_pagamento = $con->query($queryParcela)->fetch_row()[0];
    }

    $query = "UPDATE pedidos SET 
                   verba_id = '$idVerba',
                   numero_parcelas = '$num_parcelas',
                   valor_total = '$valor_total',
                   forma_pagamento = '$forma_pagamento',
                   data_kit_pagamento = '$data_kit_pagamento',
                   justificativa = '$justificativa',
                   observacao = '$observacao'
                WHERE id = '$idPedido'";

    if ($con->query($query)) {
        $mensagem = mensagem('success', 'Detalhes da parcela gravados no sistema');
    } else {
        $mensagem = mensagem('danger', 'Erro ao gravar os dados. Tente novamente.');
    }
}

$pedido = recuperaDados("pedidos", "id", $idPedido);

if ($pedido['numero_parcelas'] != null) {
    $parcelas = $pedido['numero_parcelas'];
} else {
    $parcelas = 0;
}

$data_kit = mysqli_fetch_row(mysqli_query($con, $query_data))[0];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Pedido de Contratação</h2>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <form action="?perfil=evento&p=pedido_parcelas" id="form_parcelas" method="POST" role="form"
                      data-parcelas="<?= $parcelas ?>">
                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                    <input type="hidden" name="tipoPessoa" value="<?= $tipoPessoa ?>">
                    <input type="hidden" name="idProponente" value="<?= $idProponente ?>">
                    <input type="hidden" name="data_kit" id="dataKit" value="<?= $data_kit ?>">

                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Detalhes do pagamento</h3>
                        </div>

                        <div class="row" align="center">
                            <?= $mensagem ?? "" ?>
                        </div>
                        <div id="mensagem-alerta">
                            <?php if ($data_kit == null || $data_kit == "0000-00-00"):
                                //estava no if da função verificaParcela
                                $next_step = "$('.next-step').prop('disabled', true)";
                                ?>
                                <div class="alert alert-danger col-md-12" role="alert">Crie uma ocorrência antes de
                                    prosseguir com pedido.
                                </div>
                            <?php else:
                                $next_step = "";
                            endif; ?>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-8">
                                    <label for="verba_id">Verba *</label>
                                    <select class="form-control" required id="verba_id" name="verba_id">
                                        <option value="">Selecione...</option>
                                        <?php
                                        geraOpcao("verbas", $pedido['verba_id'])
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="valor_total">Valor Total</label>
                                    <input type="text"
                                           id="valor_total" name="valor_total" class="form-control"
                                           value="<?= dinheiroParaBr($pedido['valor_total']) ?>"
                                        <?= $readonlyValorTotal ?> maxlength="10">
                                </div>
                            </div>

                            <div class="row">
                                <?php if (isset($oficina)): ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="numero_parcelas">Número de Parcelas *</label>
                                            <select class="form-control" id="numero_parcelas" name="numero_parcelas"
                                                    data-oficina="1" required
                                                    onchange="formaPagamento(); formParcela()">
                                                <option value="">Selecione...</option>
                                                <?php
                                                if ($pedido['numero_parcelas'] == 3) {
                                                    $option = 4;
                                                } elseif ($pedido['numero_parcelas'] == 4) {
                                                    $option = 3;
                                                } else {
                                                    $option = $pedido['numero_parcelas'];
                                                }
                                                geraOpcaoParcelas("oficina_opcoes", $option);
                                                ?>
                                            </select>
                                            <div class="has-error" id="msgParcelas">
                                                <span class="help-block text-danger"><strong>É necessário editar as parcelas</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="numero_parcelas">Número de Parcelas *</label>
                                            <select class="form-control" id="numero_parcelas" name="numero_parcelas"
                                                    data-oficina="0" required
                                                    onchange="formaPagamento(); formParcela()">
                                                <option value="">Selecione...</option>
                                                <?php geraOpcaoParcelas("parcela_opcoes", $pedido['numero_parcelas']); ?>
                                            </select>
                                            <div class="has-error" id="msgParcelas">
                                                <span class="help-block text-danger"><strong>É necessário editar as parcelas</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="col-md-2" style="margin-top: 2.2%;">
                                    <a href="?perfil=evento&p=parcelas_edita" class="btn btn-primary" id="btnParcelas">
                                        Editar Parcelas
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="forma_pagamento">Forma de pagamento *</label><br/>
                                    <textarea id="forma_pagamento" name="forma_pagamento" class="form-control" required
                                              rows="8"><?= $pedido['forma_pagamento'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="observacao">Observação</label>
                                    <input type="text" id="observacao" name="observacao" class="form-control"
                                           maxlength="255" value="<?= $pedido['observacao'] ?>">
                                </div>
                            </div>
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Justicativa da Contratação</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="justificativa">Justificativa *</label><br/>
                                    <textarea id="justificativa" name="justificativa" class="form-control"
                                              required rows="8"><?= $pedido['justificativa'] ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="box-footer">
                                <input name="gravar" type="submit" class="pull-right btn btn-primary" id="btnGravar"
                                       value="Gravar"
                            </div>
                            <!-- /.box-footer-->
                        </div>
                    </div>
                </form>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

<script>
    function formaPagamento() {
        let selectParcela = $('#numero_parcelas');
        let oficina = selectParcela.data('oficina');
        let formaPagamento = $('#forma_pagamento');
        let btnParcelas = $('#btnParcelas');
        let msgParcelas = $('#msgParcelas');
        let btnGravar = $('#btnGravar');

        if (selectParcela.val() == 1) {
            $('#forma_pagamento').val('O pagamento se dará no 20º (vigésimo) dia após a data de entrega de toda documentação correta relativa ao pagamento.');
            formaPagamento.attr('readonly', true);
        }


        if (oficina) {
            if (selectParcela.val() == 6 || selectParcela.val() == 1) {
                if (selectParcela.val() != 1) {
                    formaPagamento.attr('readonly', false);
                }
                btnParcelas.hide();
                msgParcelas.hide();
                btnGravar.attr('disabled', false);

            } else {
                formaPagamento.attr('readonly', true);
                btnParcelas.show();
                msgParcelas.show();
                btnGravar.attr('disabled', true);
            }
        } else {
            if (selectParcela.val() == 13 || selectParcela.val() == 1 || selectParcela.val() == "") {
                if (selectParcela.val() != 1) {
                    formaPagamento.attr('readonly', false);
                }
                btnParcelas.hide();
                msgParcelas.hide();
                btnGravar.attr('disabled', false);
            } else {
                formaPagamento.attr('readonly', true);
                btnParcelas.show();
                msgParcelas.show();
                btnGravar.attr('disabled', true);
            }
        }
    }

    function verificaParcela() {
        let parcelas = $('#form_parcelas').data('parcelas');
        let btnGravar = $('#btnGravar');
        let msgParcelas = $('#msgParcelas');

        if (parcelas) {
            btnGravar.attr('disabled', false);
            msgParcelas.hide();
        } else {
            btnGravar.attr('disabled', true);
            msgParcelas.show();
        }

        <?=$next_step?>
    }

    function formParcela() {
        let nParcela = $('#numero_parcelas').val();
        let valorTotal = $('#valor_total').val();
        let btnEditaParcela = $('#btnParcelas');

        btnEditaParcela.attr('href', '?perfil=evento&p=parcelas_edita&nParcelas=' + nParcela + '&valorTotal=' + valorTotal);
    }


    $(document).ready(formaPagamento());
    $(document).ready(verificaParcela());
    $(document).ready(formParcela());

    $(document).ready(function () {
        $('#valor_total').mask('000.000,00', {reverse: true});
    })
</script>