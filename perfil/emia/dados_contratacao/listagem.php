<?php
$con = bancoMysqli();

if (isset($_POST['cadastrar'])) {
    $idPf = $_POST['pf'];
    $ano = $_POST['ano'];
    $local = $_POST['local'];
    $cargo = $_POST['cargo'];
    $vigencia = $_POST['vigencia'];
    $cronograma = $_POST['cronograma'];
    $obs = $_POST['observacao'];
    $status = "1";
    $fiscal = $_POST['fiscal'];
    $suplente = $_POST['suplente'];
    $usuario = $_SESSION['idUser'];
    $data = date("Y-m-d H:i:s", strtotime("-3 hours"));

    $sqlInsert = "INSERT INTO emia_contratacao
                                    (pessoa_fisica_id, 
                                     ano, 
                                     emia_status_id, 
                                     local_id, 
                                     emia_cargo_id, 
                                     emia_vigencia_id, 
                                     cronograma, 
                                     observacao, 
                                     fiscal_id, 
                                     suplente_id, 
                                     usuario_id, 
                                     data_envio)
                                     VALUES
                                        ('$idPf',
                                         '$ano',
                                         '$status',
                                         '$local',
                                         '$cargo',
                                         '$vigencia',
                                         '$cronograma',
                                         '$obs',
                                         '$fiscal',
                                         '$suplente',
                                         '$usuario',
                                         '$data') ";
    if (mysqli_query($con, $sqlInsert)) {
        $mensagem = mensagem('success', 'Cadastrado com Sucesso!');
        $idContrat = recuperaUltimo('emia_contratacao');
        $protocolo = geraProtocolo($idContrat);
        $sqlProtocolo = "UPDATE emia_contratacao SET protocolo = '$protocolo' WHERE id = '$idContrat'";
        $queryProtocolo = mysqli_query($con,$sqlProtocolo);
    } else {
        $mensagem = mensagem('danger', 'Erro ao Cadastrar! Tente novamente.');
    }
}

if(isset($_POST['despublica'])){
    $idDados = $_POST['idDados'];
    $sqlDespublica = "UPDATE emia_contratacao SET publicado = 0 WHERE id = '$idDados'";
    if(mysqli_query($con,$sqlDespublica)){
        $mensagem = mensagem("success", "Excluido com Sucesso!");
    }else{
        $mensagem = mensagem("danger", "Erro ao excluir! Tente novamente.");
    }
}

$sqlSelect = "SELECT ec.id AS 'id',
		ec.protocolo AS 'protocolo',
        p.nome AS 'pessoa_fisica',
        ec.ano AS 'ano',
        c.cargo AS 'cargo'
        FROM emia_contratacao AS ec
        INNER JOIN pessoa_fisicas AS p ON p.id = ec.pessoa_fisica_id
        INNER JOIN emia_cargos AS c ON c.id = ec.emia_cargo_id
        WHERE ec.publicado = 1 ";
$querySelect = mysqli_query($con, $sqlSelect);
?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">EMIA</h2>
        </div>
        <div class="box box-primary">
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <div class="box-header">
                <h4 class="box-title">Listagem de dados para contratação</h4>
            </div>
            <div class="box-body">
                <table id="tblEmiaContratacoes" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Protocolo</th>
                        <th>Pessoa</th>
                        <th>Ano</th>
                        <th>Cargo</th>
                        <th>Visualizar</th>
                        <th>Editar</th>
                    </tr>
                    </thead>
                    <?php
                    while ($dados = mysqli_fetch_array($querySelect)) {
                        echo "<tbody>";
                        echo "<td>" . $dados['protocolo'] . "</td>";
                        echo "<td>" . $dados['pessoa_fisica'] . "</td>";
                        echo "<td>" . $dados['ano'] . "</td>";
                        echo "<td>" . $dados['cargo'] . "</td>";

                        echo "<td>
                                
                            <form method='POST' action='?perfil=emia&p=dados_contratacao&sp=detalhes' role='form'>
                            <input type='hidden' name='idECView' value='" . $dados['id'] . "'>
                            <button type='submit' name='carregar' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-eye-open'> </span></button>
                            </form>
                                </td>";
                        echo "<td>
                                
                            <form method='POST' action='?perfil=emia&p=dados_contratacao&sp=edita' role='form'>
                            <input type='hidden' name='idECEdit' value='" . $dados['id'] . "'>
                            <button type='submit' name='edit' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-edit'> </span></button>
                            </form>
                                </td>";

                        echo "</tbody>";
                    }
                    ?>
                    <tfoot>
                    <tr>
                        <th>Protocolo</th>
                        <th>Pessoa</th>
                        <th>Ano</th>
                        <th>Cargo</th>
                        <th>Visualizar</th>
                        <th>Editar</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-footer">
                <a href="?perfil=emia">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
                <a href="?perfil=emia&p=dados_contratacao&sp=cadastra">
                    <button type="button" class="btn btn-primary pull-right"> Cadastrar</button>
                </a>
            </div>
        </div>
    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblEmiaContratacoes').DataTable({
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