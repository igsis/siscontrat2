<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Cadastro de Instituicao</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Instituição</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=administrativo&p=instituicao&sp=edita_instituicao"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-9">
                                    <label for="nome">Nome da instituição *</label>
                                    <input type="text" id="nome" name="nome" class="form-control" required maxlength="60">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="Sigla">Sigla *</label>
                                    <input type="text" id="sigla" name="sigla" class="form-control" required maxlength="8">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="?perfil=administrativo&p=instituicao&sp=instituicao_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <button type="submit" name="cadastra" id="cadastra" class="btn btn-primary pull-right">
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