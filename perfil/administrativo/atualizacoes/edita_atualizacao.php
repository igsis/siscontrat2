<?php
$con = bancoMysqli();

if(isset($_POST['cadastra']) || (isset($_POST['edita']))){
    $titulo = addslashes($_POST['titulo']);
    $msg = addslashes($_POST['msg']);
    $hoje = date("Y-m-d H:i:s");

    if(isset($_POST['cadastra'])){
        $sql = "INSERT INTO avisos (titulo, mensagem, data) VALUES ('$titulo', '$msg', '$hoje')";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Aviso cadastrado com sucesso!");
            $idAtualizacao = recuperaUltimo('avisos');
        } else {
            $mensagem = mensagem("danger", "Erro no cadastro do aviso! Tente novamente.");
        }
    }

    if(isset($_POST['edita'])){
        $hoje = date("Y-m-d H:i:s");
        $idAtualizacao = $_POST['idAtualizacao'];

        $sql = "UPDATE avisos SET titulo = '$titulo', mensagem = '$msg', data = '$hoje' WHERE id = '$idAtualizacao'";

        if(mysqli_query($con, $sql)){
            gravarLog($sql);
            $mensagem = mensagem("success", "Aviso editado com sucesso!");
        }else{
            $mensagem = mensagem("danger", "Erro ao salvar o aviso! Tente novamente.");
        }
    }
}

if(isset($_POST['carregar'])){
    $idAtualizacao = $_POST['idAtualizacao'];
}

$atualizacao = recuperaDados('avisos', 'id', $idAtualizacao);

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Edição de Atualizações</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atualização</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=administrativo&p=atualizacoes&sp=edita_atualizacao"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="titulo">Título *</label>
                                    <input align="center" type="text" id="titulo" name="titulo" class="form-control" value="<?= $atualizacao['titulo'] ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group  col-md-12">
                                    <label for="msg">Mensagem *</label>
                                    <textarea type="text" rows="5" id="msg" name="msg" class="form-control" required><?= $atualizacao['mensagem'] ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="?perfil=administrativo&p=atualizacoes&sp=atualizacoes_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" name="idAtualizacao" id="idAtualizacao" value="<?= $idAtualizacao ?>">
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