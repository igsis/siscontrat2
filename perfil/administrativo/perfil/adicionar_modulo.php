<?php
$idPerfil = $_POST['idPerfil'];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Adicionar módulo ao perfil</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Perfil - Módulo</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=administrativo&p=perfil&sp=edita_perfil"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="nome">Módulo *</label>
                                    <select class="form-control" id="idModulo" name="idModulo" required>
                                        <option value="">Selecione...</option>
                                        <?php
                                        geraOpcao("modulos")
                                        ?>
                                    </select>
                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <a href="?perfil=administrativo&p=perfil&sp=perfil_lista">
                                        <button type="button" class="btn btn-default">Voltar</button>
                                    </a>
                                    <input type="hidden" id="idPerfil" name="idPerfil" value="<?= $idPerfil ?>">
                                    <button type="submit" name="adicionaModulo" id="adicionaModulo"
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