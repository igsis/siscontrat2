<?php
$con = bancoMysqli();

$sql = "SELECT * FROM pessoa_fisicas";
$query = mysqli_query($con, $sql);
$num_arrow = mysqli_num_rows($query);

?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Lista de Pessoa Física</h3>
        <a href="?perfil=formacao&p=pessoa_fisica&sp=pesquisa" class="text-right btn btn-success"
           style="float: right">Adicionar Pessoa Física</a>
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
                        <table id="tblPf" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>CPF/Passaporte</th>
                                <th>Data nascimento</th>
                                <th>Email</th>
                                <th width="10%">Demais anexos</th>
                                <th width="5%">Editar</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            if ($num_arrow == 0) {
                                ?>
                                <tr>
                                    <th colspan="5"><p align="center">Não foram encontrados registros</p></th>
                                </tr>
                                <?php
                            } else {
                                while ($pf = mysqli_fetch_array($query)) {
                                    //verifica se irá mostrar passaporte ou cpf
                                    if ($pf['cpf'] == NULL) {
                                        $doc = $pf['passaporte'];
                                    } else {
                                        $doc = $pf['cpf'];
                                    }

                                    //verifica se há ou não data de nascimento

                                    if ($pf['data_nascimento'] == "0000-00-00") {
                                        $dataNascimento = "Não cadastrado";
                                    } else {
                                        $dataNascimento = exibirDataBr($pf['data_nascimento']);
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $pf['nome'] ?></td>
                                        <td><?= $doc ?></td>
                                        <td><?= $dataNascimento ?></td>
                                        <td><?= $pf['email'] ?></td>
                                        <td>
                                            <form action="?perfil=formacao&p=pessoa_fisica&sp=pf_demais_anexos"
                                                  method="POST">
                                                <input type="hidden" name="idPf" id="idPf" value="<?= $pf['id'] ?>">
                                                <button type="submit" name="carregar" id="carregar"
                                                        class="btn btn-info btn-block">
                                                    <i class="glyphicon glyphicon-list-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="?perfil=formacao&p=pessoa_fisica&sp=edita"
                                                  method="POST">
                                                <input type="hidden" name="idPf" id="idPf"
                                                       value="<?= $pf['id'] ?>">
                                                <button type="submit" name="carregar" id="carregar"
                                                        class="btn btn-primary btn-block">
                                                    <span class='glyphicon glyphicon-edit'></span></button>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Nome</th>
                                <th>CPF/Passaporte</th>
                                <th>Data nascimento</th>
                                <th>Email</th>
                                <th width="10%">Demais anexos</th>
                                <th width="5%">Editar</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblPf').DataTable({
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

