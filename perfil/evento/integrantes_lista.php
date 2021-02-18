<?php
include "includes/menu_interno.php";

$con = bancoMysqli();
$conn = bancoPDO();

$atracao_id = $_POST['idAtracao'] ?? $_GET['atracao'];

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $cpf_passaporte = $_POST['cpf_passaporte'];
    $nome = trim(addslashes($_POST['nome']));
    $rg = trim(addslashes($_POST['rg']));
    $funcao = trim(addslashes($_POST['funcao']));

    if (isset($_POST['cadastra'])) {

        if (isset($_POST['integrante_id'])) {
            $id = $_POST['integrante_id'];
        } else {
            $add_integrante = $con->query("INSERT INTO integrantes (`nome`, `rg`, `cpf_passaporte`) VALUES ('$nome', '$rg', '$cpf_passaporte')");
            if ($add_integrante) {
                $id = $con->insert_id;
            }
        }

        $atracao_integrante = $con->query("INSERT INTO atracao_integrante (`atracao_id`, `integrante_id`, `funcao`) VALUES ('$atracao_id', '$id', '$funcao')");

        $mensagem = mensagem("success", "Integrante <strong>$nome</strong> adicionado com sucesso");
    } elseif (isset($_POST['edita'])) {
        $id = $_POST['integrante_id'];

        $con->query("UPDATE integrantes SET nome = '$nome', rg = '$rg', cpf_passaporte = '$cpf_passaporte' WHERE id = '$id'");
        $con->query("UPDATE atracao_integrante SET funcao = '$funcao' WHERE atracao_id = '$atracao_id' AND integrante_id = '$id'");

        $mensagem = mensagem("success", "Integrante <strong>$nome</strong> atualizado com sucesso");
    }
}

if (isset($_POST['apagar'])) {
    $integrante_id = $_POST['integrante_id'];
    $query = $con->query("DELETE FROM atracao_integrante WHERE atracao_id = '$atracao_id' AND integrante_id = '$integrante_id'");
    if ($query) {
        $mensagem = mensagem("success", "Integrante apagado da atração com sucesso");
    }
}

$sqlIntegrantes = "SELECT i.*, ai.funcao FROM atracao_integrante AS ai
                    INNER JOIN integrantes AS i ON ai.integrante_id = i.id
                    WHERE atracao_id = '$atracao_id'";
$integrantes = $con->query($sqlIntegrantes)->fetch_all(MYSQLI_ASSOC);

?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Integrantes</h2>
        <div class="row">
            <div class="col-md-2">
                <a href="?perfil=evento&p=integrantes_pesquisa&atracao=<?= $atracao_id ?>"
                   class="btn btn-block btn-info"><i class="fa fa-plus"></i> Adicionar</a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                        <div class="box-tools pull-right">

                        </div>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblIntegrantes" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>RG</th>
                                    <th>Função</th>
                                    <th>Editar</th>
                                    <th>Apagar</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($integrantes as $integrante): ?>
                                <tr>
                                    <td><?= $integrante['nome'] ?></td>
                                    <td><?= $integrante['cpf_passaporte'] ?></td>
                                    <td><?= $integrante['rg'] ?></td>
                                    <td><?= $integrante['funcao'] ?></td>
                                    <td width="5%">
                                        <form action="?perfil=evento&p=integrantes_cadastro&atracao=<?= $atracao_id ?>"
                                              method="post">
                                            <input type="hidden" name="integrante_id" value="<?= $integrante['id'] ?>">
                                            <input type="hidden" name="documento" value="<?= $integrante['cpf_passaporte'] ?>">
                                            <input type="hidden" name="_method" value="edita">
                                            <button class="btn btn-block btn-primary" type="submit"><i class="fa fa-file-text-o"></i></button>
                                        </form>
                                    </td>
                                    <td width='5%'>
                                        <button class='btn btn-block btn-danger' data-toggle='modal' data-target='#apagar' data-integrante-id='<?= $integrante['id'] ?>' data-tittle='Apagar Integrante' data-message='Você deseja mesmo remover este integrante da atração?' onclick ='passarId("<?= $integrante['id'] ?>")'><span class='glyphicon glyphicon-trash'></span></button>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>RG</th>
                                    <th>Função</th>
                                    <th>Editar</th>
                                    <th>Apagar</th>

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

    </section>
    <!-- /.content -->
</div>

<!--Apagar atracao-->
<div class="modal fade" id="apagar" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id='formApagar' action="?perfil=evento&p=integrantes_lista&atracao=<?= $atracao_id ?>" class="form-horizontal"
                  role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><p>Apagar Integrante</p></h4>
                </div>
                <div class="modal-body">
                    <p>Deseja mesmo remover este integrante da atração? </p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="idAtracao" value="<?= $atracao_id ?>">
                    <input type="hidden" name="integrante_id">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type='submit' class='btn btn-danger btn-sm' name="apagar">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    function passarId(id) {
        document.querySelector('#formApagar input[name="integrante_id"]').value = id;
    }

    $(function () {
        $('#tblIntegrantes').DataTable({
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