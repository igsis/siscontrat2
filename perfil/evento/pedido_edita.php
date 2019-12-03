<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$idEvento = $_SESSION['idEvento'];

if (isset($_POST['idProponente'])) {
    $idProponente = $_POST['idProponente'];
    $tipoPessoa = $_POST['tipoPessoa'];
    $idPedido = $_POST['idPedido'] ?? null;
}

if (isset($_POST['adicionaLider'])) {
    $_SESSION['idPedido'] = $_POST['idPedido'];
    $idPedido = $_SESSION['idPedido'];
    $idAtracao = $_POST['idAtracao'] ?? NULL;
    $pedido = recuperaDados("pedidos", "id", $idPedido);
}

if(isset($_POST['trocaPf'])){
    $_SESSION['idPedido'] = $_POST['idPedido'];
    $idPedido = $_SESSION['idPedido'];
    $idPessoa = $_POST['idPf'] ?? $_POST['idPessoa'];
    $trocaPf = $con->query("UPDATE pedidos SET pessoa_fisica_id = $idPessoa WHERE id = $idPedido AND origem_tipo_id = 1");
    if($trocaPf){
        $deletaPj = $con->query("UPDATE pedidos SET pessoa_juridica_id = null, pessoa_tipo_id = 1 WHERE id = $idPedido AND origem_tipo_id = 1");
        $mensagem = mensagem('success', 'Proponente trocado com sucesso!');
    }else{
        $mensagem = mensagem('danger', 'Erro ao trocar proponente! Tente novamente.');
    }
    $pedido = recuperaDados("pedidos", "id", $idPedido);
}

if(isset($_POST['trocaPj'])){
    $_SESSION['idPedido'] = $_POST['idPedido'];
    $idPedido = $_SESSION['idPedido'];
    $idPessoa = $_POST['idPj'] ?? $_POST['idPessoa'];
    $trocaPj = $con->query("UPDATE pedidos SET pessoa_juridica_id = $idPessoa WHERE id = $idPedido AND origem_tipo_id = 1");
    if($trocaPj){
        $deletaPf = $con->query("UPDATE pedidos SET pessoa_fisica_id = null, pessoa_tipo_id = 2 WHERE id = $idPedido AND origem_tipo_id = 1");
        $mensagem = mensagem('success', 'Proponente trocado com sucesso!');
    }else{
        $mensagem = mensagem('danger', 'Erro ao trocar proponente! Tente novamente.');
    }
    $pedido = recuperaDados("pedidos", "id", $idPedido);
}

if (isset($_SESSION['idPedido']) && isset($_POST['cadastra'])) {
    unset($_POST['cadastra']);
    $_POST['carregar'] = 1;
    $idPedido = $_SESSION['idPedido'];
} else {
    if (isset($_POST['cadastra'])) {
        $tipoPessoa = $_POST['pessoa_tipo_id'];
        $idPessoa = $_POST['pessoa_id'];
        $valorTotal = $_POST['valor'];
        $tipoEvento = $_POST['tipoEvento'];

        if ($tipoPessoa == 1) {
            $campo = "pessoa_fisica_id";
        } else {
            $campo = "pessoa_juridica_id";
        }
        $sqlFirst = "INSERT INTO pedidos (origem_tipo_id, origem_id, pessoa_tipo_id, $campo, valor_total, publicado) 
                                  VALUES ($tipoEvento, $idEvento, $tipoPessoa, $idPessoa, $valorTotal, 1)";
        if (mysqli_query($con, $sqlFirst)) {
            $_SESSION['idPedido'] = recuperaUltimo("pedidos");
            $idPedido = $_SESSION['idPedido'];
            $sqlContratado = "INSERT INTO contratos (pedido_id) VALUES ('$idPedido')";
            $queryContratado = mysqli_query($con,$sqlContratado);
        } else {
            echo $sqlFirst;
        }
    }

}

if (isset($_POST['edita'])) {
    $verba_id = $_POST['verba_id'];
    $valor_total = dinheiroDeBr($_POST['valor_total']);
    $forma_pagamento = addslashes($_POST['forma_pagamento']);
    $justificativa = addslashes($_POST['justificativa']);
    $observacao = addslashes($_POST['observacao']) ?? NULL;
    $numero_parcelas = $_POST['numero_parcelas'] ?? NULL;
    $data_kit_pagamento = $_POST['data_kit_pagamento'] ?? '0000-00-00';

    if ($tipoPessoa == 1) {
        $campo = "pessoa_fisica_id";
    } else {
        $campo = "pessoa_juridica_id";
    }

    if (isset($_POST['edita'])) {
        $idPedido = $_SESSION['idPedido'];
        $numero_parcelas = $_POST['numero_parcelas'] ?? $pedido['numero_parcelas'];
        if ($numero_parcelas != 1) {
            $data_kit_pagamento = "0000-00-00";
        }

        $sql_edita = "UPDATE pedidos SET verba_id = '$verba_id', valor_total = '$valor_total', numero_parcelas = '$numero_parcelas', data_kit_pagamento = '$data_kit_pagamento', forma_pagamento = '$forma_pagamento', justificativa = '$justificativa', observacao = '$observacao' WHERE id = '$idPedido'";

        if (mysqli_query($con, $sql_edita)) {
            $mensagem = mensagem("success", "Gravado com sucesso.");
            gravarLog($sql_edita);

        } else {
            $mensagem = mensagem("danger", "Erro ao gravar: " . die(mysqli_error($con)));
        }
    }
}

$pedido = recuperaDados("pedidos", "id", $idPedido);

if ($pedido['pessoa_tipo_id'] == 2) {
    $pj = recuperaDados("pessoa_juridicas", "id", $pedido['pessoa_juridica_id']);
    $proponente = $pj['razao_social'];
    $idProponente = $pj['id'];
    $link_edita = "?perfil=evento&p=pj_edita";
} else {
    $pf = recuperaDados("pessoa_fisicas", "id", $pedido['pessoa_fisica_id']);
    $proponente = $pf['nome'];
    $idProponente = $pf['id'];
    $link_edita = "?perfil=evento&p=pf_edita";
}

$tipoPessoa = $pedido['pessoa_tipo_id'];

//verificando parcelas
$sqlParcelas = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido'";
$query = mysqli_query($con, $sqlParcelas);
$numRows = mysqli_num_rows($query);

if ($numRows > 0) {
    $somaParcelas = 0;
    while ($parcela = mysqli_fetch_array($query)) {
        $arrayValores[] = dinheiroParaBr($parcela['valor']);
        $arrayDatas[] = $parcela['data_pagamento'];
        $idsParcela [] = $parcela['id'];

        $somaParcelas += $parcela['valor'];
    }

    $StringValores = implode("|", $arrayValores);
    $StringDatas = implode("|", $arrayDatas);

    foreach ($idsParcela as $idParcela) {
        $sqlComplemento = "SELECT * FROM parcela_complementos WHERE parcela_id = '$idParcela'";
        $queryComplemento = mysqli_query($con, $sqlComplemento);
        $nComplemento = mysqli_num_rows($queryComplemento);

        if ($nComplemento > 0) {
            while ($complemento = mysqli_fetch_array($queryComplemento)) {
                $arrayInicio [] = $complemento['data_inicio'];
                $arrayFim [] = $complemento['data_fim'];
                $CargaHoraria [] = $complemento['carga_horaria'];
            }
            $StringInicio = implode("|", $arrayInicio);
            $StringFim = implode("|", $arrayFim);
            $StringCarga = implode("|", $CargaHoraria);
        }
    }
}

$displayEditar = "display: none";
$displayKit = "display: block";

if (isset($pedido['numero_parcelas'])) {
    if ($pedido['numero_parcelas'] != 1) {
        $displayEditar = "display: block";
        $displayKit = "display: none";
    } else {
        $displayEditar = "display: none";
        $displayKit = "display: block";
    }
}

if(isset($_POST['gravarValorEquipamento'])){
    $valoresEquipamentos = $_POST['valorEquipamento'];
    $equipamentos = $_POST['equipamentos'];
    $idPedido = $_SESSION['idPedido'];

    $sql_delete = "DELETE FROM valor_equipamentos WHERE pedido_id = '$idPedido'";
    mysqli_query($con, $sql_delete);

    for ($i = 0; $i < count($valoresEquipamentos); $i++){
        $valor = dinheiroDeBr($valoresEquipamentos[$i]);
        $idLocal = $equipamentos[$i];

        $sql_insert_valor = "INSERT INTO valor_equipamentos (local_id, pedido_id, valor) 
                             VALUES ('$idLocal', '$idPedido', '$valor')";

        mysqli_query($con, $sql_insert_valor);
    }
}

$sqlOficina = "SELECT * FROM atracoes WHERE evento_id = '$idEvento' AND publicado = 1";
$queryOficina = mysqli_query($con, $sqlOficina);
//$atracoes = mysqli_fetch_array($queryAtracao);

while ($atracoes = mysqli_fetch_array($queryOficina)) {
    $valores [] = $atracoes['valor_individual'];
    if ($atracoes['oficina'] == 1) {
        $oficina = 1;
    }
}

if (isset($valores) && $valores > 0) {
    $valorTotal = 0;
    foreach ($valores as $valor) {
        $valorTotal += $valor;
    }
} else {
    $valorTotal = 0;
}

if ($pedido['origem_tipo_id'] != 2 && isset($valorTotal)) {
    if ($valorTotal > $pedido['valor_total'] || $valorTotal < $pedido['valor_total']) {
        $sqlUpdate = "UPDATE pedidos SET valor_total = '$valorTotal' WHERE id = $idPedido";
        if (mysqli_query($con, $sqlUpdate)) {
            $mensagem = mensagem("warning", "O valor da sua atração foi alterado e com isso o valor total do seu pedido também mudou, verifique se o mesmo está correto e altere novamente na atração caso necessário.");
            $pedido = recuperaDados('pedidos', 'id', $idPedido);
        } else {
            echo $sqlUpdate;
        }
    }
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Pedido de Contratação</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }
            ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <!-- pedido -->
                <div class="box box-info">
                    <div class="box-header with-border">
                    </div>
                    <div class="box-body">
                        <div class="stepper">
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                <li role="presentation" class="active">
                                    <a class="persistant-disabled" href="#stepper-step-1" data-toggle="tab"
                                       aria-controls="stepper-step-1" role="tab" title="Parcelas">
                                        <span class="round-tab">1</span>
                                    </a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a class="persistant-disabled" href="#stepper-step-2" data-toggle="tab"
                                       aria-controls="stepper-step-2" role="tab" title="Proponente">
                                        <span class="round-tab">2</span>
                                    </a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a class="persistant-disabled" href="#stepper-step-3" data-toggle="tab"
                                       aria-controls="stepper-step-3" role="tab" title="Lider">
                                        <span class="round-tab">3</span>
                                    </a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a class="persistant-disabled" href="#stepper-step-4" data-toggle="tab"
                                       aria-controls="stepper-step-4" role="tab" title="Parecer artístico">
                                        <span class="round-tab">4</span>
                                    </a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a class="persistant-disabled" href="#stepper-step-5" data-toggle="tab"
                                       aria-controls="stepper-step-5" role="tab" title="Valor por equipamento">
                                        <span class="round-tab">5</span>
                                    </a>
                                </li>
                            </ul>
                            <form role="form">
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" role="tabpanel" id="stepper-step-1">
                                        <h3 class="h2">1. Detalhes de parcelas</h3>

                                        <div class="row">
                                            <div class="form-group col-md-8">
                                                <label for="verba_id">Verba *</label>
                                                <select class="form-control" id="verba_id" name="verba_id" required>
                                                    <option value="">Selecione...</option>
                                                    <?php
                                                    geraOpcao("verbas", $pedido['verba_id'])
                                                    ?>
                                                </select>
                                            </div>

                                            <?php
                                            if ($pedido['origem_tipo_id'] != 2) {
                                                $readonly = 'readonly';
                                            } else {
                                                $readonly = '';
                                            }
                                            ?>
                                            <div class="form-group col-md-4">
                                                <label for="verba_id">Valor Total</label>
                                                <input type="text" onkeypress="return(moeda(this, '.', ',', event))"
                                                       id="valor_total" name="valor_total" class="form-control"
                                                       value="<?= dinheiroParaBr($pedido['valor_total']) ?>" <?=$readonly?>>
                                            </div>
                                        </div>
                                        <?php
                                        if (isset($oficina)) {
                                            ?>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="numero_parcelas">Número de Parcelas *</label>
                                                    <select class="form-control" id="numero_parcelas" name="numero_parcelas"
                                                            required>
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
                                                </div>
                                                <button type="button" id="editarParcelas" class="btn btn-primary"
                                                        style="display: block; margin-top: 2.2%;">
                                                    Editar Parcelas
                                                </button>
                                            </div>
                                            <?php

                                        } else {
                                            ?>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="numero_parcelas">Número de Parcelas *</label>
                                                    <select class="form-control" id="numero_parcelas" name="numero_parcelas"
                                                            required>
                                                        <option value="">Selecione...</option>
                                                        <?php
                                                        geraOpcaoParcelas("parcela_opcoes", $pedido['numero_parcelas']);
                                                        ?>
                                                    </select>
                                                </div>
                                                <!-- Button trigger modal -->
                                                <button type="button" id="editarParcelas" class="btn btn-primary"
                                                        style="display: block; margin-top: 2.2%;">
                                                    Editar Parcelas
                                                </button>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="forma_pagamento">Forma de pagamento *</label><br/>
                                                <textarea id="forma_pagamento" name="forma_pagamento" class="form-control"
                                                          rows="8" <?= $pedido['numero_parcelas'] != 13 ? 'readonly' : '' ?> ><?= $pedido['forma_pagamento'] ?></textarea>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="justificativa">Justificativa *</label><br/>
                                                <textarea id="justificativa" name="justificativa" class="form-control"
                                                          rows="8"><?= $pedido['justificativa'] ?></textarea>
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
                                        <input type="hidden" name="tipoPessoa" value="<?= $tipoPessoa ?>">
                                        <input type="hidden" name="idProponente" value="<?= $idProponente ?>">
                                        <ul class="list-inline pull-right">
                                            <li>
                                                <a class="btn btn-primary next-step">Proxima etapa <span
                                                            aria-hidden="true">&rarr;</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-pane fade" role="tabpanel" id="stepper-step-2">
                                        <h3 class="h2">2. Cadastro de Proponente</h3>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-8">
                                                        <div class="jumbotrom">
                                                            <label for="proponente">Proponente</label>
                                                            <input type="text" id="proponente" name="proponente"
                                                                   class="form-control" disabled
                                                                   value="<?= $proponente ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-2"><label><br></label>
                                                        <form method="POST" action="<?= $link_edita ?>" role="form">
                                                            <input type="hidden" name="idProponente" value="<?= $idProponente ?>">
                                                            <button type="submit" name="editProponente" class="btn btn-primary btn-block">
                                                                Editar Proponente
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <div class="form-group col-md-2"><label><br></label>
                                                        <form method="POST" action="?perfil=evento&p=troca_proponente"
                                                              role="form">
                                                            <input type="hidden" name="idPedido"
                                                                   value="<?= $idPedido ?>">
                                                            <input type="hidden" name="idProponente"
                                                                   value="<?= $idProponente ?>">
                                                            <button type="submit" name="trocaProponente"
                                                                    class="btn btn-primary btn-block">Trocar de
                                                                Proponente
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="list-inline pull-right">
                                            <li>
                                                <a class="btn btn-default prev-step"><span
                                                            aria-hidden="true">&larr;</span>
                                                    Voltar</a>
                                            </li>
                                            <li>
                                                <a class="btn btn-primary next-step">Próxima etapa <span
                                                            aria-hidden="true">&rarr;</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- líderes -->
                                    <?php
                                    //if($pedido['pessoa_tipo_id'] == 2){
                                    $sql_atracao = "SELECT a.id, a.nome_atracao, pf.nome, l.pessoa_fisica_id FROM atracoes AS a                                              
                                            LEFT JOIN lideres l on a.id = l.atracao_id
                                            left join pessoa_fisicas pf on l.pessoa_fisica_id = pf.id
                                            WHERE a.publicado = 1 AND a.evento_id = '" . $_SESSION['idEvento'] . "'";
                                    $query_atracao = mysqli_query($con, $sql_atracao);
                                    ?>
                                    <div class="tab-pane fade" role="tabpanel" id="stepper-step-3">
                                        <h3 class="hs">3. Líder</h3>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Atração</th>
                                                        <th>Proponente</th>
                                                        <th width="10%">Ação</th>
                                                    </tr>
                                                    </thead>
                                                    <?php
                                                    echo "<tbody>";
                                                    while ($atracao = mysqli_fetch_array($query_atracao)) {
                                                        //analisaArray($atracao);
                                                        echo "<tr>";
                                                        echo "<td>" . $atracao['nome_atracao'] . "</td>";
                                                        if ($atracao['pessoa_fisica_id'] > 0) {
                                                            echo "<td>" . $atracao['nome'] . "</td>";
                                                            echo "<td>
                                            <form method=\"POST\" action=\"?perfil=evento&p=pesquisa_lider\" role=\"form\">
                                            <input type='hidden' name='oficina' value='" . $atracao['id'] . "'>
                                            <input type='hidden' name='lider' value='$idPedido'>
                                            <button type=\"submit\" name='pesquisar' class=\"btn btn-primary\"><i class='fa fa-refresh'></i> Trocar</button>
                                            </form>
                                        </td>";
                                                        } else {
                                                            echo "<td>
                                            <form method=\"POST\" action=\"?perfil=evento&p=pesquisa_lider\" role=\"form\">
                                            <input type='hidden' name='oficina' value='" . $atracao['id'] . "'>
                                            <input type='hidden' name='lider' value='$idPedido'>
                                            <button type=\"submit\" name='pesquisar' class=\"btn btn-primary\"><i class='fa fa-plus'></i> Adicionar</button>
                                            </form>
                                        </td>";
                                                            echo "<td></td>";
                                                        }
                                                        echo "</tr>";
                                                    }
                                                    echo "</tbody>";
                                                    ?>
                                                </table>
                                            </div>
                                        </div>
                                        <ul class="list-inline pull-right">
                                            <li>
                                                <a class="btn btn-default prev-step"><span
                                                            aria-hidden="true">&larr;</span>
                                                    Voltar</a>
                                            </li>
                                            <li>
                                                <a class="btn btn-primary next-step">Próxima etapa <span
                                                            aria-hidden="true">&rarr;</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php
                                    //}
                                    ?>
                                    <div class="tab-pane fade" role="tabpanel" id="stepper-step-4">
                                        <h3>4. Parecer artístico</h3>
                                        <div class="container">
                                            <div class="row">
                                                <?php include "includes/label_parecer_artistico.php" ?>
                                            </div>
                                        </div>

                                        <ul class="list-inline pull-right">
                                            <li>
                                                <a class="btn btn-default prev-step"><span aria-hidden="true">&larr;</span>
                                                    Voltar
                                                </a>
                                            </li>
                                            <li>
                                                <a class="btn btn-primary next-step">Próxima etapa <span aria-hidden="true">&rarr;</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-pane fade" role="tabpanel" id="stepper-step-5">
                                        <h3>5. Valor por equipamento</h3>
                                        <?php
                                        $sqlEquipamento = "SELECT DISTINCT oco.local_id as 'local_id', local.local as 'local' 
                            FROM ocorrencias oco
                            INNER JOIN locais local ON local.id = oco.local_id 
                            WHERE oco.origem_ocorrencia_id = '$idEvento' AND local.publicado = 1 AND oco.publicado = 1";

                                        $queryEquipamento = mysqli_query($con, $sqlEquipamento);
                                        $numRowsEquipamento = mysqli_num_rows($queryEquipamento);
                                        ?>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <form method="POST" action="?perfil=evento&p=pedido_edita" name="form-valor-equipamento"
                                                      role="form">
                                                    <div class="form-group">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th width="80%">Equipamento</th>
                                                                <th>Valor</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            if ($numRowsEquipamento == 0) {
                                                                ?>
                                                                <tr>
                                                                    <td width="100%" class="text-center" colspan="2">
                                                                        Não há ocorrências cadastradas!
                                                                        <br>Por Favor, retorne em atração e cadastre.
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            } else {

                                                                while ($equipamento = mysqli_fetch_array($queryEquipamento)) {
                                                                    $idEquipamento = $equipamento['local_id'];

                                                                    $sql_valor = "SELECT * FROM valor_equipamentos WHERE pedido_id = '$idPedido' AND local_id = '$idEquipamento'";
                                                                    $queryValor = mysqli_query($con, $sql_valor);
                                                                    $arrayValorEquipamento = mysqli_fetch_array($queryValor);

                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $equipamento['local'] ?></td>
                                                                        <input type="hidden" value="<?= $equipamento['local_id'] ?>">
                                                                        <td>
                                                                            <input type="text" class="form-control" name="valorEquipamento[]"
                                                                                   value="<?= dinheiroParaBr($arrayValorEquipamento['valor']) ?>" onkeyup="somaValorEquipamento()"
                                                                                   onkeypress="return(moeda(this, '.', ',', event));">
                                                                            <input type="hidden" value="<?= $equipamento['local_id'] ?>" name="equipamentos[]">
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td width="50%">Valor Total: R$ <?= dinheiroParaBr($pedido['valor_total']) ?></td>
                                                                <td width="50%">Valor Faltante: R$ <span id="valorFaltante"></span></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="row col-md-offset-4 col-md-4">
                                                        <div class="box-footer">
                                                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                                            <input type="hidden" name="tipoPessoa" value="<?= $tipoPessoa ?>">
                                                            <input type="hidden" name="idProponente" value="<?= $idProponente ?>">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <ul class="list-inline pull-right">
                                            <li>
                                                <a class="btn btn-default prev-step"><span
                                                            aria-hidden="true">&larr;</span>
                                                    Voltar</a>
                                            </li>
                                            <li>
                                                <button type="submit" class="btn btn-primary">Finalizar</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <!-- /.pedido -->
                <!-- proponente -->

                <!-- líderes -->

                <!-- parecer -->


                <!-- VALOR POR EQUIPAMENTO (LOCAL) -->
            </div>
            <!-- /.col -->
        </div>

<!-- /.row -->
<!-- END ACCORDION & CAROUSEL-->
</section>
<!-- /.content -->
</div>
<!-- Modal -->
<div class="modal fade" id="modalParcelas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="margin-top: 15px;" class="modal-title text-bold" id="exampleModalLongTitle">Editar
                    Parcelas</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">
                <div class="row">
                    <h4 class="text-center" id="somaParcelas"><b><p id="msg"></p><b/></h4>
                </div>
                <form action="#" id="formParcela">
                </form>
                <div class="row">
                    <h4 class="text-center" id="valor_restante_text"><b>Valor restante</b> <em><p
                                    id="valor_restante"><?= isset($somaParcelas) ? "0,00" : dinheiroParaBr($pedido['valor_total']) ?></p>
                        </em>
                    </h4>
                </div>
                <div class="row">
                    <h4 class="text-center" id="somaParcelas"><b>Soma das
                            parcelas</b> <em><p
                                    id="soma"><?= isset($somaParcelas) ? dinheiroParaBr($somaParcelas) : NULL ?></p>
                        </em></h4>
                </div>
                <div class="row">
                    <h4 class="text-center"><b>Valor total do contrato</b>
                        <p id="valor_total"><em><?= dinheiroParaBr($pedido['valor_total']) ?> </em></p></h4>
                </div>
                <br>
            </div>
            <div class="modal-footer">
                <div class="botoes">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" name="salvar" id="salvarModal">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/x-handlebars-template" id="templateParcela">
    <div class='row'>
        <div class='form-group col-md-2'>
            <label for='parcela'>Parcela </label>
            <input type='number' value="{{count}}" class='form-control' disabled>
        </div>
        <div class='form-group col-md-3'>
            <label for='valor'>Valor *</label>
            <input type='text' id='valor' name='valor[{{count}}]' value="{{valor}}" required
                   placeholder="Valor em reais"
                   onkeypress="return(moeda(this, '.', ',', event));" onkeyup="somar()" class='form-control'>
        </div>
        <div class='form-group col-md-4'>
            <label for='modal_data_kit_pagamento'>Data Kit Pagamento *</label>
            <input type='date' id='modal_data_kit_pagamento' value="{{kit}}" required
                   name='modal_data_kit_pagamento[{{count}}]'
                   class='form-control'>
        </div>
    </div>
</script>
<!-- Modal Oficinas-->
<style>
    .modal-lg {
        width: 90%;
    }
</style>
<div class="modal fade" id="modalOficina" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="margin-top: 15px;" class="modal-title text-bold" id="exampleModalLongTitle">Editar
                    Parcelas de Oficina</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <h4 class="text-center" id="msg"><b><p id="msg"></p><b/></h4>
                </div>
                <form action="#" id="formParcela">
                </form>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <h4 class="text-center" id="valor_restante_text"><b>Valor restante </b> <em><p
                                            id="valor_restante"><?= isset($somaParcelas) ? "0,00" : dinheiroParaBr($pedido['valor_total']) ?> </p>
                                </em></h4>
                        </div>
                        <div class="row">
                            <h4 class="text-center" id="somaParcelas"><b>Soma das
                                    parcelas</b> <em><p
                                            id="soma"><?= isset($somaParcelas) ? dinheiroParaBr($somaParcelas) : NULL ?></p>
                                </em></h4>
                        </div>
                        <div class="row">
                            <h4 class="text-center"><b>Valor total do contrato</b>
                                <p id="valor_total"><em><?= dinheiroParaBr($pedido['valor_total']) ?> </em></p></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="botoesOficina">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" name="salvar" id="salvarModalOficina">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/x-handlebars-template" id="templateOficina">
    <div class='row'>
        <div class='form-group col-md-1'>
            <label for='parcela'>Parcela </label>
            <input type='number' value="{{count}}" class='form-control' disabled>
        </div>
        <div class='form-group col-md-2'>
            <label for='valor'>Valor </label>
            <input type='text' id='valor' name='valor[{{count}}]' value="{{valor}}" placeholder="Valor em reais"
                   onkeyup="somar()" onkeypress="return(moeda(this, '.', ',', event))" class='form-control'>
        </div>
        <div class='form-group col-md-2'>
            <label for='data_inicial'>Data Inicial</label>
            <input type='date' id='data_inicial' value="{{inicial}}" name='inicial[{{count}}]'
                   class='form-control'>
        </div>
        <div class='form-group col-md-2'>
            <label for='data_final'>Data Final</label>
            <input type='date' id='data_final' value="{{final}}" name='final[{{count}}]' class='form-control'>
        </div>
        <div class='form-group col-md-2'>
            <label for='modal_data_kit_pagamento'>Data Kit Pagamento</label>
            <input type='date' id='modal_data_kit_pagamento' value="{{kit}}"
                   name='modal_data_kit_pagamento[{{count}}]' class='form-control'>
        </div>
        <div class='form-group col-md-2'>
            <label for='horas'>Horas</label>
            <input type='number' id='horas' value="{{horas}}" name='horas[{{count}}]' class='form-control'>
        </div>
    </div>
</script>


<script type="text/javascript">

    $(function () {
        $('#numero_parcelas').on('change', ocultarBotao);

        $('#abrirParcelas').on('click', abrirModal);
        $('#editarParcelas').on('click', abrirModal);

        $('#salvarModal').on('click', salvarModal);
        $('#salvarModalOficina').on('click', salvarModal);

        $('#editarModal').on('click', editarModal);


    });

    // $('#modalParcelas').on('hide.bs.modal', function () {
    //     location.reload(true);
    // });
    //
    // $('#modalOficina').on('hide.bs.modal', function () {
    //     location.reload(true);
    // });


    function somar() {

        var oficina = parseInt("<?= isset($oficina) ? $oficina : '' ?>");

        if (oficina == 1) {

            if ($("#numero_parcelas").val() == 4) {
                //$("#numero_parcelas").val("3");
                var parcelas = $("#numero_parcelas").val() - 1;

            } else if ($("#numero_parcelas").val() == 3) {
                // $("#numero_parcelas").val("2");
                var parcelas = $("#numero_parcelas").val() - 1;

            } else {
                var parcelas = $("#numero_parcelas").val();
            }

        } else {
            var parcelas = $("#numero_parcelas").val();
        }

        var valorTotal = "<?=$pedido['valor_total']?>";
        var restante = valorTotal;

        var arrayValor = [];
        let soma = 0;

        for (var i = 1; i <= parcelas; i++) {
            arrayValor [i] = $("input[name='valor[" + i + "]']").val().replace('.', '').replace(',', '.');

            if (arrayValor[i] == "") {
                $("input[name='valor[" + i + "]']").val("0,00");
                continue;
            }
            soma += parseFloat(arrayValor[i]);
            restante -= arrayValor[i];
        }

        if (oficina == 1) {
            $('#modalOficina').find('#soma').html(soma.toFixed(2).replace('.', ','));
            $('#modalOficina').find('#valor_restante').html(restante.toFixed(2).replace('.', ','));

            if (restante != 0 || restante != '0,00') {
                $("#salvarModalOficina").attr("disabled", true);
                $("#editarModalOficina").attr("disabled", true);
                $("#modalOficina").find('#msg').html("<em class='text-danger'>O valor da soma das parcelas deve ser igual ao valor total do contrato! </em>");
            } else {
                $("#salvarModalOficina").attr("disabled", false);
                $("#editarModalOficina").attr("disabled", false);

                var nums = "<?= isset($numRows) ? $numRows : ''; ?>";

                if (nums != '') {
                    $("#modalOficina").find('#msg').html("<em class='text-success'> Agora os valores batem! Clique em editar para continuar.");
                } else {
                    $("#modalOficina").find('#msg').html("<em class='text-success'> Agora os valores batem! Clique em salvar para continuar.");
                }
            }

        } else {
            $('#modalParcelas').find('#soma').html(soma.toFixed(2).replace('.', ','));
            $('#modalParcelas').find('#valor_restante').html(restante.toFixed(2).replace('.', ','));

            if (Math.sign(restante) != 0) {
                console.log(Math.sign(restante));
                $("#salvarModal").attr("disabled", true);
                $("#editarModal").attr("disabled", true);
                $("#modalParcelas").find('#msg').html("<em class='text-danger'>O valor das parcelas somadas devem ser igual ao valor total do contrato! </em>");
            } else {
                $("#salvarModal").attr("disabled", false);
                $("#editarModal").attr("disabled", false);
                $('#modalParcelas').find('#valor_restante_text').hide();

                var nums = "<?= isset($numRows) ? $numRows : ''; ?>";

                if (nums != '') {
                    $("#modalParcelas").find('#msg').html("<em class='text-success'> Agora os valores batem! Clique em editar para continuar.");
                } else {
                    $("#modalParcelas").find('#msg').html("<em class='text-success'> Agora os valores batem! Clique em salvar para continuar.");
                }
            }
        }
    }

    var ocultarBotao = function () {

        let valorPedido = "<?=$pedido['valor_total']?>";

        var optionSelect = document.querySelector("#numero_parcelas").value;
        var editarParcelas = document.querySelector('#editarParcelas');
        var dataKit = document.querySelector("#data_kit_pagamento");
        var formPagamento = document.querySelector('#forma_pagamento')

        if ($('#numero_parcelas').val() != 13){
            $('#forma_pagamento').attr('readonly',true);
        }
        else{
            $('#forma_pagamento').attr('readonly',false);
        }

        console.log ($('#valor_total').val());


        if ($('#valor_total').val() > '0.00') {
            if (optionSelect == "1" || optionSelect == 0) {
                dataKit.required = true;
                editarParcelas.style.display = "none";
                dataKit.style.display = "block";
            } else {
                $("#data_kit_pagamento").attr("required", false);
                editarParcelas.style.display = "block";
                dataKit.style.display = "none";
            }
        } else {
            $("#numero_parcelas").attr('title', 'Grave o valor do pedido para poder editar as parcelas!');
            dataKit.style.display = 'none';
        }
    }

    var abrirModal = function () {

        var source = document.getElementById("templateParcela").innerHTML;
        var template = Handlebars.compile(source);
        var html = '';

        var parcelasSalvas = "<?= isset($numRows) ? $numRows : ''; ?>";

        var footer = document.querySelector(".main-footer");
        footer.style.display = "none";

        var StringValores = "<?= isset($StringValores) ? $StringValores : ''; ?>";

        var StringDatas = "<?= isset($StringDatas) ? $StringDatas : ''; ?>";

        var oficina = parseInt("<?= isset($oficina) ? $oficina : '' ?>");

        if (oficina == 1) {
            var sourceOficina = document.getElementById("templateOficina").innerHTML;
            var templateOficina = Handlebars.compile(sourceOficina);

            $(".botoesOficina").html("<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>" + "<button type='button' class='btn btn-primary' name='editar' id='editarModalOficina'>Editar</button>");

            // var parcelasSelected = $("#numero_parcelas").val();

            if ($("#numero_parcelas").val() == 4) {
                // $("#numero_parcelas").val("3");
                var parcelasSelected = $("#numero_parcelas").val() - 1;

            } else if ($("#numero_parcelas").val() == 3) {
                //$("#numero_parcelas").val("2");
                var parcelasSelected = $("#numero_parcelas").val() - 1;

            } else {
                var parcelasSelected = $("#numero_parcelas").val();
            }

            var StringInicio = "<?= isset($StringInicio) ? $StringInicio : '';?>";

            var StringFim = "<?= isset($StringFim) ? $StringFim : ''; ?>";

            var StringCarga = "<?= isset($StringCarga) ? $StringCarga : '';?>";

            if (StringValores != "" && StringDatas != "") {
                var valores = StringValores.split("|");
                var datas = StringDatas.split("|");
                var inicio = StringInicio.split("|");
                var fim = StringFim.split("|");
                var horas = StringCarga.split("|");

                var somando = 0;

                if (parseInt(parcelasSelected) < parseInt(parcelasSalvas)) {
                    swal("Haviam  " + parcelasSalvas + " parcelas nesse pedido!", "Número de parcelas selecionadas menor que quantidade de parcelas salvas, ao edita-lás as demais seram excluídas!", "warning");

                    for (var count = 0; count < parcelasSelected; count++) {
                        html += templateOficina({
                            count: count + 1, // para sincronizar com o array vindo do banco
                            valor: valores [count],
                            kit: datas [count],
                            inicial: inicio [count],
                            final: fim [count],
                            horas: horas [count],
                        });

                        var valor = valores[count].replace('.', '').replace(',', '.');
                        somando += parseFloat(valor);
                    }
                    var valorFaltando = 0;
                    for (var x = parcelasSelected; x < parcelasSalvas; x++) {
                        var valor = valores[x].replace('.', '').replace(',', '.');
                        valorFaltando += parseFloat(valor);
                    }


                    $('#modalOficina').find('#valor_restante').html(valorFaltando.toFixed(2).replace('.', ','));

                    if ($("#valor_restante") != 0) {
                        $("#editarModalOficina").attr("disabled", true);
                        $('#modalOficina').find('#soma').html(somando.toFixed(2).replace('.', ','));
                        $("#modalOficina").find('#msg').html("<em class='text-danger'>O valor das parcelas somadas devem ser igual ao valor total do contrato! </em>");
                    }
                } else {
                    for (var count = 0; count < parcelasSalvas; count++) {
                        html += templateOficina({
                            count: count + 1, // para sincronizar com o array vindo do banco
                            valor: valores [count],
                            kit: datas [count],
                            inicial: inicio [count],
                            final: fim [count],
                            horas: horas [count],
                        });
                    }
                }

                if (parseInt(parcelasSalvas) < parseInt(parcelasSelected)) {
                    let faltando = parcelasSelected - parcelasSalvas;
                    let count = parcelasSalvas;
                    for (var i = 1; i <= parseInt(faltando); i++) {
                        html += templateOficina({
                            count: parseInt(count) + 1,
                        });
                        count++;
                    }
                }

                $('#modalOficina').find('#formParcela').html(html);

                $('#editarModalOficina').on('click', editarModal);
                $('#modalOficina').modal('show');

            } else {
                for (var count = 1; count <= parcelasSelected; count++) {
                    html += templateOficina({
                        count: count
                    });
                }

                var footer = document.querySelector(".main-footer");
                footer.style.display = "none";

                $('#editarModalOficina').on('click', salvarModal);
                $('#modalOficina').find('#formParcela').html(html);
                $('#modalOficina').modal('show');
            }

        } else {

            var parcelasSelected = $("#numero_parcelas").val();

            if (parseInt(parcelasSelected) < parseInt(parcelasSalvas)) {
                swal("Haviam  " + parcelasSalvas + " parcelas nesse pedido!", "Número de parcelas selecionadas menor que quantidade de parcelas salvas, ao edita-lás as demais seram excluídas!", "warning");
            }

            if (StringValores != "" && StringDatas != "") {

                $(".botoes").html("<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>" + "<button type='button' class='btn btn-primary' name='editar' id='editarModal'>Editar</button>");

                var valores = StringValores.split("|");
                var datas = StringDatas.split("|");

                var somando = 0;

                console.log(valores);


                if (parseInt(parcelasSelected) < parseInt(parcelasSalvas)) {
                    for (var count = 0; count < parcelasSelected; count++) {
                        html += template({
                            count: count + 1, // para sincronizar com o array vindo do banco
                            valor: valores [count],
                            kit: datas [count],
                        });

                        var valor = valores[count].replace('.', '').replace(',', '.');
                        somando += parseFloat(valor);
                    }
                    var valorFaltando = 0;
                    for (var x = parcelasSelected; x < parcelasSalvas; x++) {
                        var valor = valores[x].replace('.', '').replace(',', '.');
                        valorFaltando += parseFloat(valor);
                    }
                    $('#modalParcelas').find('#valor_restante').html(valorFaltando.toFixed(2).replace('.', ','));

                    if ($("#valor_restante") != "0,00") {
                        $("#editarModal").attr("disabled", true);
                        $('#modalParcelas').find('#soma').html(somando.toFixed(2).replace('.', ','));
                        $("#modalParcelas").find('#msg').html("<em class='text-danger'>O valor das parcelas somadas devem ser igual ao valor total do contrato! </em>");
                    }

                } else {
                    for (var count = 0; count < parcelasSalvas; count++) {
                        html += template({
                            count: count + 1, // para sincronizar com o array vindo do banco
                            valor: valores [count],
                            kit: datas [count],
                        });
                    }
                }

                if (parseInt(parcelasSalvas) < parseInt(parcelasSelected)) {
                    var faltando = parcelasSelected - parcelasSalvas;
                    var count = parcelasSalvas;
                    for (var i = 1; i <= parseInt(faltando); i++) {
                        html += template({
                            count: parseInt(count) + 1,
                        });
                        count++;
                    }
                }

                $('#modalParcelas').find('#formParcela').html(html);

                $('#editarModal').on('click', editarModal);
                $('#modalParcelas').modal('show');


            } else {

                $(".botoes").html("<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>" + "<button type='button' class='btn btn-primary' name='salvar' id='salvarModal'>Salvar</button>");

                for (var count = 1; count <= parcelasSelected; count++) {
                    html += template({
                        count: count
                    });
                }
                $('#salvarModal').on('click', salvarModal);
                $('#modalParcelas').find('#formParcela').html(html);
                $('#modalParcelas').modal('show');
            }
        }
    };


    var salvarModal = function () {
        var oficina = "<?= isset($oficina) ? $oficina : '' ?>";

        var count = 0;
        $("#formParcela input").each(function () {
            if ($(this).val() == "" || $(this).val() == "0,00") {
                count++;
            }
        });

        if (count != 0) {
            swal("Preencha todas as informações para editar as parcelas!", "", "warning");
        } else {

            if (oficina == 1) {
                if ($("#numero_parcelas").val() == 4) {
                    // $("#numero_parcelas").val("3");
                    var parcelas = $("#numero_parcelas").val() - 1;

                } else if ($("#numero_parcelas").val() == 3) {
                    //$("#numero_parcelas").val("2");
                    var parcelas = $("#numero_parcelas").val() - 1;

                } else {
                    var parcelas = $("#numero_parcelas").val();
                }

                var arrayKit = [];
                var arrayValor = [];
                var arrayInicial = [];
                var arrayFinal = [];
                var horas = [];

                for (var i = 1; i <= parcelas; i++) {
                    arrayKit [i] = $("input[name='modal_data_kit_pagamento[" + i + "]']").val();
                    arrayValor [i] = $("input[name='valor[" + i + "]']").val();
                    arrayInicial [i] = $("input[name='inicial[" + i + "]']").val();
                    arrayFinal[i] = $("input[name='final[" + i + "]']").val();
                    horas[i] = $("input[name='horas[" + i + "]']").val();
                }

                $('#modalOficina').slideUp();

                $.post('?perfil=evento&p=parcelas_cadastro', {
                    parcelas: parcelas,
                    arrayValor: arrayValor,
                    arrayKit: arrayKit,
                    arrayInicial: arrayInicial,
                    arrayFinal: arrayFinal,
                    horas: horas
                })
                    .done(function () {
                        var sourceOficina = document.getElementById("templateOficina").innerHTML;
                        var templateOficina = Handlebars.compile(sourceOficina);
                        var html = '';

                        for (var count = 0; count < parcelas; count++) {
                            html += templateOficina({
                                count: count + 1, // para sincronizar com o array vindo do banco
                                valor: arrayValor [count],
                                kit: arrayKit [count],
                                inicial: arrayInicial [count],
                                final: arrayFinal [count],
                                horas: horas [count],
                            });
                        }
                        swal("" + parcelas + " parcelas gravadas com sucesso!", "", "success")
                            .then(() => {
                                // location.reload(true);
                                // $('#modalOficina').slideDown('slow');
                            });
                    })
                    .fail(function () {
                        swal("danger", "Erro ao gravar");
                    });

            } else {

                var parcelas = $("#numero_parcelas").val();
                var arrayKit = [];
                var arrayValor = [];

                for (var i = 1; i <= parcelas; i++) {
                    arrayKit [i] = $("input[name='modal_data_kit_pagamento[" + i + "]']").val();
                    arrayValor [i] = $("input[name='valor[" + i + "]']").val();
                }

                var newButtons = "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>" + "<button type='button' class='btn btn-primary' name='editar' id='editarModal'>Editar</button>";

                $('#modalParcelas').slideUp();

                $.post('?perfil=evento&p=parcelas_cadastro', {
                    parcelas: parcelas,
                    arrayValor: arrayValor,
                    arrayKit: arrayKit
                })
                    .done(function () {
                        var source = document.getElementById("templateParcela").innerHTML;
                        var template = Handlebars.compile(source);
                        var html = '';

                        for (var count = 0; count < parcelas; count++) {
                            html += template({
                                count: count + 1, // para sincronizar com o array vindo do banco
                                valor: arrayValor[count],
                                kit: arrayKit[count],
                            });
                        }

                        $(".botoes").html(newButtons);
                        $('#editarModal').on('click', editarModal);

                        swal("" + parcelas + " parcelas gravadas com sucesso!", "", "success")
                            .then(() => {
                                $('#modalParcelas').slideDown('slow');
                                //window.location.href = "?perfil=evento&p=parcelas_cadastro";
                            });
                    })
                    .fail(function () {
                        swal("danger", "Erro ao gravar");
                    });
            }
        }
    };

    var editarModal = function () {

        var count = 0;
        $("#formParcela input").each(function () {
            if ($(this).val() == "" || $(this).val() == "0,00") {
                count++;
            }
        });

        if (count != 0) {
            swal("Preencha todas as parcelas para edita-lás!", "", "warning");

        } else {
            var oficina = "<?= isset($oficina) ? $oficina : ''?>";

            if (oficina == 1) {
                if ($("#numero_parcelas").val() == 4) {
                    // $("#numero_parcelas").val("3");
                    var parcelas = $("#numero_parcelas").val() - 1;

                } else if ($("#numero_parcelas").val() == 3) {
                    //$("#numero_parcelas").val("2");
                    var parcelas = $("#numero_parcelas").val() - 1;

                } else {
                    var parcelas = $("#numero_parcelas").val();
                }
                var arrayKit = [];
                var arrayValor = [];
                var arrayInicial = [];
                var arrayFinal = [];
                var horas = [];

                for (var i = 1; i <= parcelas; i++) {
                    arrayKit [i] = $("input[name='modal_data_kit_pagamento[" + i + "]']").val();
                    arrayValor [i] = $("input[name='valor[" + i + "]']").val();
                    arrayInicial [i] = $("input[name='inicial[" + i + "]']").val();
                    arrayFinal[i] = $("input[name='final[" + i + "]']").val();
                    horas[i] = $("input[name='horas[" + i + "]']").val();
                }

                var sourceOficina = document.getElementById("templateOficina").innerHTML;
                var templateOficina = Handlebars.compile(sourceOficina);
                var html = '';

                var newButtons = "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>" + "<button type='button' class='btn btn-primary' name='editar' id='editarModalOficina'>Editar</button>";

                $('#modalOficina').slideUp();

                $.post('?perfil=evento&p=parcelas_edita', {
                    parcelas: parcelas,
                    valores: arrayValor,
                    datas: arrayKit,
                    arrayInicial: arrayInicial,
                    arrayFinal: arrayFinal,
                    horas: horas
                })
                    .done(function () {
                        for (var count = 0; count < parcelas; count++) {
                            html += templateOficina({
                                count: count + 1, // para sincronizar com o array vindo do banco
                                valor: arrayValor [count],
                                kit: arrayKit [count],
                                inicial: arrayInicial [count],
                                final: arrayFinal [count],
                                horas: horas [count],
                            });
                        }

                        $(".botoes").html(newButtons);
                        $('#editarModalOficina').on('click', editarModal);

                        swal("" + parcelas + " parcelas gravadas com sucesso!", "", "success")
                            .then(() => {
                                //location.reload(true);
                                $('#modalOficina').slideDown("slow");
                            });


                    })
                    .fail(function () {
                        swal("danger", "Erro ao gravar");
                    });

            } else {

                var parcelas = $("#numero_parcelas").val();

                var datas = new Array(1);
                var valores = new Array(1);

                for (var i = 1; i <= parcelas; i++) {
                    $("input[name='modal_data_kit_pagamento[" + i + "]']").each(function () {
                        datas.push($(this).val());
                    });

                    $("input[name='valor[" + i + "]']").each(function () {
                        valores.push($(this).val());
                    });
                }

                $('#modalParcelas').slideUp();

                $.ajax({
                    url: "?perfil=evento&p=parcelas_edita",
                    method: "POST",
                    data: {
                        parcelas: parcelas,
                        valores: valores,
                        datas: datas
                    },
                })
                    .done(function () {
                        var source = document.getElementById("templateParcela").innerHTML;
                        var template = Handlebars.compile(source);
                        var html = '';

                        for (var count = 0; count < parcelas; count++) {
                            html += template({
                                count: count + 1, // para sincronizar com o array vindo do banco
                                valor: valores[count],
                                kit: datas[count],
                            });
                        }

                        $('#forma_pagamento').val() == '';
                        for(let conta = 1; conta<= parcelas; conta ++){
                            $('#forma_pagamento').append(conta+'° parcela R$ '+valores[conta]+ '\n')
                        }
                        swal("" + parcelas + " parcelas editadas com sucesso!", "", "success")
                            .then(() => {
                                //location.reload(true);
                                $('#modalParcelas').slideDown("slow");
                            });


                    })
                    .fail(function () {
                        swal("danger", "Erro ao gravar");
                    });
            }
        }
    };

</script>
<script>
    $(document).ready(somaValorEquipamento());

    function somaValorEquipamento() {
        let valorEquipamento = $("input[name='valorEquipamento[]']");
        let valor_total = 0;

        for (let i = 0; i < valorEquipamento.length; i++) {
            if (valorEquipamento[i].value == "") {
                valorEquipamento[i].value = "0,00"
            }

            let valor = parseFloat(valorEquipamento[i].value.replace('.', '').replace(',', '.'));
            console.log(valor);

            valor_total += valor;
        }

        console.log(valor_total);

        let valorTotal = parseFloat($('#valor_total').val().replace('.', '').replace(',', '.'));
        let valorDif;

        if (valor_total != valorTotal) {
            valorDif = valorTotal - valor_total;
        } else {
            valorDif = 0;
        }

        valorDif = parseFloat(valorDif.toFixed(2));

        if (valorDif < 0) {
            // VALOR DIGITADO MAIOR QUE O VALOR TOTAL DO EVENTO
            $('#valorFaltante').html("<span style='color: red'>VALOR MAIOR QUE VALOR TOTAL</span>");
            $('#gravarValorEquipamento').attr("disabled", true);
        } else if (valorDif == 0) {
            // VALOR DOS EQUIPAMENTOS IGUAL O DO VALOR TOTAL DO EVENTO
            $('#valorFaltante').html("<span style='color: green'> VALOR OK </span>");
            $('#gravarValorEquipamento').attr("disabled", false);
        } else {
            //  VALOR DIGITADO MENOR QUE O VALOR TOTAL DO EVENTO
            $('#valorFaltante').html(valorDif);
            $('#gravarValorEquipamento').attr("disabled", true);
        }
    }
</script>