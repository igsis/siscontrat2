<h3><?= ($tipoPessoa == 2 && $tipoEvento == 1) ? "6" : "5" ?>. Valor por equipamento</h3>
<?php
$sqlEquipamento = "SELECT DISTINCT oco.local_id as 'local_id', local.local as 'local' 
                    FROM ocorrencias AS oco
                    INNER JOIN locais local ON local.id = oco.local_id 
                    WHERE oco.origem_ocorrencia_id = '$idEvento' AND local.publicado = 1 AND oco.publicado = 1";

$queryEquipamento = mysqli_query($con, $sqlEquipamento);
$numRowsEquipamento = mysqli_num_rows($queryEquipamento);
?>

<div class="row">
    <div class="col-md-12">
        <form class="formulario-ajax" method="POST" action="../funcoes/api_pedido_eventos.php" name="form-valor-equipamento" role="form" data-etapa="Valor por Equipamento">
            <input type="hidden" name="_method" value="valorPorEquipamento">
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
    </div>
</div>
<ul class="list-inline pull-right">
    <li>
        <a class="btn btn-default prev-step"><span
                aria-hidden="true">&larr;</span>
            Voltar</a>
    </li>
    <li>
        <button type="submit" class="btn btn-primary" id="gravarValorEquipamento">Gravar</button>
    </li>
</ul>
</form>