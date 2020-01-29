<?php
include "includes/menu.php";
include "includes/funcoesAuxiliares.php";

$con = bancoMysqli();
$conn = bancoPDO();

$idUser = $_SESSION['idUser'];

$sqlSis = "SELECT       eve.id AS idEvento, 
                        eve.nome_evento AS nome_evento, 
                        es.status AS status, 
                        u.nome_completo AS nome_usuario
        FROM eventos as eve
        LEFT JOIN usuarios u ON eve.usuario_id = u.id
        INNER JOIN evento_status es on eve.evento_status_id = es.id
        WHERE eve.publicado = 1 AND evento_status_id between 3 AND 4 AND
        (suplente_id = '$idUser' OR fiscal_id = '$idUser' OR usuario_id = '$idUser')";

$query = mysqli_query($con,$sqlSis);

$sqlAg = "SELECT 	ag.id AS idEvento, 
                    ag.nome_evento AS nome_evento, 
                    es.status AS status, 
                    us.nome_completo AS nome_usuario
        FROM agendoes AS ag
        LEFT JOIN usuarios us ON ag.usuario_id = us.id
        INNER JOIN evento_status es ON ag.evento_status_id = es.id
                WHERE ag.publicado = 1 AND evento_status_id between 3 AND 4 AND ag.usuario_id = '$idUser'";

$query2 = mysqli_query($con, $sqlAg);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <h2 class="page-header">Filtro de Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Filtrar por:</h3>
                    </div>
                    <div id="caixa-filtro" class="row">
                        <div class="col-md-11 col-md-offset-1 margin-top-20">
                            <div class="row">
                                <div id="topico-filtro" class="col-md-2">
                                    <span id="titulo-filtro">Editado</span>
                                    <div class="lateral">
                                        <label>
                                            <input type="checkbox" value="1">
                                            Confirmado
                                        </label>
                                        <label>
                                            <input type="checkbox" value="2">
                                            Pendente
                                        </label>
                                    </div>
                                </div>
                                <div id="topico-filtro" class="col-md-2">
                                    <span id="titulo-filtro">Revisado</span>
                                    <div class="lateral">
                                        <label>
                                            <input type="checkbox" value="3">
                                            Confirmado
                                        </label>
                                        <label>
                                            <input type="checkbox" value="4">
                                            Pendente
                                        </label>
                                    </div>
                                </div>
                                <div id="topico-filtro" class="col-md-2">
                                    <span id="titulo-filtro">Site</span>
                                    <div class="lateral">
                                        <label>
                                            <input type="checkbox" value="5">
                                            Confirmado
                                        </label>
                                        <label>
                                            <input type="checkbox" value="6">
                                            Pendente
                                        </label>
                                    </div>
                                </div>
                                <div id="topico-filtro" class="col-md-2">
                                    <span id="titulo-filtro">Impresso</span>
                                    <div class="lateral">
                                        <label>
                                            <input type="checkbox" value="7">
                                            Confirmado
                                        </label>
                                        <label>
                                            <input type="checkbox" value="8">
                                            Pendente
                                        </label>
                                    </div>
                                </div>
                                <div id="topico-filtro" class="col-md-2">
                                    <span id="titulo-filtro">Foto</span>
                                    <div class="lateral">
                                        <label>
                                            <input type="checkbox" value="9">
                                            Confirmado
                                        </label>
                                        <label>
                                            <input type="checkbox" value="10">
                                            Pendente
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-top-20">
                                <div class="col-md-10">
                                    <button type="button" class="btn btn-primary btn-lg btn-block">Filtrar</button>
                                </div>
                            </div>
                            <div class="row">
                                <div id="legenda" class="col-md-10 margin-top-20">
                                    <div class="panel panel-default">
                                        <!-- Default panel contents -->
                                        <div class="panel-heading">Legendas</div>
                                        <table class="table">
                                            <tbody id="legendas-tbody">
                                            <tr>
                                                <td>
                                                    <div class="quad-legenda bg-aqua"><span> Editado </span></div>
                                                </td>
                                                <td>
                                                    <div class="quad-legenda bg-fuchsia"><span> Revisado </span></div>
                                                </td>
                                                <td>
                                                    <div class="quad-legenda bg-green"><span> Site </span></div>
                                                </td>
                                                <td>
                                                    <div class="quad-legenda bg-yellow"><span> Impresso </span></div>
                                                </td>
                                                <td>
                                                    <div class="quad-legenda bg-red"><span> Foto </span></div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblEvento" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nome do Evento</th>
                                <th>Enviado por</th>
                                <th>Início/Termino</th>
                                <th>Status</th>
                                <th>Operação</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($evento = mysqli_fetch_array($query)) {
                                ?>
                                <tr>
                                    <td>
                                        <?= $evento['nome_evento'] ?>
                                    </td>
                                    <td>
                                        <?= $evento['nome_usuario'] ?>
                                    </td>
                                    <td>
                                        <?=
                                            retornaPeriodo($evento['idEvento']);
                                        ?>
                                    </td>
                                    <td>
                                        <div class="status-comunicacao">
                                            <?php geraLegendas($evento['idEvento'],'eventos','comunicacoes'); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <form method="post" action="?perfil=comunicacao&p=comunicacao">
                                            <input type="hidden" name="evento" value="<?= $evento['idEvento'] ?>">
                                            <button class="btn-info btn">Editar</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            <?php
                            while ($agendao = mysqli_fetch_array($query2)) {
                                ?>
                                <tr>
                                    <td>
                                        <?= $agendao['nome_evento'] ?>
                                    </td>
                                    <td>
                                        <?= $agendao['nome_usuario'] ?>
                                    </td>
                                    <td>
                                        <?=
                                        retornaPeriodo($agendao['idEvento']);
                                        ?>
                                    </td>
                                    <td>
                                        <div class="status-comunicacao">
                                            <?php geraLegendas($agendao['idEvento'],'agendoes','comunicacao_agendao'); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <form method="post" action="?perfil=comunicacao&p=comunicacao">
                                            <input type="hidden" name="evento" value="<?= $agendao['idEvento'] ?>">
                                            <button class="btn-info btn">Editar</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Nome do Evento</th>
                                <th>Enviado por</th>
                                <th>Início/Termino</th>
                                <th>Status</th>
                                <th>Operação</th>
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
    </section>
    <!-- /.content -->
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover();
    });

    $(function () {
        $('#tblEvento').DataTable({
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
<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
