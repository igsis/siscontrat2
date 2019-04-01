<?php
include "includes/menu_principal.php";
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Solicitar adição de Espaço</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Espaços</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=evento&p=index"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="local">Local: </label>
                                    <select name="local" id="local" class="form-control" required>
                                        <?php
                                            geraOpcao('locais');
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="sigla">Espaço: </label>
                                    <input type="text" class="form-control" id="espaco" name="espaco" required>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="?perfil=administrativo&p=instituicao&sp=instituicao_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <button type="submit" name="cadastraEspaco" id="cadastraEspaco" class="btn btn-primary pull-right">
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