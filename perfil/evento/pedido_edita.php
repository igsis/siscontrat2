<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$idEvento = $_SESSION['idEvento'];

if (isset($_POST['carregar'])) {
    $_SESSION['idPedido'] = $_POST['idPedido'];
}


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

if (isset($_POST['trocaPf'])) {
    $_SESSION['idPedido'] = $_POST['idPedido'];
    $idPedido = $_SESSION['idPedido'];
    $idPessoa = $_POST['idPf'] ?? $_POST['idPessoa'];
    $trocaPf = $con->query("UPDATE pedidos SET pessoa_fisica_id = $idPessoa WHERE id = $idPedido AND origem_tipo_id = 1");
    if ($trocaPf) {
        $deletaPj = $con->query("UPDATE pedidos SET pessoa_juridica_id = null, pessoa_tipo_id = 1 WHERE id = $idPedido AND origem_tipo_id = 1");
        $mensagem = mensagem('success', 'Proponente trocado com sucesso!');
    } else {
        $mensagem = mensagem('danger', 'Erro ao trocar proponente! Tente novamente.');
    }
    $pedido = recuperaDados("pedidos", "id", $idPedido);
}

if (isset($_POST['trocaPj'])) {
    $_SESSION['idPedido'] = $_POST['idPedido'];
    $idPedido = $_SESSION['idPedido'];
    $idPessoa = $_POST['idPj'] ?? $_POST['idPessoa'];
    $trocaPj = $con->query("UPDATE pedidos SET pessoa_juridica_id = $idPessoa WHERE id = $idPedido AND origem_tipo_id = 1");
    if ($trocaPj) {
        $deletaPf = $con->query("UPDATE pedidos SET pessoa_fisica_id = null, pessoa_tipo_id = 2 WHERE id = $idPedido AND origem_tipo_id = 1");
        $mensagem = mensagem('success', 'Proponente trocado com sucesso!');
    } else {
        $mensagem = mensagem('danger', 'Erro ao trocar proponente! Tente novamente.');
    }
    $pedido = recuperaDados("pedidos", "id", $idPedido);
}

if (isset($_SESSION['idPedido']) && (isset($_POST['cadastra'])) || isset($_GET['lider'])) {
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
                                  VALUES (1, $idEvento, $tipoPessoa, $idPessoa, $valorTotal, 1)";
        if (mysqli_query($con, $sqlFirst)) {
            $_SESSION['idPedido'] = recuperaUltimo("pedidos");
            $idPedido = $_SESSION['idPedido'];
            $sqlContratado = "INSERT INTO contratos (pedido_id) VALUES ('$idPedido')";
            $queryContratado = mysqli_query($con, $sqlContratado);
        } else {
            echo $sqlFirst;
        }
    }

}

if (isset($_POST['edita'])) {
    $verba_id = $_POST['verba_id'];
    $valor_total = dinheiroDeBr($_POST['valor_total']);
    $forma_pagamento = trim(addslashes($_POST['forma_pagamento']));
    $justificativa = trim(addslashes($_POST['justificativa']));
    $observacao = trim(addslashes($_POST['observacao'])) ?? NULL;
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

$sqlOficina = "SELECT a.valor_individual, aa.acao_id FROM eventos e
                INNER JOIN atracoes a on e.id = a.evento_id
                INNER JOIN acao_atracao aa on a.id = aa.atracao_id
                WHERE e.id = '$idEvento' and a.publicado = 1";
$queryOficina = mysqli_query($con, $sqlOficina);

while ($atracoes = mysqli_fetch_array($queryOficina)) {
    $valores [] = $atracoes['valor_individual'];
    if ($atracoes['acao_id'] == 8) {
        $oficina = 1;
    }
}

$evento = recuperaDados('eventos', 'id', $idEvento);
$tipoEvento = $evento['tipo_evento_id'];

if (isset($valores) && $valores > 0) {
    $valorTotal = 0;
    foreach ($valores as $valor) {
        $valorTotal += $valor;
    }
} else {
    $valorTotal = 0;
}

if ($pedido['origem_tipo_id'] != 2 && isset($valorTotal) && $tipoEvento != 2) {
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
                                <?php if ($tipoPessoa == 2) { ?>
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
                                           aria-controls="stepper-step-5" role="tab" title="Anexos do pedido">
                                            <span class="round-tab">5</span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a class="persistant-disabled" href="#stepper-step-6" data-toggle="tab"
                                           aria-controls="stepper-step-6" role="tab" title="Valor por equipamento">
                                            <span class="round-tab">5</span>
                                        </a>
                                    </li>
                                    <?php
                                } else {
                                    ?>
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
                                           aria-controls="stepper-step-3" role="tab" title="Parecer artístico">
                                            <span class="round-tab">3</span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a class="persistant-disabled" href="#stepper-step-4" data-toggle="tab"
                                           aria-controls="stepper-step-4" role="tab" title="Anexos do pedido">
                                            <span class="round-tab">4</span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a class="persistant-disabled" href="#stepper-step-5" data-toggle="tab"
                                           aria-controls="stepper-step-5" role="tab" title="Valor por equipamento">
                                            <span class="round-tab">4</span>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <div class="tab-content">
                                <!-- Detalhes de Parcelas -->
                                <div class="tab-pane fade in active" role="tabpanel" id="stepper-step-1">
                                    <?php include "includes/label_pedido_parcelas.php" ?>
                                </div>

                                <!-- Cadastro de Proponente -->
                                <div class="tab-pane fade" role="tabpanel" id="stepper-step-2">
                                    <?php include "includes/label_pedido_proponente.php" ?>
                                </div>
                                <?php if ($tipoPessoa == 2){?>
                                <!-- Líderes -->
                                <div class="tab-pane fade" role="tabpanel" id="stepper-step-3">
                                    <?php include "includes/label_pedido_lideres.php" ?>
                                </div>
                                <?php } ?>
                                <!-- Parecer Artístico -->
                                <div class="tab-pane fade" role="tabpanel" id="stepper-step-<?= $tipoPessoa == 2 ? '4' : '3'?>">
                                    <?php
                                    if ($tipoPessoa == 2){
                                        $par = 4;
                                    } else{
                                        $par = 3;
                                    }
                                    ?>
                                    <h3><?= $par ?>. Parecer artístico</h3>
                                    <div class="container">
                                        <div class="row">
                                            <?php include "includes/label_pedido_parecer_artistico.php" ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Anexos do pedido -->
                                <div class="tab-pane fade" role="tabpanel" id="stepper-step-<?= $tipoPessoa == 2 ? '5' : '4'?>">
                                    <?php
                                    if ($tipoPessoa == 2){
                                        $i_a = 5;
                                    } else{
                                        $i_a = 4;
                                    }
                                    ?>
                                    <h3><?= $i_a ?>. Anexos do pedido</h3>
                                    <div class="container">
                                        <div class="row">
                                            <?php include "includes/label_pedido_anexos.php" ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Valor por Equipamento -->
                                <div class="tab-pane fade" role="tabpanel" id="stepper-step-<?= $tipoPessoa == 2 ? '6' : '5'?>">
                                    <?php include_once "includes/label_pedido_valor_equipamento.php" ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.col -->
        </div>

        <!-- /.row -->
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

        <?php


        if ($data_kit == null && $data_kit2 == 0){
        ?>
        $('.next-step').prop('disabled', true);
        $('#mensagem-alerta').append('<div class="alert alert-danger col-md-12" role="alert">Crie uma ocorrência antes de prosseguir com pedido.</div>');

        <?php
        }else{
        ?>
        $('#dataKit').val("<?= $data_kit ?>");
        <?php

        }
        ?>
    });

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

        var valorTotal = "<?= $pedido['valor_total'] ?>";
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

        let valorPedido = "<?= dinheiroParaBr($pedido['valor_total'])?>";

        var optionSelect = document.querySelector("#numero_parcelas").value;
        var editarParcelas = document.querySelector('#editarParcelas');
        var dataKit = document.querySelector("#data_kit_pagamento");
        var formPagamento = document.querySelector('#forma_pagamento');

        if ($('#numero_parcelas').val() != 13) {
            $('#forma_pagamento').attr('readonly', true);
            $('#forma_pagamento').val('');
            //$('#forma_pagamento').val('O pagamento se dará no 20º (vigésimo) dia após a data de entrega de toda documentação correta relativa ao pagamento.');
        } else {
            $('#forma_pagamento').attr('readonly', false);
        }

        if ($('#numero_parcelas').val() == 1) {
            $('#editarParcelas').hide();
            $('#forma_pagamento').val('O pagamento se dará no 20º (vigésimo) dia após a data de entrega de toda documentação correta relativa ao pagamento.');
        }

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
                        for (let conta = 1; conta <= parcelas; conta++) {
                            let data = datas[conta].split('-');
                            $('#forma_pagamento').append(conta + '° parcela R$ ' + valores[conta] + '. Entrega de documentos a partir de ' + data[2] + '/' + data[1] + '/' + data[0] + '.\n')
                        }
                        $('#forma_pagamento').append('\nO pagamento de cada parcela se dará no 20º (vigésimo) dia após a data de entrega de toda documentação correta relativa ao pagamento.');
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

            valor_total += valor;
        }


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

    //Faz a gravação dos dados no banco via ajax
    $('.formulario-ajax').submit(function (e) {
        e.preventDefault();

        var form = $(this);

        var action = form.attr('action');
        var method = form.attr('method');
        var etapa = form.attr('data-etapa');

        var formdata = new FormData(this);

        $.ajax({
            type: method,
            url: action,
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                return xhr;
            },
            success: function (data) {
                if (data) {
                    toastr.success('Dados da etapa <strong>' + etapa + '</strong> salvos com sucesso')
                } else {
                    toastr.error('Falha ao Gravar os Dados')
                }
            },
            error: function () {
                toastr.error('Falha ao Gravar os Dados')
            }
        })
    });

    function mostrarResultado(box,num_max,campospan){
        var contagem_carac = box.length;
        if (contagem_carac != 0){
            document.getElementById(campospan).innerHTML = contagem_carac + " caracteres digitados";
            if (contagem_carac == 1){
                document.getElementById(campospan).innerHTML = contagem_carac + " caracter digitado";
            }
            if (contagem_carac < num_max){
                document.getElementById(campospan).innerHTML = "<font color='red'>Você não inseriu a quantidade mínima de caracteres!</font>";
            }
        }else{
            document.getElementById(campospan).innerHTML = "Ainda não temos nada digitado...";
        }
    }
    function contarCaracteres(box,valor,campospan){
        var conta = valor - box.length;
        document.getElementById(campospan).innerHTML = "Faltam " + conta + " caracteres";
        if(box.length >= valor){
            document.getElementById(campospan).innerHTML = "Quantidade mínima de caracteres atingida!";
        }
    }
    function mostrarResultado3(box,num_max,campospan){
        var contagem_carac = box.length;
        if (contagem_carac != 0){
            document.getElementById(campospan).innerHTML = contagem_carac + " caracteres digitados";
            if (contagem_carac == 1){
                document.getElementById(campospan).innerHTML = contagem_carac + " caracter digitado";
            }
            if (contagem_carac < num_max){
                document.getElementById(campospan).innerHTML = "<font color='red'>Você não inseriu a quantidade mínima de caracteres!</font>";
            }
        }else{
            document.getElementById(campospan).innerHTML = "Ainda não temos nada digitado...";
        }
    }
    function contarCaracteres3(box,valor,campospan){
        var conta = valor - box.length;
        document.getElementById(campospan).innerHTML = "Faltam " + conta + " caracteres";
        if(box.length >= valor){
            document.getElementById(campospan).innerHTML = "Quantidade mínima de caracteres atingida!";
        }
    }
</script>