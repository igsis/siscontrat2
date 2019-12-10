<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

$idEvento = $_SESSION['idEvento'];

$evento = recuperaDados('eventos', 'id', $idEvento);

if (isset($_POST['apagar'])) {

    $idApagar = $_POST['idApagar'];

    $sqlDelete = "DELETE FROM filme_eventos WHERE id = '$idApagar'";

    if ($query = mysqli_query($con, $sqlDelete)) {
        $mensagem = mensagem("success", "Filme deletado com sucesso");

        $deletaOcorrenciasFilme = "UPDATE ocorrencias SET publicado = 0 WHERE atracao_id = '$idApagar' AND tipo_ocorrencia_id = 2";
        mysqli_query($con, $deletaOcorrenciasFilme);
    } else {
        $mensagem = mensagem("danger", "Erro ao tentar executar operação na atração");
    }
}

if (isset($_POST['selecionar'])) {
    $idFilme = $_POST['idFilme'];

    $sql = "INSERT INTO filme_eventos (filme_id, evento_id)
    VALUES ('$idFilme','$idEvento')";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Filme adicionado a lista!");
    } else {
        $mensagem = mensagem("danger", die(mysqli_error($con)));
    }

}

$query = "SELECT f.id, f.titulo, f.ano_producao, f.duracao, f.direcao, fe.id AS 'idFilmeEvento'
FROM filmes f
INNER JOIN filme_eventos fe ON fe.filme_id = f.id
WHERE f.publicado = 1 AND fe.evento_id='$idEvento'";

$resul = mysqli_query($con, $query);
?>

<div class="content-wrapper">

    <section class="content">

        <h2 class="page-header">Cinema</h2>
        <div class="row">
            <div class="col-md-2">
                <a href="?perfil=evento&p=evento_cinema_procura">
                    <button type="button" class="btn btn-block btn-info"><i class="fa fa-plus"></i> Adicionar</button>
                </a>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>
                    <div class="box-body">

                        <table id="tblFilmes" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Filme</th>
                                <th width="5%">Ano</th>
                                <th>Duração</th>
                                <th>Diretor</th>
                                <th width="15%">Ocorrência</th>
                                <th width="10%">Visualizar</th>
                                <th width="7%">Apagar</th>
                            </tr>
                            </thead>
                            <?php
                            echo "<tbody>";
                            while ($filmes = mysqli_fetch_assoc($resul)) {
                                echo "<tr>";
                                echo "<td>" . $filmes['titulo'] . "</td>";
                                echo "<td>" . $filmes['ano_producao'] . "</td>";
                                echo "<td>" . $filmes['duracao'] . "</td>";
                                echo "<td>" . $filmes['direcao'] . "</td>";

                                // OCORRENCIAS
                                // se no 'campo' nao for 'atracao_id' é origem_ocorrencia_id
                                $ocorrencias = recuperaOcorrenciaDados('ocorrencias', 'atracao_id', $filmes['idFilmeEvento'], $evento['tipo_evento_id']);

                                if ($ocorrencias > 0) {

                                    echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento&p=ocorrencia_lista\" role=\"form\">
                                    <input type='hidden' name='idOrigem' value='" . $filmes['id'] . "'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-primary\"><i class=\"fa fa-pencil-square-o\"></i> Listar ocorrência</button>
                                    </form>
                                    </td>";
                                } else {

                                    echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento&p=ocorrencia_cadastro\" role=\"form\">
                                    <input type='hidden' name='idOrigem' value='" . $filmes['idFilmeEvento'] . "'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><i class=\"fa fa-plus\"></i> Ocorrência</button>
                                    </form>
                                    </td>";
                                }
                                echo "<td>
                                <form method=\"POST\" action=\"?perfil=evento&p=evento_cinema_edita\" role=\"form\">
                                <input type='hidden' name='idFilme' value='" . $filmes['id'] . "''>
                                <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><span class='glyphicon glyphicon-eye-open'></span></button>
                                </form>
                                </td>";
                                echo "<td>
                                <buttonn class='btn btn-block btn-danger' data-toggle='modal' data-target='#apagar' 
                                    data-ocorrencia-id='" . $filmes['id'] . "' data-tittle='Apagar Filme' 
                                    data-message='Deseja mesmo excluir o filme do evento?' 
                                    onclick ='passarId(" . $filmes['idFilmeEvento'] . ")'>
                                    <span class='glyphicon glyphicon-trash'></span></buttonn>
                                </td>";
                                echo "</tr>";
                            }

                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Filme</th>
                                <th>Ano</th>
                                <th>Duração</th>
                                <th>Diretor</th>
                                <th>Ocorrência</th>
                                <th>Visualizar</th>
                                <th>Apagar</th>
                            </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<!--Apagar filme do evento-->
<div class="modal fade" id="apagar" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id='formApagar' action="?perfil=evento&p=evento_cinema_lista" class="form-horizontal"
                  role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><p>Apagar filme</p></h4>
                </div>
                <div class="modal-body">
                    <p>Deseja mesmo excluir o filme do evento? </p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="idApagar">
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
    $(function () {
        $('#tblFilmes').DataTable({
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

<script>

    function passarId(id) {
        document.querySelector('#formApagar input[name="idApagar"]').value = id;
    }
</script>