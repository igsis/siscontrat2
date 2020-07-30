<!-- Sweet Alert 2 -->
<script src="../visual/plugins/sweetalert2/sweetalert2.min.js"></script>
<link rel="stylesheet" href="../visual/plugins/sweetalert2/sweetalert2.css">
<?php
$con = bancoMysqli();
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2";
$http = $server . "/pdf/";
$link_todosArquivos = $http . "impressao_contrato_todosArquivos.php";


if (isset($_POST['Voltar'])) {
    $idEvento = $_POST['idEvento'];
    $idPedido = $_POST['idPedido'];
}

if (isset($_POST['selecionar'])) {
    $idPedido = $_POST['idPedido'];
    $pedido = recuperaDados('pedidos', 'id', $idPedido);
    $idEvento = $pedido['origem_id'];
    $idPf = $_POST['idPf'];
    if (isset($_POST['editOnly'])) {

    } else {
        $sql = "UPDATE pedidos SET pessoa_fisica_id = '$idPf', pessoa_tipo_id = 1, pessoa_juridica_id = null WHERE id = '$idPedido'";
        if (mysqli_query($con, $sql)) {
            $mensagem = mensagem("success", "Troca efetuada com sucesso!");
        } else {
            $mensagem = mensagem("danger", "Ocorreu um erro ao trocar proponente! Tente novamente.");
        }
    }

} else if (isset($_POST['selecionarPj'])) {
    $idPj = $_POST['idPj'];
    $idPedido = $_POST['idPedido'];
    $pedido = recuperaDados('pedidos', 'id', $idPedido);
    $idEvento = $pedido['origem_id'];

    if (isset($_POST['editOnly'])) {

    } else {
        $sql = "UPDATE pedidos SET pessoa_juridica_id = '$idPj', pessoa_tipo_id = 2, pessoa_fisica_id = null WHERE id ='$idPedido' AND origem_tipo_id = 1";
        if (mysqli_query($con, $sql)) {
            $mensagem = mensagem("success", "Troca efetuada com sucesso!");
        } else {
            $mensagem = mensagem("danger", "Ocorreu um erro ao trocar proponente! Tente novamente.");
        }
    }

} else if (isset($_POST['carregar'])) {
    $idLider = $_POST['idLider'];
    $idAtracao = $_POST['idAtracao'];
    $idPedido = $_POST['idPedido'];
    $pedido = recuperaDados('pedidos', 'id', $idPedido);
    $idEvento = $pedido['origem_id'];

} else if (isset($_POST['cadastraLider'])) {
    $idLider = $_POST['idLider'];
    $idAtracao = $_POST['idAtracao'];
    $idPedido = $_POST['idPedido'];
    $pedido = recuperaDados('pedidos', 'id', $idPedido);
    $idEvento = $pedido['origem_id'];

    $sql = "SELECT * FROM lideres WHERE atracao_id = '$idAtracao' AND pedido_id = '$idPedido'";
    $query = mysqli_query($con, $sql);
    $num = mysqli_num_rows($query);

    if ($num > 0)
        $sql = "UPDATE lideres SET pessoa_fisica_id = '$idLider' WHERE atracao_id = '$idAtracao' AND pedido_id = '$idPedido'"; #update
    else
        $sql = "INSERT INTO lideres (pedido_id, atracao_id, pessoa_fisica_id) VALUE ('$idPedido', '$idAtracao', '$idLider')"; #insert

    if (mysqli_query($con, $sql)) {
        #foi
        $mensagem = mensagem("success", "Troca realizada com sucesso!");

        gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao efetuar a troca!");
    }

}

if (isset($_POST['load'])) {
    unset($_SESSION['idEvento']);
    $idEvento = $_POST['idEvento'];
    $_SESSION['idEvento'] = $idEvento;
}

if (isset($_POST['salvar'])) {
    $idPedido = $_POST['idPedido'];
    $idEvento = $_POST['idEvento'];
    if ($nivelUsuario == 1) { // alterar o operador e/ou o status do pedido
        $operador = $_POST['operador'] ?? NULL;
        $status = $_POST['status'] ?? NULL;

        if ($operador == NULL) {

        } else {
            $sql = "UPDATE contratos SET usuario_contrato_id = '$operador' WHERE pedido_id = '$idPedido'";
            if (mysqli_query($con, $sql))
                gravarLog($sql);
        }

        if ($status == NULL) {

        } else {
            $sql = "UPDATE pedidos SET status_pedido_id = '$status' WHERE id = '$idPedido' AND origem_tipo_id = 1";
            if (mysqli_query($con, $sql)) {
                gravarLog($sql);
            }

            if ($status == 20) {
                $sql = "UPDATE eventos SET evento_status_id = 5 WHERE id = '$idEvento'";
                gravarLog($sql);
                mysqli_query($con, $sql);
            }
        }
    }

    $testaFilme = $con->query("SELECT tipo_evento_id FROM eventos WHERE id = $idEvento")->fetch_array();

    if ($testaFilme['tipo_evento_id'] == 1) {
        $idAtracao = $_POST['idAtracao'];
        $nome_atracao = $_POST['nome_atracao'];
        $integrantes = $_POST['integrantes'];
        for ($i = 0; $i < count($idAtracao); $i++) { // altera de uma ou de todas as atracoes (nome da atracao e integrantes)
            $baldeId = $idAtracao[$i];
            $baldeNome = addslashes($nome_atracao[$i]);
            $baldeIntegrantes = addslashes($integrantes[$i]);

            $sql = "UPDATE atracoes SET 
                    nome_atracao = '$baldeNome', 
                    integrantes = '$baldeIntegrantes' 
                    WHERE id = '$baldeId'";

            mysqli_query($con, $sql);
        }
    }
    //pedidos
    $formaPagamento = trim(addslashes($_POST['formaPagamento']));
    $verba = $_POST['verba'];
    $processoMae = $_POST['processoMae'];
    $processo = $_POST['processo'];
    $justificativa = trim(addslashes($_POST['justificativa']));
    $operador = $_POST['operador'] ?? NULL;
    $pedido = recuperaDados('pedidos', 'id', $idPedido);
    $pendencia = trim(addslashes($_POST['pendencia']));

    //eventos
    $fiscal = $_POST['fiscal'];
    $suplente = $_POST['suplente'] ?? null;

    $sqlEvento = "UPDATE eventos SET fiscal_id = '$fiscal', suplente_id ='$suplente' WHERE id = '$idEvento'";
    $sqlPedido = "UPDATE pedidos SET numero_processo = '$processo', numero_processo_mae = '$processoMae', forma_pagamento = '$formaPagamento', justificativa = '$justificativa', verba_id = '$verba', pendencias_contratos = '$pendencia' WHERE id = '$idPedido' AND origem_tipo_id = 1";

    if (mysqli_query($con, $sqlPedido) && mysqli_query($con, $sqlEvento)) {
        if ($operador != NULL) {
            $trocaOp = $con->query("UPDATE pedidos SET operador_id = '$operador' WHERE id = $idPedido AND origem_id = 1");
        }
        if ($processo != NULL || $processoMae != NULL) {
            $atualizaStatus = $con->query("UPDATE pedidos SET status_pedido_id = 13 WHERE id = $idPedido AND origem_id = 1");
            if ($atualizaStatus) {
                $testaEtapa = $con->query("SELECT pedido_id, data_contrato FROM pedido_etapas WHERE pedido_id = $idPedido")->fetch_assoc();
                $data = dataHoraNow();
                if ($testaEtapa != NULL && $testaEtapa['data_contrato'] == "0000-00-00 00:00:00" || $testaEtapa['data_contrato'] != "0000-00-00 00:00:00") {
                    $updateEtapa = $con->query("UPDATE pedido_etapas SET data_contrato = '$data' WHERE pedido_id = '$idPedido'");
                }
                if ($testaEtapa == NULL) {
                    $insereEtapa = $con->query("INSERT INTO pedido_etapas (pedido_id, data_contrato) VALUES ('$idPedido', '$data')");
                }
            }

        }
        gravarLog($sqlEvento);
        gravarLog($sqlPedido);
        $mensagem = mensagem("success", "Atualizações salvas com sucesso!");
    } else {
        $mensagem = mensagem("danger", "Erro ao salvar alterações! Tente novamente.");
    }
}

if (isset($_POST['reabertura'])) {
    $idEvento = $_POST['idEvento'];
    $now = date('Y-m-d H:i:s', strtotime("-3 Hours"));
    $idUsuario = $_SESSION['usuario_id_s'];
    $sql = "INSERT INTO evento_reaberturas (evento_id, data_reabertura, usuario_reabertura_id) VALUES ('$idEvento', '$now', '$idUsuario')";
    $sqlStatus = "UPDATE eventos SET evento_status_id = 1 WHERE id = '$idEvento'";

    if ((mysqli_query($con, $sql)) && (mysqli_query($con, $sqlStatus))) {
        $mensagem = "<script>
                    Swal.fire({
                        title: 'Reabertura',
                        html: 'Reabertura do evento realizada com sucesso!',
                        type: 'success',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showCancelButton: false,
                        confirmButtonText: 'Ok'
                    }).then(function() {
                        window.location.href = 'index.php';
                    });
                </script>";
        gravarLog($sql);
        unset($_SESSION['idEvento']);
        unset($_SESSION['idPedido']);
    } else {
        $mensagem = mensagem("danger", "Erro ao efetuar a reabertura do evento! Tente novamente.");
    }
}

if (isset($_POST['parcelaEditada'])) {
    $idPedido = $_POST['idPedido'];
    $idEvento = $_POST['idEvento'];
    $numParcelas = $_POST['numParcelas'];
    $checaOficina = $_POST['checaOficina'];

    $parcelas = $con->query("SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND publicado = 1")->fetch_all(MYSQLI_ASSOC);

    if (count($parcelas) > 0) {
        foreach ($parcelas as $parcela) {
            if (isset($_POST['checaOficina'])) {
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

    if (isset($_POST['parcelaEditada']) && $numParcelas != NULL) {

        foreach ($_POST['parcela'] AS $countPost => $parcela) {
            $valor = dinheiroDeBr($_POST['valor'][$countPost]) ?? NULL;
            $data_pagamento = $_POST['data_pagamento'][$countPost] ?? NULL;

            $sqlParcelas = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento) VALUES ('$idPedido', '$parcela', '$valor', '$data_pagamento')";

            if ($con->query($sqlParcelas)) {
                if ($checaOficina == 1) {
                    $idParcela = $con->insert_id;
                    $data_inicio = $_POST['data_inicio'][$countPost] ?? NULL;
                    $data_fim = $_POST['data_fim'][$countPost] ?? NULL;
                    $cargaHoraria = $_POST['cargaHoraria'][$countPost] ?? NULL;

                    $queryComplementos = $con->query("INSERT INTO parcela_complementos (parcela_id, data_inicio, data_fim, carga_horaria) VALUES 
                                            ('$idParcela', '$data_inicio', '$data_fim', '$cargaHoraria')");

                }

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
        $forma = $countForma + 1 . "º parcela R$ " . $parcelasArray['valor'] . ". Entrega de documentos a partir de " . exibirDataBr($parcelasArray['data_pagamento']) . ".\n";
        $formaCompleta = $formaCompleta . $forma;

        $countForma += 1;
    }
    $formaCompleta = $formaCompleta . "\nO pagamento de cada parcela se dará no 20º (vigésimo) dia após a data de entrega de toda documentação correta relativa ao pagamento.";

    $sqlForma = "UPDATE pedidos SET forma_pagamento = '$formaCompleta' WHERE id = $idPedido AND origem_tipo_id = 1";
    mysqli_query($con, $sqlForma);
}

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

$evento = recuperaDados('eventos', 'id', $idEvento);
$sql = "SELECT * FROM pedidos where origem_tipo_id = 1 AND origem_id = '$idEvento' AND publicado = 1";
$query = mysqli_query($con, $sql);
$pedido = mysqli_fetch_array($query);

$idPedido = $pedido['id'];


$contrato = recuperaDados('contratos', 'pedido_id', $pedido['id']);
$sqlAtracao = "SELECT * FROM atracoes where evento_id = '$idEvento' AND publicado = 1";
$queryAtracao = mysqli_query($con, $sqlAtracao);

$testaFilme = $con->query("SELECT tipo_evento_id FROM eventos WHERE id = $idEvento")->fetch_array();
if ($testaFilme['tipo_evento_id'] == 2) {
    $escondeLider = 1;
} else {
    $escondeLider = 0;
}

$sql = "SELECT * FROM  chamados where evento_id = '$idEvento'";
$query = mysqli_query($con, $sql);
$chamado = mysqli_fetch_array($query);
$disabledImpr = "";
$disableDown = "";
//$idChamado = $chamado['id'];

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
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <form method="POST" action="?perfil=contrato&p=resumo"
                          role="form">
                        <div class="box-body">

                            <?php
                            if ($nivelUsuario == 3) {
                                ?>
                                <div class="row">
                                    <div class="col-md-6 from-group">
                                        <label for="operador">Operador</label>
                                        <select name="operador" id="operador" class="form-control">
                                            <option value="">Selecione um operador</option>
                                            <?php
                                            geraOpcao('usuarios u INNER JOIN usuario_contratos uc on uc.usuario_id = u.id WHERE uc.nivel_acesso != 1', $pedido['operador_id']);
                                            ?>
                                        </select>
                                    </div>

                                    <?php
                                    $nomeStatus = $con->query("SELECT status FROM pedido_status WHERE id = " . $pedido['status_pedido_id'])->fetch_array();
                                    ?>

                                    <div class="col-md-6 form-group">
                                        <label for="status">Status Contrato</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="<?= $pedido['status_pedido_id'] ?>"><?= $nomeStatus['status'] ?></option>
                                            <?php
                                            $sqlStatus = "SELECT id, status FROM pedido_status WHERE id NOT IN (1,3) AND id != " . $pedido['status_pedido_id'] . " AND area = 1 ORDER BY ordem";
                                            $queryStatus = mysqli_query($con, $sqlStatus);
                                            while ($status = mysqli_fetch_array($queryStatus)) {
                                                echo "<option value='" . $status['id'] . "'>" . $status['status'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <?php
                            }
                            if ($nivelUsuario == 2 || $nivelUsuario == 1) {
                                $nomeStatus = $con->query("SELECT status FROM pedido_status WHERE id = " . $pedido['status_pedido_id'])->fetch_array();
                                ?>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="status">Status Contrato</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="<?= $pedido['status_pedido_id'] ?>"><?= $nomeStatus['status'] ?></option>
                                            <?php
                                            $sqlStatus = "SELECT id, status FROM pedido_status WHERE id NOT IN (1,3) AND id != " . $pedido['status_pedido_id'] . " AND area = 1 ORDER BY ordem";
                                            $queryStatus = mysqli_query($con, $sqlStatus);
                                            while ($status = mysqli_fetch_array($queryStatus)) {
                                                echo "<option value='" . $status['id'] . "'>" . $status['status'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                            <?php }
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
                                        <label for="valor">Valor Individual: </label>
                                        <input type="text" readonly
                                               value="<?= dinheiroParaBr($atracao['valor_individual']) ?>"
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="integrantes[]">Integrantes* </label>
                                        <textarea name="integrantes[]" id="integrantes" required rows="5"
                                                  class="form-control"><?= $atracao['integrantes'] ?></textarea>
                                    </div>
                                </div>
                                <hr>
                                <?php
                            }
                            ?>

                            <?php
                            if ($pedido['numero_parcelas'] == 1 || $pedido['numero_parcelas'] == 13) {
                                $readonly = "";
                            } else {
                                $readonly = "readonly";
                            }
                            ?>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="formaPagamento">Forma de pagamento</label>
                                    <textarea name="formaPagamento" id="formaPagamento" rows="5" required
                                              class="form-control"
                                              <?= $readonly ?>><?= $pedido['forma_pagamento'] ?></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="justificativa">Justificativa</label>
                                    <textarea name="justificativa" id="justificativa" rows="5" required
                                              class="form-control"><?= $pedido['justificativa'] ?></textarea>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="valorTotal">Valor Total: </label>
                                    <input type="text" value="<?= dinheiroParaBr($pedido['valor_total']) ?>"
                                           onKeyPress="return(moeda(this,'.',',',event))" class="form-control"
                                           readonly
                                           name="valorTotal" id="valorTotal">
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
                                    <label for="suplente">Suplente *</label>
                                    <select class="form-control" id="suplente" name="suplente" required>
                                        <option value="">Selecione um suplente...</option>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, $evento['suplente_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <?php
                            if ($pedido['numero_parcelas'] != 1) {
                                ?>
                                <br>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="?perfil=contrato&p=edita_parcelas&id=<?= $idEvento ?>">
                                            <button type="button" style="width: 35%"
                                                    class="btn btn-primary center-block">
                                                Editar parcelas
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>

                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="pendencia">Pendências no Setor de Contratos Artísticos:</label>
                                    <textarea name="pendencia" rows="5"
                                              class="form-control"><?= $pedido['pendencias_contratos'] ?></textarea>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" name="salvar" id="salvar" class="btn btn-primary center-block"
                                style="width: 40%;">
                            Salvar
                        </button>
                        <br>
                        <br>
                    </form>
                    <div class="row">
                        <div class="col-md-4">
                            <form action="?perfil=contrato&p=detalhe_evento" method="post" role="form" target="_blank">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                                <button type="submit" class="btn btn-primary pull-right " name="detalheEvento"
                                        style="width: 95%"> Ver detalhes do Evento
                                </button>
                            </form>
                        </div>

                        <?php if (isset($chamado)): ?>
                            <div class="col-md-4">
                                <form action="?perfil=contrato&p=chamados_contrato" method="post" role="form">
                                    <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                    <button type="submit" class="btn btn-primary btn-block"> Ver chamados</button>
                                </form>
                            </div>
                        <?php else : ?>
                            <div class="col-md-4">
                                <!-- <form action="?perfil=contrato&p=chamados_contrato" method="post" role="form">
                                    <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                    <button type="submit" class="btn btn-primary btn-block"> Ver chamados</button>
                                </form> -->
                            </div>
                        <?php endif ?>

                        <?php
                        if ($idEvento) {
                            $sqlEvento = $con->query("SELECT arq.* FROM arquivos AS arq 
                        INNER JOIN lista_documentos ld on arq.lista_documento_id = ld.id 
                        WHERE arq.publicado = '1' AND origem_id = '$idEvento' AND ld.tipo_documento_id='3'")->num_rows;
                            if ($sqlEvento == 0 || $sqlEvento == "") {
                                $disableDown = "";
                                ?>
                                <div class="col-md-4">
                                    <form action="<?= $link_todosArquivos ?>" method="post" target="_blank">
                                        <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                        <button type="submit" <?= $disableDown ?> class="btn btn-primary pull-right "
                                                style="width: 95%"> Baixar todos os arquivos
                                        </button>
                                    </form>
                                </div>
                            <?php }
                        } ?>
                        <!-- <div class="col-md-3">
                            <form action="?perfil=contrato&p=anexos_pedido" method="post" role="form">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                                <button type="submit" class="btn btn-primary pull-left btn-block" style="width: 95%">
                                    Abrir anexos do Pedido
                                </button>
                            </form>
                        </div>-->
                    </div>

                    <br>
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
                                            <form action="?perfil=contrato&p=edita_pf" method="POST">
                                                <input type="hidden" name="idPf" id="idPf" value="<?= $idPf ?>">
                                                <input type="hidden" name="idPedido" id="idPedido"
                                                       value="<?= $idPedido ?>">
                                                <button type="submit" class="btn btn-primary btn-block"><span
                                                            class="glyphicon glyphicon-pencil"></span></button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="?perfil=contrato&p=tipo_pessoa"
                                                  method="POST">
                                                <input type="hidden" name="idPedido" id="idPedido"
                                                       value="<?= $idPedido ?>">
                                                <button type="submit" class="btn btn-info btn-block"><span
                                                            class="glyphicon glyphicon-random"></span></button>
                                            </form>
                                        </td>
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
                                <thead>
                                <tr>
                                    <th>Proponente</th>
                                    <th width="5%">Editar</th>
                                    <th width="5%">Trocar</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= $proponente['razao_social'] ?></td>
                                    <td>
                                        <form action="?perfil=contrato&p=edita_pj" method="POST">
                                            <input type="hidden" name="idPedido" id="idPedido"
                                                   value="<?= $idPedido ?>">
                                            <input type="hidden" name="idPj" id="idPj" value="<?= $idPj ?>">
                                            <input type="hidden" name="idEvento" id="idEvento"
                                                   value="<?= $idEvento ?>">
                                            <button type="submit" name="load" id="load"
                                                    class="btn btn-primary btn-block"><span
                                                        class="glyphicon glyphicon-pencil"></span></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="?perfil=contrato&p=tipo_pessoa"
                                              method="POST">
                                            <input type="hidden" name="idPedido" id="idPedido"
                                                   value="<?= $idPedido ?>">
                                            <button type="submit" class="btn btn-info btn-block"><span
                                                        class="glyphicon glyphicon-random"></span></button>
                                        </form>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    //determina se há necessidade de esconder ou não a aba de lider da página e determina se ela será para edição ou cadastro
                    if ($escondeLider == 0) {
                    $numAtracao = $con->query("SELECT id FROM atracoes WHERE evento_id = $idEvento")->num_rows;
                    $numLider = $con->query("SELECT * FROM lideres WHERE pedido_id = $idPedido")->num_rows;
                    if ($numLider > 0 && $numLider >= $numAtracao){
                    $disabledImpr = "";
                    ?>

                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Líderes</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Atração</th>
                                    <th>Lider</th>
                                    <th width="5%">Editar</th>
                                    <th width="5%">Trocar</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                while ($atracao = mysqli_fetch_array($query_atracao)) {
                                    ?>
                                    <tr>
                                        <td><?= $atracao['nome_atracao'] ?></td>
                                        <td><?= $atracao['nome'] ?></td>
                                        <td>
                                            <form action="?perfil=contrato&p=edita_lider" method="POST">
                                                <input type="hidden" name="idLider"
                                                       value="<?= $atracao['pessoa_fisica_id'] ?>">
                                                <input type='hidden' name='idAtracao'
                                                       value="<?= $atracao['id'] ?>">
                                                <input type='hidden' name='idPedido' value='<?= $idPedido ?>'>
                                                <button type="submit" name="carregar"
                                                        class="btn btn-primary btn-block"><span
                                                            class="glyphicon glyphicon-pencil"></span></button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="POST" action="?perfil=contrato&p=pesquisa_lider"
                                                  role="form">
                                                <input type='hidden' name='idAtracao'
                                                       value="<?= $atracao['id'] ?>">
                                                <input type='hidden' name='idPedido' value='<?= $idPedido ?>'>
                                                <button type="submit" name='trocaLider'
                                                        class="btn btn-info btn-block">
                                                    <span class="glyphicon glyphicon-random"></span></button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>

                        <?php } else {
                        $disabledImpr = "disabled";
                        ?>
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <h3 class="box-title">Líderes</h3>
                            </div>
                            <div class="box-body">
                                <div class='col-md-12' style='text-align:center'>
                                    <span style='color: red; font-size: 16px'><strong>Para ter acesso á area de impressão é necessário cadastrar os líderes restantes</strong></span>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Atração</th>
                                        <th>Lider</th>
                                        <th width="5%">Cadastrar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    while ($atracao = mysqli_fetch_array($query_atracao)) {
                                        ?>
                                        <tr>
                                            <td><?= $atracao['nome_atracao'] ?></td>
                                            <td><?= $atracao['nome'] ?></td>
                                            <td>
                                                <form method="POST" action="?perfil=contrato&p=pesquisa_lider"
                                                      role="form">
                                                    <input type='hidden' name='idAtracao'
                                                           value="<?= $atracao['id'] ?>">
                                                    <input type='hidden' name='idPedido' value='<?= $idPedido ?>'>
                                                    <button type="submit" name='trocaLider'
                                                            class="btn btn-info btn-block">
                                                        <span class="glyphicon glyphicon-plus"></span></button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } //else
                            } //if esconde lider
                            } //if pessoa_tipo == 2 ?>


                            <form action="?perfil=contrato&p=area_impressao" target="_blank" method="post" role="form">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" <?= $disabledImpr ?> class="btn btn-info pull-right"
                                        style="margin: 0 10px;">Ir para a
                                    área
                                    de impressão
                                </button>
                                <?php
                                if ($nivelUsuario != 2) { ?>
                                    <button type="button" class="btn btn-info" name="reabre"
                                            style="margin:0 10px;width: 25%"
                                            id="reabre" data-toggle="modal" data-target="#reabrir">
                                        Reabertura
                                    </button>
                                <?php } ?>

                                <a href="?perfil=contrato&p=pesquisa_contratos">
                                    <button type="button" class="btn btn-info pull-left" style="margin: 0 10px;">
                                        Voltar
                                    </button>
                                </a>
                            </form>
                            <?php
                            //apenas para questões estéticas
                                if($nivelUsuario == 2){ ?>
                                    <br>
                            <?php } ?>
                            <br>
                        </div>
                    </div>
                </div>
                <div id="reabrir" class="modal modal fade in" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Confirmação de Reabertura</h4>
                            </div>
                            <form action="?perfil=contrato&p=resumo"
                                  role="form" method="post">
                                <div class="modal-body">
                                    <p>Tem certeza que deseja reabrir este evento?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                                        Cancelar
                                    </button>
                                    <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                                    <button type="submit" class="btn btn-primary" name="reabertura">Reabrir</button>
                                </div>
                            </form>
                        </div>
                    </div>
    </section>
</div>

<script>
    let suplente = $('#suplente');
    let btn = $('#salvar');

    function bloqueiaBtn() {
        if (suplente.val() == "") {
            btn.prop('disabled', true);
        } else {
            btn.prop('disabled', false);
        }
    }

    suplente.on('change', bloqueiaBtn);

</script>