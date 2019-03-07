<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$idPedido = $_SESSION['idPedido'];

$sqlParcelas = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido'";
$query = mysqli_query($con, $sqlParcelas);
$nums = mysqli_num_rows($query);
$array = range(1, $nums);


if(isset($_POST['salvar'])) {
    $valor = $_POST['valor'];
    $dataPagamento = $_POST['data_kit_pagamento'];

    $sqlUpdate = "UPDATE parcelas SET valor = '$valor', data_pagamento = '$dataPagamento' WHERE pedido_id = '$idPedido'";

    if (mysqli_query($con, $sqlUpdate)) {
        gravarLog($sqlUpdate);
        $mensagem = mensagem("success", "Parcelas editadas com sucesso!");

    } else {
        $mensagem = mensagem("danger", "Erro ao atualizar! Tente novamente.");
    }


}



?>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Parcelas</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edição de parcelas</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=parcelas_edita" role="form">
                        <div class="box-body center-block">
                            <?php
                            while ($parcela = mysqli_fetch_array($query)) {
                                ?>
                                <div class='row'>
                                    <div class='form-group col-md-offset-3 col-md-1'>
                                        <label for='parcela'>Parcela </label>
                                        <input type='number' value="<?=$parcela['numero_parcelas']?>" class='form-control' disabled>
                                    </div>
                                    <div class='form-group col-md-2'>
                                        <label for='valor'>Valor </label>
                                        <input type='text' id='valor' name='valor' value="<?=  dinheiroParaBr($parcela['valor'])?>"
                                               onkeypress="return(moeda(this, '.', ',', event))" class='form-control'>
                                    </div>
                                    <div class='form-group col-md-3'>
                                        <label for='data_kit_pagamento'>Data Kit Pagamento</label>
                                        <input type='date' id='data_kit_pagamento' value="<?=$parcela['data_pagamento']?>"
                                               name='data_kit_pagamento' class='form-control'>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-default">Cancelar</button>
                                <button type="submit" name="salvar" class="btn btn-info pull-right">Salvar
                                    alterações
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>




