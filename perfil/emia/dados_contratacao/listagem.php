<?php
$con = bancoMysqli();

if(isset($_POST['deletar'])){
    $idContrat = $_POST['idContratacao'];
    $sqlDespublica = "UPDATE emia_contratacao SET publicado = 0 WHERE id = '{$idContrat}'";
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
                        <th width="5%">Visualizar</th>
                        <th width="5%">Editar</th>
                        <th width="5%">Apagar</th>
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
                        echo "<td>
                                  <button type='button' name='apagar' id='apaga' data-target='#modalExclusao' data-toggle='modal' data-id='" . $dados['id'] . "' class='btn btn-block btn-danger'>
                                  <span class='glyphicon glyphicon-trash'></span>
                                  </button>
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
                        <th width="5%">Visualizar</th>
                        <th width="5%">Editar</th>
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
<div id="modalExclusao" class="modal modal-danger modal fade in" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmação de Exclusão</h4>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir a contratação?</p>
            </div>
            <div class="modal-footer">
                <form action="?perfil=emia&p=dados_contratacao&sp=listagem" method="POST">
                    <input type="hidden" name="idContratacao" id="idContratacao" value="">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                    </button>
                    <input type="submit" class="btn btn-danger btn-outline" name="deletar" value="Excluir">
                </form>
            </div>
        </div>
    </div>
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

    $('#modalExclusao').on('show.bs.modal', function (e) {
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('#idContratacao').attr('value', `${id}`);
    })
</script>