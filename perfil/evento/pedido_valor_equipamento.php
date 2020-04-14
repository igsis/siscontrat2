<?php
include "includes/menu_interno.php";

$idPedido = $_SESSION['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
if ($pedido['pessoa_tipo_id'] == 1) {
    $tipoPessoa = 1;
    $idProponente = $pedido['pessoa_fisica_id'];
} elseif ($pedido['pessoa_tipo_id'] == 2) {
    $tipoPessoa = 2;
    $idProponente = $pedido['pessoa_juridica_id'];
}

$sqlEquipamento = "SELECT DISTINCT oco.local_id as 'local_id', local.local as 'local' 
                    FROM ocorrencias AS oco
                    INNER JOIN locais local ON local.id = oco.local_id 
                    WHERE oco.origem_ocorrencia_id = '$idEvento' AND local.publicado = 1 AND oco.publicado = 1";

$queryEquipamento = mysqli_query($con, $sqlEquipamento);
$numRowsEquipamento = mysqli_num_rows($queryEquipamento);

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Pedido de Contração</h2>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <form class="formulario-ajax" method="POST" action="?perfil=evento&p=pedido_valor_equipamento" role="form">
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
                                                           value="<?= isset($arrayValorEquipamento['valor']) ? dinheiroParaBr($arrayValorEquipamento['valor']) : "" ?>"
                                                           onkeyup="somaValorEquipamento()"
                                                           onkeypress="return(moeda(this, '.', ',', event));">
                                                    <input type="hidden" value="<?= $equipamento['local_id'] ?>"
                                                           name="equipamentos[]">
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td width="50%">Valor Total:
                                            R$ <?= dinheiroParaBr($pedido['valor_total']) ?></td>
                                        <td width="50%">Valor Faltante: <span id="valorFaltante"></span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="pull-right btn btn-primary next-step" id="next">Gravar</button>
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
            valorDif = valorDif.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
            $('#valorFaltante').html(valorDif);
            $('#gravarValorEquipamento').attr("disabled", true);
        }
    }
</script>