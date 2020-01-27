<?php
$con = bancoMysqli();

if (isset($_POST['alterar'])) {
    $idNivel = $_POST['idNivel'];
    $idUsuario = $_POST['idUsuario'];

    $sql = "INSERT INTO usuario_contratos VALUES ('$idUsuario', '$idNivel')";
    $deleteRelacaoUser = "DELETE FROM usuario_contratos WHERE usuario_id = '$idUsuario'";

    if (mysqli_query($con, $deleteRelacaoUser) && mysqli_query($con, $sql)) {
        $mensagem = mensagem('success', 'Troca de nível do usuário feito com sucesso!');
        gravarLog($sql);
    } else {
        $mensagem = mensagem('danger', 'Ocorreu um erro ao trocar o nível do usuário selecionado. Tente novamente!');
    }
}

if (isset($_POST['excluir'])) {
    $idUsuario = $_POST['idUsuario'];

    $deleteRelacaoUser = "DELETE FROM usuario_contratos WHERE usuario_id = '$idUsuario'";

    if (mysqli_query($con, $deleteRelacaoUser)) {
        $mensagem = mensagem('success', 'Exclusão feita com sucesso!');
    } else {
        $mensagem = mensagem('danger', 'Ocorreu um erro ao excluir. Tente novamente.');
    }
}

function geraUserContratos()
{
    //gera os options de um select
    $sql = "SELECT u.id,u.nome_completo 
            FROM usuarios AS u 
            INNER JOIN perfis p on u.perfil_id = p.id
            INNER JOIN modulo_perfis mp on p.id = mp.perfil_id
            WHERE mp.modulo_id = 6 AND p.publicado = 1 AND u.publicado = 1
            GROUP BY u.nome_completo
            ORDER BY u.nome_completo";
    $con = bancoMysqli();
    $query = mysqli_query($con,$sql);
    while($option = mysqli_fetch_row($query))
    {
        echo "<option value='".$option[0]."'>".$option[1]."</option>";
    }
}

$usuarios = "SELECT u.id, u.nome_completo, na.nivel FROM usuario_contratos uc INNER JOIN usuarios u ON u.id = uc.usuario_id INNER JOIN nivel_acessos na on uc.nivel_acesso = na.id WHERE u.publicado = 1";
$query = mysqli_query($con, $usuarios);
$num_rows = mysqli_num_rows($query);
?>


<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Resultado de busca</h3>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblResultado" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="40%">Nome</th>
                                <th width="40%">Acesso</th>
                                <th>Ação</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            if ($num_rows == 0) {
                                ?>
                                <tr>
                                    <th colspan="3"><p align="center">Não foram encontrados registros</p></th>
                                </tr>
                                <?php
                            } else {
                                while ($usuario = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $usuario['nome_completo'] ?></td>
                                        <td><?= $usuario['nivel'] ?></td>
                                        <td>
                                            <button type="button" name="excluir" id="excluir"
                                                    class="btn btn-block btn-danger"
                                                    data-target="#exclusao" data-toggle="modal"
                                                    data-id="<?= $usuario['id'] ?>">
                                                Remover
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            <tr>
                                <form action="?perfil=contrato&p=admin&sp=nivel_usuario" method="POST" role="form">

                                    <td>
                                        <select name="idUsuario" id="idUsuario" class="form-control" required>
                                            <?php
                                            geraUserContratos();
                                            ?>
                                        </select>
                                    </td>

                                    <td>
                                        <select name="idNivel" id="idNivel" class="form-control" required>
                                            <?php
                                            geraOpcao('nivel_acessos');
                                            ?>
                                        </select>
                                    </td>

                                    <td>
                                        <button type="submit" name="alterar" class="btn btn-block btn-primary">
                                            Adicionar
                                        </button>
                                    </td>
                                </form>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Nome</th>
                                <th>Acesso</th>
                                <th>Ação</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <form action="?perfil=contrato&p=admin&sp=nivel_usuario" method="POST">
                    <h4 class="modal-title">Confirmação de Exclusão</h4>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja remover o nível do usuário?</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idUsuario" id="idUsuario" value="">
                <input type="hidden" name="excluir" id="excluir">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                </button>
                <input type="submit" class="btn btn-danger btn-outline" name="exclui" value="Excluir">
                </form>
            </div>
        </div>
    </div>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('#idUsuario').attr('value', `${id}`);
    })
</script>

<script type="text/javascript">
    $(function () {
        $('#tblResultado').DataTable({
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