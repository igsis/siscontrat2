<?php
$con = bancoMysqli();
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2";
$http = $server . "/pdf/";

$link_padraoEvento = $http . "padrao_evento.php";
$link_piaEvento = $http . "pia.evento.php";

$amparo = $_POST['amparo'];
$idEvento = $_SESSION['eventoId'];
$dotacao = $_POST['dotacao'];
$finalizacao = $_POST['finalizar'];

if(isset($_POST['finalizar'])) {
             $sqlInsert =  "INSERT INTO juridicos(pedido_id, amparo_legal, finalizacao, dotacao) 
                VALUES ('$idEvento','$amparo','$finalizacao','$dotacao')";
}
?>
    <div class="content-wrapper" >
    <section class="content" >
        <div class="box box-primary" >
            <h2 align = "center" > Escolha um modelo </h2 >
            <div class="box-body" >
                <div class="row" >
                    <div class="col-md-offset-4 col-md-4" align = "center" >
                        <a href = "<?= $link_padraoEvento ?>" target = "_blank" >
                            <button type = "submit" class="btn btn-primary btn-lg btn-block" > DESPACHO PADRÃO
    </button >
                        </a >
                        <br >
                        <a href = "#" target = '_blank' >
                            <button type = "submit" class="btn btn-primary btn-lg btn-block" > DESPACHO FORMAÇÃO
    </button >
                        </a >
                        <br >
                        <a href = "" target = '_blank' >
                            <button type = "submit" class="btn btn-primary btn-lg btn-block" > MANIFESTAÇÃO JURÍDICA
    </button >
                        </a >
                        <br >
                        <a href = "" target = '_blank' >
                            <button type = "submit" class="btn btn-primary btn-lg btn-block" > DESPACHO OFICINAS
    </button >
                        </a >
                    </div >
                </div >
                </div >
            </div >
    </section >
</div >