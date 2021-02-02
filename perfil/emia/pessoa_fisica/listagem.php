<?php
$con = bancoMysqli();

//só retorna se os nomes tiverem em maiúsculo
$sql = "SELECT id, nome, cpf, passaporte,data_nascimento, email FROM pessoa_fisicas WHERE nome=BINARY UPPER(nome) ";
$query = mysqli_query($con, $sql);

?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2>Edição de pessoa física</h2>
        </div>
        <div class="box box-primary">
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <div class="box-header">
                <h4 class="box-title">Listagem</h4>
            </div>
            <div class="box-body">
                <table id="tblPessoasFisicas" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF/Passaporte</th>
                        <th>Data de Nascimento</th>
                        <th>Email</th>
                        <th width="10%">Demais Anexos</th>
                        <th width="5%">Editar</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($dados = mysqli_fetch_array($query)) {
                    if ($dados['passaporte'] != NULL) {
                        $doc = $dados['passaporte'];
                    } else {
                        $doc = $dados['cpf'];
                    }

                    if($dados['data_nascimento'] == "0000-00-00"){
                        $dataNascimento = "Não cadastrado";
                    }else{
                        $dataNascimento = exibirDataBr($dados['data_nascimento']);
                    }

                    ?>

                    <tr>
                        <td> <?= $dados['nome'] ?> </td>
                        <td> <?= $doc == NULL ? "Não cadastrado" : $doc ?> </td>
                        <td> <?= $dataNascimento ?> </td>
                        <td> <?= $dados['email'] ?> </td>
                        <td>
                            <form action='?perfil=emia&p=pessoa_fisica&sp=demais_anexos' method='POST'>
                                <input type='hidden' name='idPf' id='idPf' value=" <?= $dados['id'] ?> ">
                                <button type='submit' class='btn btn-block btn-info'><span
                                            class='glyphicon glyphicon-list-alt'></span></button>
                            </form>
                        </td>
                        <td>
                            <form action='?perfil=emia&p=pessoa_fisica&sp=edita' method='POST'>
                                <input type='hidden' name='idPf' id='idPf' value="<?= $dados['id'] ?>">
                                <button type='submit' name='carregar' id='carregar' class='btn btn-block btn-primary'>
                                    <span class='glyphicon glyphicon-edit'></span></button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Nome</th>
                        <th>CPF/Passaporte</th>
                        <th>Data de Nascimento</th>
                        <th>Email</th>
                        <th width="10%">Demais Anexos</th>
                        <th width="5%">Editar</th>
                    </tr>
                    </tfoot>
                </table>
                <div class="box-footer">
                    <a href="?perfil=emia">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <a href="?perfil=emia&p=pessoa_fisica&sp=pesquisa">
                        <button type="button" class="btn btn-primary pull-right"> Cadastrar uma nova pessoa física
                        </button>
                    </a>
                </div>
            </div>
        </div>

    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblPessoasFisicas').DataTable({
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
