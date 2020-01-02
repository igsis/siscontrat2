<?php
$con = bancoMysqli();

$idPedido = $_POST['idPedido'];

if(isset($_POST['cadastrar']) || isset($_POST['editar'])){
    $extrato_liquidacao = addslashes($_POST['extrato_liquidacao']);
    $retencoes_inss = addslashes($_POST['retencoes_inss']);
    $retencoes_iss = addslashes($_POST['retencoes_iss']);
    $retencoes_irrf = addslashes($_POST['retencoes_irrf']);
}

if(isset($_POST['cadastrar'])){
     $cadastra = $con->query("INSERT INTO liquidacao (pedido_id, extrato_liquidacao, retencoes_inss, retencoes_iss, retencoes_irrf) VALUES ('$idPedido', '$extrato_liquidacao', '$retencoes_inss', '$retencoes_iss', '$retencoes_irrf')");
     if($cadastra){
         $mensagem = mensagem("success", "Cadastrado com sucesso!");
     } else{
         $mensagem = mensagem("danger", "Erro ao cadastrar.");
     }
}

if(isset($_POST['editar'])){
    $edita = $con->query("UPDATE liquidacao SET extrato_liquidacao = '$extrato_liquidacao', retencoes_inss = '$retencoes_inss', retencoes_iss = '$retencoes_iss', retencoes_irrf = '$retencoes_irrf' WHERE pedido_id = '$idPedido'");
    if($edita){
        $mensagem = mensagem("success", "Editado com sucesso!");
    } else{
        $mensagem = mensagem("danger", "Erro ao editar.");
    }
}

$liquidacao = $con->query("SELECT * FROM liquidacao WHERE pedido_id = '$idPedido'")->fetch_array();
if($liquidacao == NULL){
    $botao = "cadastrar";
} else{
    $botao="editar";
}
?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3>Pagamento - Nota de Liquidação</h3>
        </div>
        <div class="box">
            <div class="box-header">
                <h2 class="box-title">Cadastro de Nota de Liquidação</h2>
                <?php
                if ($botao == "editar"){
                    $link = "http://".$_SERVER['SERVER_NAME']."/siscontrat2/pdf/recibo_liquidacao.php";
                    ?>
                    <form action="<?= $link ?>" method="post" target="_blank" role="form">
                        <button type="submit" class="btn btn-primary pull-right" name="idPedido" value="<?= $idPedido ?>">Imprimir Recibo</button>
                    </form>
                <?php
                }
                ?>
            </div>
            <div class="row" align="center">
                <?= $mensagem ?? NULL; ?>
            </div>
            <form action="#" method="post" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="extrato_liquidacao">Extrato de Liquidação e Pagamento nº:</label>
                            <input type="text" class="form-control" name="extrato_liquidacao" id="extrato_liquidacao" placeholder="Número do extrato de liquidação e pagamento" value="<?= $liquidacao['extrato_liquidacao'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="retencoes_inss">Retenções de I.N.S.S:</label>
                            <input type="text" class="form-control" name="retencoes_inss" id="retencoes_inss" placeholder="Guia de recolhimento ou depósito da Prefeitura do Município de São Paulo nº" value="<?= $liquidacao['retencoes_inss'] ?>">
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="retencoes_iss">Retenções de I.S.S:</label>
                            <input type="text" class="form-control" name="retencoes_iss" id="retencoes_iss" placeholder="Documento de Arrecadação de Tributos Mobiliários - DARM nº" value="<?= $liquidacao['retencoes_iss'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="retencoes_irrf">Retenções de I.R.R.F:</label>
                            <input type="text" class="form-control" name="retencoes_irrf" id="retencoes_irrf" placeholder="Guia recibo de recolhimento ou depósito nº" value="<?= $liquidacao['retencoes_irrf'] ?>">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                    <button type="submit" class="btn btn-primary pull-right" name="<?= $botao ?>">Gravar</button>
                </div>
            </form>
        </div>
    </section>
</div>