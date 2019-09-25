<?php
$con = bancoMysqli();
$sql = "SELECT id, nome, cpf, data_nascimento, email FROM pessoa_fisicas";
$query = mysqli_query($con, $sql);

?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2>Contratos</h2>
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
                <table id="tblPF" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Data de Nascimento</th>
                        <th>Email</th>
                        <th>Visualizar</th>
                    </tr>
                    </thead>
                    <?php
                    echo "<tbody>";
                    while ($dados = mysqli_fetch_array($query)) {
                        echo "<tr>";
                        echo "<td>" . $dados['nome'] . "</td>";
                        echo "<td>" . $dados['cpf'] . "</td>";
                        echo "<td>" . exibirDataBr($dados['data_nascimento']) . "</td>";
                        echo "<td>" . $dados['email'] . "</td>";
                        echo "<td>
                        <form action='?perfil=contrato&p=pf&sp=edita' method='POST'>
                        <input type='hidden' name='idPf' id='idPf' value='" . $dados['id'] . "'>
                        <button type='submit' name='carregar' id='carregar'  class='btn btn-block btn-primary'><span class='glyphicon glyphicon-edit'></span></button>
                        </form>
                        </td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    ?>
                    <tfoot>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Data de Nascimento</th>
                        <th>Email</th>
                        <th>Visualizar</th>
                    </tr>
                    </tfoot>
                </table>
                <div class="box-footer">
                    <a href="?perfil=contrato">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <a href="?perfil=contrato&p=pf&sp=pesquisa">
                        <button type="button" class="btn btn-primary pull-right"> Cadastrar uma nova pessoa física</button>
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
        $('#tblPF').DataTable({
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

