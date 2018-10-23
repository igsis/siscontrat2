<?php

    $con = bancoMysqli();
    include "includes/menu_interno.php";

    if (isset($_POST['idAtracao'])){
        $idAtracao = $_POST['idAtracao'];
    }

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Cadastro de Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Produtor do Evento</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="?perfil=evento&p=oficina_edita" method="post" role="form">
                        <div class="box-body">
                            <input type="hidden" name="idAtracao" value="<?= $idAtracao?>">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="certificado">Certificado: *</label>
                                    <input type="number" class="form-control" name="certificado" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="vagas">Vagas: *</label>
                                    <input type="number" class="form-control" name="vagas" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="publicoAlvo">Público Alvo: *</label>
                                <textarea name="publicoAlvo" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="material">Material: </label>
                                <textarea name="material" id="" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="inscricao">Inscrição: *</label>
                                    <input type="number" class="form-control" name="inscricao">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="valorHora">Valor Hora: *</label>
                                    <input type="text" class="form-control" name="valorHora">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="venda">Venda: *</label>
                                    <input type="number" class="form-control" name="venda">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="dataDivulgacao">Data de Divulgação: *</label>
                                    <input type="text" class="form-control" name="dataDivulgacao">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="cargaHoraria">Carga  Horaria: *</label>
                                    <input type="number" class="form-control" name="cargaHoraria">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-default">Cancelar</button>
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
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