<?php
include "includes/menu_principal.php";
$con = bancoCapac();

$idCapac = $_POST['idCapac'] ?? null;
$nomeEvento = $_POST['nome'] ?? null;
$publico = $_POST['publico'] ?? null;

$sqlIdCapac = '';
$sqlNomeEvento = '';
$sqlPublico = '';

if($idCapac != null){
    $sqlIdCapac = " AND e.id = '$idCapac'";
}
if($nomeEvento != null){
    $sqlNomeEvento = " AND e.nome_evento = '$nomeEvento'";
}
if($publico != null){
    $sqlPublico = " AND ep.publico_id = '$publico'";
}

$sql = "SELECT e.id, e.nome_evento, e.data_cadastro, p.publico FROM capac_new.eventos e 
    INNER JOIN capac_new.evento_publico ep ON ep.evento_id = e.id
    INNER JOIN capac_new.publicos p ON p.id = ep.publico_id
    WHERE e.publicado = 2 $sqlIdCapac $sqlNomeEvento $sqlPublico";
echo $sql;
$query = mysqli_query($con, $sql);
$numRows = mysqli_num_rows($query);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Resultado</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Eventos do CAPAC</h3>
                    </div>
                    <div class="box-body">
                    <table id="tblCapac" class="table table-bordered table-striped">
                        <thead>
                        <th>Código</th>
                        <th>Nome do Evento</th>
                        <th>Data do cadastro</th>
                        <th>Representatividade</th>
                        <th>Abrir</th>
                        </thead>

                        <tbody>
                        <?php
                        if($numRows == 0){
                            ?>
                            <td colspan="5"><p align="center"><b>Não foram encontrados registros</b></p></td>
                        <?php
                        }else{
                            while ($evento = mysqli_fetch_array($query)){
                                ?>
                                <td><?= $evento['id'] ?></td>
                                <td><?= $evento['nome_evento'] ?></td>
                                <td><?= exibirDataHoraBr($evento['data_cadastro']) ?></td>
                                <td><?= $evento['publico'] ?></td>
                                <td>

                                    <form action="?perfil=evento&p=resumo_capac" method="POST">
                                        <input type="hidden" id="idCapac" name="idCapac" value="<?= $evento['id'] ?>">
                                        <button type="submit" name="buscar" id="buscar" class="btn btn-block btn-info">
                                            <span class="glyphicon glyphicon-folder-open"></span>
                                        </button>
                                    </form>
                                </td>
                                <?php
                            }
                            ?>

                        <?php
                        }
                        ?>

                        </tbody>

                        <tfoot>
                        <th>Código</th>
                        <th>Nome do Evento</th>
                        <th>Data do cadastro</th>
                        <th>Representatividade</th>
                        <th>Abrir</th>
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
        $('#tblCapac').DataTable({
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