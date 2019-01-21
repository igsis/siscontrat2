<?php
include "includes/menu_pj.php";
$con = bancoMysqli();
?>

<script>
    $(document).ready(function () {
        $("#cep").mask('00000-000', {reverse: true});
        $("#telefone").mask('(00) 0000-00009', {reverse: true});
    });

</script>
<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro Pessoa Jurídica</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações Pessoa Jurídica</h3>
                    </div>

                    <form method="POST" action="?perfil=evento&p=pj_edita" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="razao_social">Razão Social: </label>
                                    <input type="text" class="form-control" id="razao_social" name="razao_social"
                                           maxlength="100" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="email">Email: </label>
                                    <input type="email" class="form-control" id="email" name="email" maxlength="60"
                                           required>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-4">
                                    <label for="cnpj">CNPJ: </label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj"
                                           required readonly value="<?= $_POST['cnpj'] ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="ccm">CCM: </label>
                                    <input type="text" class="form-control" id="ccm" name="ccm">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="telefone">Telefone: </label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" required data-mask="(00) 0000-00000">
                                </div>
                            </div>


                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="cep">CEP: </label>
                                    <input type="text" class="form-control" id="cep" name="cep"
                                           maxlength="100" required data-mask="00000-000">
                                </div>

                                <div class="form-group col-md-5">
                                    <label for="logradouro">Rua: </label>
                                    <input type="text" class="form-control" id="rua" name="logradouro"
                                           maxlength="200"
                                           readonly>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="bairro">Bairro:</label>
                                    <input type="text" class="form-control" id="bairro" name="bairro" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="numero">Número: </label>
                                    <input type="number" class="form-control" id="numero" name="numero" required>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="complemento">Complemento: </label>
                                    <input type="text" class="form-control" id="complemento" name="complemento"
                                           maxlength="20">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="uf">Estado:</label>
                                    <input type="text" class="form-control" id="estado" name="uf" readonly>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="cidade">Cidade:</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade" readonly>
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-default">Cancelar</button>
                                <button type="submit" name="cadastra" id="cadastra" class="btn btn-info pull-right">
                                    Cadastrar
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
</div>
