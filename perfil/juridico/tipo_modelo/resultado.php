<?php
$con = bancoMysqli();
$numprocesso = "";
$codigopedido ="";

if (isset($_POST['numprocesso']) && $_POST['numprocesso'] != null) {
    $numprocesso = $_POST['numprocesso'];
    $numprocesso = "AND numero_processo='$numprocesso'";
}
if (isset($_POST['$codigopedido']) && $_POST['$codigopedido'] != null) {
    $codigopedido = $_POST ['$codigopedido'];
    $codigopedido = "AND protocolo ='$codigopedido"; //*forma pagamento da tabela pedido */ - para o campo de FORMA DE PAGAMENTO
}
?>


<div class="box box-primary" id="filtro">
    <form method="POST" action="?perfil=juridico&p=tipo_modelo&sp=seleciona_modelo">
        <h2 align="center" >DESPACHO DE PESSOA FÍSICA</h2>
        <div class="box-body">
            <div class="row">
                <div class="col-md-offset-4 col-md-4" align="center">
                    <form>
                        <div class="form-group">
                            <div class="col-md-6 col-md-6"><strong>Código do Pedido de Contratação:</strong><br/><?php  ?></div>
                            <div class="col-md-6"><strong>Número do Processo:</strong><br/><?php  ?> </div>
                            <div class="col-md-12"><strong>Contratado:</strong><br><?php ?></div>
                            <div class="col-md-12"><strong>Local:</strong><br><?php  ?> </div>
                            <div class="col-md-6"><strong>Valor:</strong><br><?php ?></div>
                            <div class="col-md-6"><strong>Periodo:</strong><br><?php  ?> </div>
                            <div class="col-md-12"><strong>Forma de Pagamento:</strong><br><?php  ?> </div>
                            <div class="col-md-12"><strong>Amparo</strong><br><?php  ?> </div>
                            <div class="col-md-12"><strong>Finalização:</strong><br><?php  ?> </div>
                        </div>
                        <a href=""><button type="button" class="btn btn-primary btn-lg btn-block">Gravar<button</a>

                    </form>
                </div>
            </div>
        </div>
    </form>
</div>
</section>