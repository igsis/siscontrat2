<?php
$idInstituicao = $_POST['idInstituicao'];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Adicionar Local</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Local</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=administrativo&p=instituicao&sp=edita_local"
                          role="form">
                        <div class="box-body">

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
                                    <a href="?perfil=administrativo&p=instituicao&sp=instituicao_lista">
                                        <button type="button" class="btn btn-default">Voltar</button>
                                    </a>
                                    <input type="hidden" id="idInstituicao" name="idInstituicao" value="<?= $idInstituicao ?>">
                                    <button type="submit" name="cadastra" id="cadastra"
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