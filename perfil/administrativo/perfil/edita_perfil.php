<?php
$con = bancoMysqli();
$conn = bancoPDO();

if(isset($_POST['cadastra']) || (isset($_POST['edita']))){
    $nome = addslashes($_POST['nome']);
    $token = addslashes($_POST['token']);

    if(isset($_POST['cadastra'])){
        $sql = "INSERT INTO perfis (descricao, token)
                VALUES ('$nome', '$token')";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            $mensagem = mensagem("success", "Perfil cadastrado com sucesso!");
            $idPerfil = recuperaUltimo('perfis');
        } else {
            $mensagem = mensagem("danger", "Erro no cadastro de perfil! Tente novamente.");
        }
    }

    if(isset($_POST['edita'])){
        $idPerfil = $_POST['idPerfil'];

        $sql = "UPDATE perfis SET descricao = '$nome', token = '$token' WHERE id = '$idPerfil'";

        if(mysqli_query($con, $sql)){
            gravarLog($sql);
            $mensagem = mensagem("success", "Perfil editado com sucesso!");
        }else{
            $mensagem = mensagem("danger", "Erro ao salvar o perfil! Tente novamente.");
        }
    }
}

if(isset($_POST['carregar'])){
    $idPerfil = $_POST['idPerfil'];
}

if(isset($_POST['adicionaModulo'])){
    $idPerfil = $_POST['idPerfil'];
    $idModulo = $_POST['idModulo'];

    $sql = "INSERT INTO modulo_perfis (perfil_id, modulo_id) VALUES ('$idPerfil', '$idModulo')";

    if(mysqli_query($con, $sql)){
        gravarLog($sql);
        $mensagem2 = mensagem("success", "Modulo adicionado com sucesso!");
    }else{
        $mensagem2 = mensagem("danger", "Erro ao adicionar módulo! Tente novamente.");
    }
}

if (isset($_POST['excluir'])) {
    $idPerfil = $_POST['idPerfil'];
    $idModulo = $_POST['idModulo'];
    $stmt = $conn->prepare("DELETE FROM modulo_perfis WHERE perfil_id = :idPerfil AND modulo_id = :idModulo");
    $stmt->execute(['idPerfil' => $idPerfil, 'idModulo' => $idModulo]);
    $mensagem2 = mensagem("success", "Excluido com sucesso!");
}

$perfil = recuperaDados('perfis', 'id', $idPerfil);

$sql = "SELECT modu.id id, modu.descricao FROM modulo_perfis modpf INNER JOIN modulos modu ON modu.id = modpf.modulo_id WHERE modpf.perfil_id = '$idPerfil'";
$query = mysqli_query($con, $sql);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Edição de Perfil</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Perfil</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=administrativo&p=perfil&sp=edita_perfil"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-9">
                                    <label for="nome">Descrição do perfil *</label>
                                    <input type="text" id="nome" name="nome" class="form-control" required value="<?= $perfil['descricao'] ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="token">Token *</label>
                                    <input type="text" id="token" name="token" class="form-control" required value="<?= $perfil['token'] ?>">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="?perfil=administrativo&p=perfil&sp=perfil_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" name="idPerfil" id="idPerfil" value="<?= $idPerfil ?>">
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

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem de Módulos</h3>
                        <form action="?perfil=administrativo&p=perfil&sp=adicionar_modulo" method="POST">
                            <input type="hidden" name="idPerfil" id="idPerfil" value="<?= $idPerfil ?>">
                            <button type="submit" class="text-right btn btn-success" style="float: right">Linkar Módulo</button>
                        </form>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem2)) {
                            echo $mensagem2;
                        }; ?>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblPerfil" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="90%">Modulo</th>
                                <th>Excluir</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($modulo = mysqli_fetch_array($query)) {
                                echo "<tr>";
                                echo "<td>" . $modulo['descricao'] . "</td>";
                                ?>
                                <td>
                                    <form method='POST' id='formExcliuir'>
                                        <input type="hidden" name='idPerfil' value="<?= $perfil['id'] ?>">
                                        <input type="hidden" name='idModulo' value="<?= $modulo['id'] ?>">
                                        <button type="button" class="btn btn-block btn-danger" id="excluiPerfil"
                                                data-toggle="modal" data-target="#exclusao" name="excluiPerfil"
                                                data-nome="<?= $modulo['descricao'] ?>"
                                                data-modulo="<?= $modulo['id'] ?>"
                                                data-perfil="<?= $idPerfil ?>"><span
                                                    class='glyphicon glyphicon-trash'></span></button>
                                    </form>
                                </td>
                                <?php
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Perfil</th>
                                <th>Excluir</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

        <!--.modal-->
        <div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmação de Exclusão</h4>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="?perfil=administrativo&p=perfil&sp=edita_perfil" method="post">
                            <input type="hidden" name="idPerfil" id="idPerfil" value="">
                            <input type="hidden" name="idModulo" id="idModulo" value="">
                            <input type="hidden" name="apagar" id="apagar">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                            </button>
                            <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
                        </form>
                    </div>
                </div>

            </div>
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