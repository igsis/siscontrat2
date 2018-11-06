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
                                <div class="form-group col-md-6">
                                    <label for="cnpj">CNPJ: </label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj"
                                           required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="ccm">CCM: </label>
                                    <input type="text" class="form-control" id="ccm" name="ccm">
                                </div>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-6">
                                    <label for="representante_legal1_id">Representante Legal 1: </label>
                                    <select name="representante_legal1_id" id="representante_legal1_id" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                            geraOpcao("representante_legais");
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="representante_legal2_id">Representante Legal 2: </label>
                                    <select name="representante_legal2_id" id="representante_legal2_id" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                            geraOpcao("representante_legais");
                                        ?>
                                    </select>
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
