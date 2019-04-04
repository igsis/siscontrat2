<?php
$con = bancoMysqli();

if(isset($_POST['cadastra']) || (isset($_POST['edita']))){
    $nome = addslashes($_POST['nome']);

    if(isset($_POST['cadastra'])){
        $sql = "INSERT INTO categoria_atracoes (categoria_atracao) VALUES ('$nome')";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Categoria cadastrada com sucesso!");
            $idCategoria = recuperaUltimo('categoria_atracoes');
        } else {
            $mensagem = mensagem("danger", "Erro no cadastro da categoria! Tente novamente.");
        }
    }

    if(isset($_POST['edita'])){
        $idCategoria = $_POST['idCategoria'];

        $sql = "UPDATE categoria_atracoes SET categoria_atracao = '$nome' WHERE id = '$idCategoria'";

        if(mysqli_query($con, $sql)){
            gravarLog($sql);
            $mensagem = mensagem("success", "Categoria editada com sucesso!");
        }else{
            $mensagem = mensagem("danger", "Erro ao salvar a categoria! Tente novamente.");
        }
    }
}

if(isset($_POST['carregar'])){
    $idCategoria = $_POST['idCategoria'];
}

$categoria = recuperaDados('categoria_atracoes', 'id', $idCategoria);

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Edição de Categoria</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Categoria</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=administrativo&p=categoria&sp=edita_categoria"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="nome">Categoria da Atração *</label>
                                    <input type="text" id="nome" name="nome" class="form-control" required value="<?= $categoria['categoria_atracao'] ?>">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="?perfil=administrativo&p=categoria&sp=categoria_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" name="idCategoria" id="idCategoria" value="<?= $idCategoria ?>">
                            <button type="submit" name="edita" id="edita" class="btn btn-primary pull-right">
                                Salvar
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

        <!--.modal-->
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

    </section>
    <!-- /.content -->
</div>


<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblPerfil').DataTable({
            "language": {
                "url": 'bower_components/datatables.net/Portuguese-Brasil.json'
            },
            "responsive": true,
            "dom": "<'row'<'col-sm-6'l><'col-sm-6 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>",
        });
    });
</script>

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let idPerfil = $(e.relatedTarget).attr('data-perfil');
        let idModulo = $(e.relatedTarget).attr('data-modulo');

        $(this).find('#idPerfil').attr('value', `${idPerfil}`);
        $(this).find('#idModulo').attr('value', `${idModulo}`);
    })
</script>