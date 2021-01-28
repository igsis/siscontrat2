<?php
include "includes/menu_interno.php";

$idPedido = $_SESSION['idPedido'];
$idEvento = $_SESSION['idEvento'];

$invalido = false;

if (isset($_GET['valorTotal']) && $_GET['valorTotal'] != '0,00') {
    $query = "UPDATE `pedidos` SET valor_total = " . dinheiroDeBr($_GET['valorTotal']) . " WHERE id = {$idPedido}";
    $con->query($query);
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

if (isset($oficina)) {
    switch ($_GET['nParcelas']) {
        case 3:
            $nParcelas = 2;
            break;

        case 4:
            $nParcelas = 3;
            break;

        default:
            $nParcelas = $_GET['nParcelas'];
            break;
    }
} else {
    $nParcelas = $_GET['nParcelas'] ?? NULL;
}

$pedido = recuperaDados("pedidos", "id", $idPedido);

$sqlParcelas = "SELECT p.id, p.valor, p.data_pagamento, pc.data_inicio, pc.data_fim, pc.carga_horaria 
                FROM parcelas AS p
                LEFT JOIN parcela_complementos pc on p.id = pc.parcela_id
                WHERE pedido_id = '$idPedido' AND p.publicado = 1";

$queryParcelas = $con->query($sqlParcelas);

if ($queryParcelas->num_rows) {
    $parcelas = $queryParcelas->fetch_all(MYSQLI_ASSOC);
}

if ($pedido['valor_total'] == '0.00') {
    $mensagem = mensagem('danger', 'Valor deve ser maior que 0,00 para cadastrar parcelas.');
    $invalido = true;
}

$sqlData = "SELECT data_inicio FROM ocorrencias WHERE origem_ocorrencia_id = {$idEvento} AND publicado = 1 ORDER BY data_inicio ASC LIMIT 1 ";
$dataInicio = $con->query($sqlData)->fetch_all(MYSQLI_ASSOC);


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
                <form action="?perfil=evento&p=pedido_parcelas" method="POST" role="form">
                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                    <input type="hidden" name="nParcelas" value="<?= $nParcelas ?>">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Parcelas</h3>
                        </div>

                        <div class="row" align="center">
                            <?= $mensagem ?? "" ?>
                            <div id="linha-erro" class="col-md-12">
                                <div class="box box-danger box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Data de pagamento deve ser após a data de ocorrência!</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                                        class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <?php for ($i = 0; $i < $nParcelas; $i++):
                                // if (isset($oficina)): ?>
                                    <input type="hidden" name="oficina" value="1">
                                    <div class='row'>
                                        <div class='form-group col-md-1'>
                                            <label for='parcela'>Parcela </label>
                                            <input type='number' name="parcela[<?= $i ?>]" value="<?= $i + 1 ?>"
                                                   class='form-control' readonly>
                                        </div>
                                        <div class='form-group col-md-2'>
                                            <label for='valor'>Valor *</label>
                                            <input type='text' name='valor[<?= $i ?>]' class='form-control valor'
                                                   value="<?= isset($parcelas[$i]['valor']) ? dinheiroParaBr($parcelas[$i]['valor']) : '' ?>"
                                                   required
                                                   placeholder="Valor em reais" maxlength="10">
                                        </div>
                                        <div class='form-group col-md-2'>
                                            <label for='data_inicial'>Data Inicial</label>
                                            <input type='date' id='data_inicial'
                                                   value="<?= $parcelas[$i]['data_inicio'] ?? '' ?>"
                                                   name='data_inicial[<?= $i ?>]'
                                                   class='form-control'>
                                        </div>
                                        <div class='form-group col-md-2'>
                                            <label for='data_final'>Data Final</label>
                                            <input type='date' id='data_final'
                                                   value="<?= $parcelas[$i]['data_fim'] ?? '' ?>"
                                                   name='data_final[<?= $i ?>]'
                                                   class='form-control'>
                                        </div>
                                        <div class='form-group col-md-2'>
                                            <label for='data_pagamento'>Data Kit Pagamento *</label>
                                            <input type='date' id='data_pagamento' required
                                                   value="<?= $parcelas[$i]['data_pagamento'] ?? '' ?>"
                                                   name='data_pagamento[<?= $i ?>]' class='form-control'>
                                        </div>
                                        <div class='form-group col-md-2'>
                                            <label for='carga_horaria'>Carga Horária</label>
                                            <input type='number' id='carga_horaria' min="0"
                                                   value="<?= $parcelas[$i]['carga_horaria'] ?? '' ?>"
                                                   name='carga_horaria[<?= $i ?>]'
                                                   class='form-control'>
                                        </div>
                                    </div>
                                <?php //else: ?>
<!--                                    <div class='row'>-->
<!--                                        <div class='form-group col-md-2'>-->
<!--                                            <label for='parcela'>Parcela </label>-->
<!--                                            <input type='number' name="parcela[--><?//= $i ?><!--]" value="--><?//= $i + 1 ?><!--"-->
<!--                                                   class='form-control' readonly>-->
<!--                                        </div>-->
<!--                                        <div class='form-group col-md-3'>-->
<!--                                            <label for='valor'>Valor *</label>-->
<!--                                            <input type='text' name='valor[--><?//= $i ?><!--]' class='form-control valor'-->
<!--                                                   value="--><?//= isset($parcelas[$i]['valor']) ? dinheiroParaBr($parcelas[$i]['valor']) : '' ?><!--"-->
<!--                                                   required-->
<!--                                                   placeholder="Valor em reais"-->
<!--                                                   maxlength="10">-->
<!--                                        </div>-->
<!--                                        <div class='form-group col-md-4'>-->
<!--                                            <label for='data_pagamento'>Data Kit Pagamento *</label>-->
<!--                                            <input type='date' id='data_pagamento' required-->
<!--                                                   value="--><?//= $parcelas[$i]['data_pagamento'] ?? '' ?><!--"-->
<!--                                                   name='data_pagamento[--><?//= $i ?><!--]' class='form-control dataPagamento'>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                <?php //endif;
                            endfor; ?>
                            <div class="row">
                                <div class="col-md-offset-3 col-md-3">
                                    <div class="alert">
                                        <strong>Valor Total:</strong> R$ <span
                                                id="valorTotal" data-id="<?=$pedido['valor_total']?>"><?= $pedido['valor_total'] ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="alert">
                                        <strong>Valor Registrado:</strong> R$ <span id="totalRegistrado"
                                                                                    class="moedasBR">0.00</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-offset-5 col-md-3 has-feedback">
                                    <strong class="text-success" id="alertValor">Valor OK</strong>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <a href="?perfil=evento&p=pedido_parcelas" class="pull-left btn btn-default">Voltar</a>
                            <?php if (!$invalido): ?>
                                <input type="submit"
                                       name="<?= !isset($parcelas) ? 'gravarParcelas' : 'editarParcelas' ?>"
                                       class="pull-right btn btn-primary" value="Gravar" id="gravaParcelas">
                            <?php endif; ?>
                        </div>
                        <!-- /.box-footer-->
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

    moedaBR("#valorTotal");
    moedaBR("#totalRegistrado");

    $('#alertValor').css("display", "none");

    let linha_erro = $('#linha-erro');
    var btnGravar = $('#gravaParcelas');

    linha_erro.css("display", "none");

    $(document).on("keyup", ".valor", function () {
        var total = parseFloat($('#valorTotal').attr('data-id'));
        var sum = 0;
        let alerta = $('#alertValor');

        $(".valor").each(function () {
            let valor = $(this).val().replace('.', '').replace(',', '.');
            sum += +valor;
        });

        //let valOriginal = document.querySelector('#valorTotal').textContent;
        //let valRegistrado = document.querySelector('#totalRegistrado').textContent;
        $("#totalRegistrado").text(sum.toFixed(2));
        //console.log("TOTAL: " + total);
        if (sum == total) {
            alerta.css("display", "block");
            btnGravar.attr('disabled', false);
        } else {
            alerta.css("display", "none");
            btnGravar.attr('disabled', true);
        }
        moedaBR("#totalRegistrado");
    });

    let dataInicio = new Date("<?= $dataInicio[0]['data_inicio'] ?>");

    $(".dataPagamento").change(function (e) {
        let dataParcela = new Date(this.value);
        if (dataInicio >= dataParcela) {
            linha_erro.css("display", "block");
            btnGravar.attr('disabled', true);
        } else {
            linha_erro.css("display", "none");
            btnGravar.attr('disabled', false);
        }
    });

    function moedaBR(elemento) {
        let valor = document.querySelector(elemento);
        let x = parseFloat(valor.textContent).toLocaleString('pt-br', {minimumFractionDigits: 2});
        valor.textContent = x;
    }

    $(document).ready(function () {
        $('.valor').mask('000.000,00',{reverse: true})
    })
</script>