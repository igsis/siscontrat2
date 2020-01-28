<?php
$con = bancoMysqli();

$idEvento = $_SESSION['idEvento'];
$pedido = $con->query("SELECT id, numero_parcelas FROM pedidos WHERE origem_id = $idEvento AND origem_tipo_id = 1 AND publicado = 1")->fetch_array();
$idPedido = $pedido['id'];
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Atualizar Parcelas do pedido</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Parcelas</h3>
                    </div>
                    <form method="POST" action="?perfil=contrato&p=resumo"
                          role="form">
                        <div class="box-body">
                            <?php
                            for ($i = 1; $i < $pedido['numero_parcelas'] + 1; $i++) {
                                $sql = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";
                                $parcela = mysqli_fetch_array(mysqli_query($con, $sql));
                                ?>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="parcela[]">Parcela:</label>
                                        <input type="number" readonly class="form-control" value="<?= $i ?>"
                                               name="parcela[]" id="parcela[]" required>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="valor[]">Valor:</label>
                                        <input type="text" id="valor[]" name="valor[]"
                                               class="form-control" onKeyPress="return(moeda(this,'.',',',event))"
                                               value="<?= dinheiroParaBr($parcela['valor']) ?>" required>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="data_pagamento">Data pagamento: </label>
                                        <input type="date" name="data_pagamento[]" class="form-control"
                                               id="datepicker12" placeholder="DD/MM/AAAA" required
                                               value="<?= $parcela['data_pagamento'] ?? NULL ?>">
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="box-footer">
                        <input type="hidden" value="<?=$idPedido?>" name="idPedido">
                            <button type="submit" name="parcelaEditada" id="parcelaEditada"
                                    class="btn btn-primary pull-right">
                                Gravar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

