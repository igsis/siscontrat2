<?php
    $con = bancoMysqli();
    include "includes/menu_principal.php";

?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Serviços Internos</h2>

            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cadastrar Produtor</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=evento_produtor_edita" role="form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nomeProdutor">Nome do produtor do evento: *</label>
                                <input type="text" class="form-control" name="nomeProdutor" id="nomeProdutor">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="telefone1">Telefone #1: *</label>
                                    <input type="text" class="form-control" name="telefone1" id="telefone1">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="telefone2">Telefone #2: </label>
                                    <input type="text" class="form-control" name="telefone2" id="telefone2">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email: *</label>
                                <input type="text" class="form-control" name="email" id="email">
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="reset" class="btn btn-default">Cancel</button>
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
            </section>
        </div>
