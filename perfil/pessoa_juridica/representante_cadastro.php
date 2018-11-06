<?php
include "includes/menu_interno.php";
$con = bancoMysqli();

?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Representante</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações Presentante</h3>
                    </div>

                    <form method="POST" action="?perfil=pessoa_juridica/representante_edita" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nome">Nome: </label>
                                    <input type="text" class="form-control" id="nome" name="nome"
                                           maxlength="70" required>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="rg">RG: </label>
                                    <input type="text" class="form-control" id="rg" name="rg" required>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="cpf">CPF: </label>
                                    <input type="text" class="form-control" id="cpf" name="cpf" required>
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
