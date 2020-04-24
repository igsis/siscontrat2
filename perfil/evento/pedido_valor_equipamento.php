<?php
$con = bancoMysqli();
include "includes/menu_interno.php";
$idPedido = $_SESSION['idPedido'];

if (isset($_POST['gravar'])) {
    $valoresEquipamentos = $_POST['valorEquipamento'];
    $equipamentos = $_POST['equipamentos'];
    $idPedido = $_POST['idPedido'];

    $con->query("DELETE FROM valor_equipamentos WHERE pedido_id = '$idPedido'");

    for ($i = 0; $i < count($valoresEquipamentos); $i++) {
        $valor = dinheiroDeBr($valoresEquipamentos[$i]);
        $idLocal = $equipamentos[$i];

        $sql_insert_valor = "INSERT INTO valor_equipamentos (local_id, pedido_id, valor) 
                     VALUES ('$idLocal', '$idPedido', '$valor')";

        if ($con->query($sql_insert_valor)) {
            $erro[] = false;
        } else {
            $erro[] = true;
        }
    }

    if (in_array(true, $erro, true)) {
        $mensagem = mensagem('danger', 'Erro ao gravar os dados. Tente novamente.');
    } else {
        $mensagem = mensagem('success', 'Valores gravados com sucesso.');
    }
}

$pedido = $con->query("SELECT valor_total FROM pedidos WHERE id = '$idPedido'")->fetch_assoc();

$sqlEquipamento = "SELECT DISTINCT oco.local_id as 'local_id', l.local as 'local' 
                    FROM ocorrencias AS oco
                    INNER JOIN locais AS l ON l.id = oco.local_id 
                    WHERE oco.origem_ocorrencia_id = '$idEvento' AND l.publicado = 1 AND oco.publicado = 1";

$queryEquipamento = $con->query($sqlEquipamento);
$numRowsEquipamento = $queryEquipamento->num_rows;

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
                <form method="POST" action="?perfil=evento&p=pedido_valor_equipamento" role="form">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Valor por Equipamento</h3>
                        </div>

                        <div class="row" align="center">
                            <?= $mensagem ?? "" ?>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th width="80%">Equipamento</th>
                                        <th>Valor</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($numRowsEquipamento == 0) { ?>
                                        <tr>
                                            <td width="100%" class="text-center" colspan="2">
                                                Não há ocorrências cadastradas!
                                                <br>Por Favor, retorne em atração e cadastre.
                                            </td>
                                        </tr>
                                    <?php
                                    } else {
                                        while ($equipamento = mysqli_fetch_assoc($queryEquipamento)) {
                                            $idEquipamento = $equipamento['local_id'];

                                            $sql_valor = "SELECT * FROM valor_equipamentos WHERE pedido_id = '$idPedido' AND local_id = '$idEquipamento'";
                                            $queryValor = $con->query($sql_valor);
                                            $arrayValorEquipamento = $queryValor->fetch_assoc();

                                            ?>
                                            <tr>
                                                <td><?= $equipamento['local'] ?></td>
                                                <td>
                                                    <input type="hidden" value="<?= $equipamento['local_id'] ?>"
                                                           name="equipamentos[]">
                                                    <input type="text" class="form-control" name="valorEquipamento[]"
                                                           value="<?= isset($arrayValorEquipamento['valor']) ? dinheiroParaBr($arrayValorEquipamento['valor']) : "" ?>"
                                                           onkeyup="somaValorEquipamento()"
                                                           onkeypress="return(moeda(this, '.', ',', event));">
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td width="50%">
                                            Valor Total: R$ <span id="valor_total"><?= dinheiroParaBr($pedido['valor_total']) ?></span>
                                        </td>
                                        <td width="50%">Valor Faltante: <span id="valorFaltante"></span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <input class="pull-right btn btn-primary" type="submit" name="gravar"
                                   id="gravarValorEquipamento" value="Gravar">
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


        let valorTotal = parseFloat($('#valor_total').text().replace('.', '').replace(',', '.'));
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
            valorDif = valorDif.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
            $('#valorFaltante').html(valorDif);
            $('#gravarValorEquipamento').attr("disabled", true);
        }
    }

    $(document).ready(somaValorEquipamento());
</script>