<?php
$con = bancoMysqli();

if(isset($_POST['despublica'])){
    $idDados = $_POST['idDados'];
    $sqlDespublica = "UPDATE formacao_contratacoes SET publicado = 0 WHERE id = '$idDados'";
    if(mysqli_query($con,$sqlDespublica)){
       $mensagem = mensagem("success", "Apagado com sucesso!");
    }else{
        $mensagem = mensagem("danger", "Erro ao apagar! Tente novamente.");
    }
}
$sqlDados = "SELECT
            c.id AS 'id',
            c.protocolo AS 'protocolo',
            pf.nome AS 'pessoa',
            c.ano AS 'ano',
            p.programa AS 'programa',
            l.linguagem AS 'linguagem',
            fc.cargo AS 'cargo'
            FROM formacao_contratacoes AS c
            INNER JOIN pessoa_fisicas AS pf ON pf.id = c.pessoa_fisica_id
            INNER JOIN programas AS p ON p.id = c.programa_id
            INNER JOIN linguagens AS l ON l.id = c.linguagem_id
            INNER JOIN formacao_cargos AS fc ON fc.id = c.form_cargo_id
            WHERE c.publicado = 1";
$queryDados = mysqli_query($con,$sqlDados);
?>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Lista de Dados de Contratação</h2>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Listagem</h3>
            </div>
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <div class="box-body">
                    <table id="tblDadosContratacao" class="table table-striped table-responsive">
                        <thead>
                        <tr>
                           <th> Protocolo </th>
                           <th> Pessoa </th>
                           <th> Ano </th>
                           <th> Programa </th>
                           <th> Linguagem </th>
                           <th> Cargo </th>
                           <th> Visualizar </th>
                           <th> Editar </th>
                        </tr>
                        </thead>
                        <?php
                            echo"<tbody>";
                            while($dados = mysqli_fetch_array($queryDados)){
                                echo"<td>" . $dados['protocolo'] . "</td>";
                                echo"<td>" . $dados['pessoa'] . "</td>";
                                echo"<td>" . $dados['ano'] . "</td>";
                                echo"<td>" . $dados['programa'] . "</td>";
                                echo"<td>" . $dados['linguagem'] . "</td>";
                                echo"<td>" . $dados['cargo'] . "</td>";
                                echo"<td>
                                
                            <form method='POST' action='?perfil=formacao&p=dados_contratacao&sp=detalhes' role='form'>
                            <input type='hidden' name='idPCView' value='" . $dados['id'] . "'>
                            <button type='submit' name='carregar' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-eye-open'> </span></button>
                            </form>
                                </td>";
                                echo"<td>
                                
                            <form method='POST' action='?perfil=formacao&p=dados_contratacao&sp=editar' role='form'>
                            <input type='hidden' name='idPCEdit' value='" . $dados['id'] . "'>
                            <button type='submit' name='edit' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-edit'> </span></button>
                            </form>
                                </td>";
                                echo "</tbody>";
                            }?>
                            <tfoot>
                                <tr>
                                    <th> Protocolo </th>
                                    <th> Pessoa </th>
                                    <th> Ano </th>
                                    <th> Programa </th>
                                    <th> Linguagem </th>
                                    <th> Cargo </th>
                                    <th width="5%"> Visualizar </th>
                                    <th width="5%"> Editar </th>
                                </tr>
                            </tfoot>
                    </table>
                <div class="box-footer">
                    <a href="?perfil=formacao">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <a href="?perfil=formacao&p=dados_contratacao&sp=cadastro">
                        <button type="button" class="btn btn-primary pull-right"> Cadastrar </button>
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
        $('#tblDadosContratacao').DataTable({
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