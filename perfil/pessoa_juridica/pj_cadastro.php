<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro Pessoa Jurídica</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações Pessoa Jurídica</h3>
                    </div>

                    <form method="POST" action="?perfil=pessoa_juridica/pj_edita" role="form">
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
                                           required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="ccm">CCM: </label>
                                    <input type="text" class="form-control" id="ccm" name="ccm">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="telefone">Telefone: </label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" required>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-6">
                                    <label for="representante_legal1_id">Representante Legal 1: </label>
                                    <select name="representante_legal1_id" id="representante_legal1_id"
                                            class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("representante_legais");
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="representante_legal2_id">Representante Legal 2: </label>
                                    <select name="representante_legal2_id" id="representante_legal2_id"
                                            class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("representante_legais");
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="cep">CEP: </label>
                                    <input type="text" class="form-control" id="cep" name="cep"
                                           maxlength="100" required>
                                </div>

                                <div class="form-group col-md-5">
                                    <label for="logradouro">Rua: </label>
                                    <input type="text" class="form-control" id="logradouro" name="logradouro"
                                           maxlength="200"
                                           required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="bairro">Bairro:</label>
                                    <input type="text" class="form-control" id="bairro" name="bairro" required>
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
                                    <input type="text" class="form-control" id="uf" name="uf" required>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="cidade">Cidade:</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade" required>
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
