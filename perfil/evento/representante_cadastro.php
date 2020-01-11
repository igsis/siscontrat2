<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$idPj = $_SESSION['idPj'];
$cpf = $_POST['documentacao'];
$tipoRepresentante = $_POST['tipoRepresentante'];

?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastro de Representante</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações Representante</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=representante_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nome">Nome: </label>
                                    <input type="text" class="form-control" id="nome" name="nome"
                                           pattern="[a-zA-ZàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇáéíóúýÁÉÍÓÚÝ ]{1,70}" title="Apenas letras"
                                           maxlength="70" required>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="rg">RG: </label>
                                    <input type="text" class="form-control" id="rg" name="rg" required maxlength="12">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="cpf">CPF: </label>
                                    <input type="text" class="form-control" id="cpf" name="cpf" required value="<?= $cpf; ?>" readonly>
                                </div>
                            </div>
                                <input type="hidden" name="tipoRepresentante" value="<?= $tipoRepresentante ?>">
                                <input type="hidden" name="idPj" value="<?= $idPj ?>">
                                <button type="submit" name="cadastra" id="cadastra" class="btn btn-info pull-right">
                                    Cadastrar
                                </button>
                    </form>
                    <form action="?perfil=evento&p=pj_edita" method="post">
                        <button type="submit" name="idPj" id="idPj" value="<?= $idPj ?>" class="btn btn-default">Voltar</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    $("#voltar").on("click", function () {

        var nome = document.querySelector("#nome");
        var rg = document.querySelector("#rg");
        var cpf = document.querySelector("#cpf");

        nome.required = false;
        rg.required = false;
        cpf.required = false;

    })
</script>