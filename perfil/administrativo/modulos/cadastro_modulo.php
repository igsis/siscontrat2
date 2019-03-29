<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Cadastro de Módulos</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Módulos</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=administrativo&p=modulos&sp=edita_modulos"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="sigla">Sigla: </label>
                                    <input type="text" class="form-control" id="sigla" name="sigla"
                                           maxlength="70" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="descricao">Descrição: </label>
                                    <input type="text" class="form-control text" id="descricao" name="descricao" required maxlength="12">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="cor">Cor: </label>
                                    <select class="form-control" id="cor" name="cor">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                            geraOpcao("cores");
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-1 cor text-center" style="margin-top: 6px; display: none;">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="?perfil=administrativo&p=modulos&sp=modulos_lista">
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


<script>
    $("#cor").on("change", function () {

        let selecionado = $("#cor :selected").text();

        let cor = selecionado.split("-");

        if (cor.length > 2) {
            cor = cor[1] + "-" + cor[2];
        } else {
            cor = cor[1];
        }

        let div = document.querySelector(".cor");

        div.style.display = "block";

        $(".cor").html("<label for='cor'><span class='glyphicon glyphicon-eye-open'></span></label><input type='text' class='form-control bg-"+ cor + "' disabled>");

    });
</script>