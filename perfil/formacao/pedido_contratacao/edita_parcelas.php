<?php
$con = bancoMysqli();
$idUser = $_SESSION['idUser'];
$idPedido = $_SESSION['idPedido'];

$pedido = recuperaDados('pedidos', 'id', $idPedido);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Atualizar Parcelas do pedido</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Vigência</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=pedido_contratacao&sp=edita"
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
                                               value="<?= dinheiroParaBr($parcela['valor']) ?>">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="data_pagamento">Data pagamento: </label>
                                        <input type="date" name="data_pagamento[]" class="form-control"
                                               id="datepicker12" placeholder="DD/MM/AAAA"
                                               value="<?= $parcela['data_pagamento'] ?? NULL ?>">
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="box-footer">
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
