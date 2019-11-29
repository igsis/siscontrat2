<?php
include "includes/menu_interno.php";

if(isset($_POST['trocaProponente'])){
    $idPessoa = $_POST['idProponente'];
    $idPedido = $_POST['idPedido'];
}

?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Trocar Proponente</h2>
        <div class="row">
            <div class="col-md-12">

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Escolha um tipo de pessoa</h3>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-offset-3 col-md-3">
                                <form method="POST" action="?perfil=evento&p=pf_pesquisa" role="form">
                                    <input type="hidden" name="idProponente" value="<?=$idPessoa?>">
                                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                    <button type="submit" name="troca_pf" class="btn btn-block btn-primary btn-lg">Pessoa Física</button>
                                </form>
                            </div>
                            <div class="form-group col-md-3">
                                <form method="POST" action="?perfil=evento&p=pj_pesquisa" role="form">
                                    <input type="hidden" name="idProponente" value="<?=$idPessoa?>">
                                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                    <button type="submit" name="troca_pj" class="btn btn-block btn-primary btn-lg">Pessoa Jurídica</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

