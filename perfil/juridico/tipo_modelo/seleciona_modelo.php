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
    $codigopedido = "AND protocolo ='$codigopedido";
}
?>
<div class="box box-primary" id="filtro">
    <form method="POST" action="?perfil=juridico&p=eventos&sp=pesquisarFormacao">
        <h2 align="center" >Escolha um modelo</h2>
        <div class="box-body">
            <div class="row">
                <div class="col-md-offset-4 col-md-4" align="center">
                    <br>
                    <a href="?perfil=juridico&p=tipo_modelo&sp=resultado"><button type="button" class="btn btn-primary btn-lg btn-block">PADRÃO<button</a>
                </div>
            </div>
        </div>
    </form>
</div>
</section>