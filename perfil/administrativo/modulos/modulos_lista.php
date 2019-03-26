<?php
$con = bancoMysqli();
$conn = bancoPDO();

if (isset($_POST['excluir'])) {
    $usuario = $_POST['idUsuario'];
    $stmt = $conn->prepare("UPDATE `usuarios` SET publicado = 0 WHERE id = :id");
    $stmt->execute(['id' => $usuario]);
    $mensagem = mensagem("success", "Usuário excluido com sucesso!");
}


$sql = "SELECT * FROM modulo_perfis";
$query = mysqli_query($con, $sql);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Lista de Usuário</h3>
        <a href="?perfil=administrativo&p=usuario&sp=cadastro_usuario" class="text-right btn btn-success"
           style="float: right">Adicionar Modulo</a>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblUsuario" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Sigla</th>
                                <th>Perfil</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($modulo = mysqli_fetch_array($query)) {
                                $idModulo = $modulo['modulo_id'];

                                $modulos = recuperaDados("modulos", "id", $idModulo);
                                $modulo_perfis = recuperaDados("modulo_perfis", "modulo_id", $idModulo);
                                $perfis = recuperaDados("perfis", "id", $modulo['perfil_id']);


                                /*$sqlPerfis = "SELECT * FROM modulo_perfis as M_P
                                                            INNER JOIN perfis ON perfis.id = M_P.perfil_id
                                                            INNER JOIN modulos ON modulos.id = M_P.modulo_id
                                                            WHERE M_P.modulo_id = '$idModulo' AND M_P.perfil_id = perfis.id";
                                $queryPerfis = mysqli_query($con, $sqlPerfis);
                                $ArrayPerfis = mysqli_fetch_array($queryPerfis);*/

                                $unico = array_diff($ArrayPerfis);

                                print_r($unico);

                               //$descricoes = perfis.sli

                                echo "<tr>";
                                echo "<td>" . $ArrayPerfis['descricao'] . "</td>";
                                echo "<td>" . $ArrayPerfis['sigla'] . "</td>";

                                echo "<td>" .  $ArrayPerfis ['descricao'] .  "</td>";

                               /* $perfis = implode(" | " , $ArrayPerfis);*/


                              // echo "<td>" . $perfis['descricao'] . $perfis['descricao'] . "</td>";

                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=administrativo&p=usuario&sp=edita_usuario\" role=\"form\">
                                    <input type='hidden' name='idUsuario' value='" . $modulo['modulo_id'] . "'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><span class='glyphicon glyphicon-eye-open'></span></button>
                                    </form>
                                </td>";
                                ?>
                                <td>
                                    <form method='POST' id='formExcliuir'>
                                        <input type="hidden" name='idUsuario' value="<?= $modulo['modulo_id'] ?>">
                                        <button type="button" class="btn btn-block btn-danger" id="excluiUsuario"
                                                data-toggle="modal" data-target="#exclusao" name="excluiUsuario"
                                                data-nome="<?= $ArrayPerfis['sigla'] ?>"
                                                data-id="<?= $modulo['modulo_id'] ?>"><span
                                                    class='glyphicon glyphicon-trash'></span></button>
                                    </form>
                                </td>
                                <?php
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Nome</th>
                                <th>Usuário</th>
                                <th>RF/RG</th>
                                <th>email</th>
                                <th>telefone</th>
                                <th colspan="2" width="15%"></th>
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
                        <p>Tem certeza que deseja excluir este usuário?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="?perfil=administrativo&p=usuario&sp=usuario_lista" method="post">
                            <input type="hidden" name="idUsuario" id="idUsuario" value="">
                            <input type="hidden" name="apagar" id="apagar">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                            </button>
                            <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </section>
    <!-- /.content -->
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblUsuario').DataTable({
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
        let nome = $(e.relatedTarget).attr('data-nome');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir o usuário ${nome} ?`);
        $(this).find('#idUsuario').attr('value', `${id}`);
    })
</script>