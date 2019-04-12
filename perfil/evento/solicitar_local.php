<?php
include "includes/menu_principal.php";

$idAtracao = $_POST['idAtracao'];

?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Solicitar adição de Local</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Local</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=evento&p=index"
                          role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="cep">Instituição: *</label>
                                    <select name="instituicao" id="instituicao" class="form-control" required>
                                        <?php
                                            geraOpcao('instituicoes');
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="cep">Local: *</label>
                                    <input type="text" class="form-control" name="local" id="local" required>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="cep">CEP: *</label>
                                    <input type="text" class="form-control" name="cep" id="cep" maxlength="9" placeholder="Digite o CEP" required data-mask="00000-000">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="cep">Zona: *</label>
                                    <select class="form-control" id="zona" name="zona">
                                        <?php
                                            geraOpcao('zonas');
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="rua">Rua: *</label>
                                    <input type="text" class="form-control" name="rua" id="rua" placeholder="Digite a rua" maxlength="200" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="numero">Número: *</label>
                                    <input type="number" name="numero" class="form-control" placeholder="Ex.: 10" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="complemento">Complemento:</label>
                                    <input type="text" name="complemento" class="form-control" maxlength="20" placeholder="Digite o complemento">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="bairro">Bairro: *</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro" placeholder="Digite o Bairro" maxlength="80" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Digite a cidade" maxlength="50" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" class="form-control" name="estado" id="estado" maxlength="2" placeholder="Ex.: SP" readonly>
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="?perfil=evento&p=atracoes_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" name="idAtracao" value="<?=$idAtracao?>">
                            <button type="submit" name="cadastraLocal" id="cadastraLocal"
                                    class="btn btn-primary pull-right">
                                Cadastrar
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

    </section>
    <!-- /.content -->
</div>