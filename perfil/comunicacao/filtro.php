<?php
include "includes/menu.php";
include "includes/funcoesAuxiliares.php";

$con = bancoMysqli();
$conn = bancoPDO();

$idUser = $_SESSION['usuario_id_s'];
$filtro = null;

if (isset($_POST['_filtros'])) {
    $filtro = [];
    foreach ($_POST as $key => $value) {
        switch ($key) {
            case 'editado':
                $filtro[$key] = $value;
                break;
            case 'revisado':
                $filtro[$key] = $value;
                break;
            case 'site':
                $filtro[$key] = $value;
                break;
            case 'impresso':
                $filtro[$key] = $value;
                break;
            case 'foto':
                $filtro[$key] = $value;
                break;
        }
    }

    $query = retornaEventosComunicacao($idUser, 'eventos', $filtro);
    $query2 = retornaEventosComunicacao($idUser, 'agendoes', $filtro);
} else {
    $query = retornaEventosComunicacao($idUser, 'eventos');
    $query2 = retornaEventosComunicacao($idUser, 'agendoes');
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <h2 class="page-header">Filtro de Evento</h2>
        <?= var_dump($filtro) ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Filtrar por:</h3>
                    </div>
                    <div id="caixa-filtro" class="row">
                        <div class="col-md-11 col-md-offset-1 margin-top-20">
                            <form action="?perfil=comunicacao&p=filtro" method="POST">
                                <div class="row">
                                    <div class="filtros">
                                        <div id="editado" class="topico-filtro col-md-2">
                                            <span id="titulo-filtro">Editado</span>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="editado"
                                                       id="editadoC" value="1" <?php if (isset($filtro['editado'])) {
                                                    echo ($filtro['editado']) ? 'checked' : '';
                                                } ?>>
                                                <label class="form-check-label" for="editadoC">
                                                    Confirmado
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="editado"
                                                       id="editadoP" value="0" <?php if (isset($filtro['editado'])) {
                                                    echo !($filtro['editado']) ? 'checked' : '';
                                                } ?>>
                                                <label class="form-check-label" for="editadoP">
                                                    Pendente
                                                </label>
                                            </div>
                                        </div>
                                        <div id="revisado" class="topico-filtro col-md-2">
                                            <span id="titulo-filtro">Revisado</span>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="revisado"
                                                       id="revisadoC" value="1" <?php if (isset($filtro['revisado'])) {
                                                    echo ($filtro['revisado']) ? 'checked' : '';
                                                } ?>>
                                                <label class="form-check-label" for="revisadoC">
                                                    Confirmado
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="revisado"
                                                       id="revisadoP" value="0" <?php if (isset($filtro['revisado'])) {
                                                    echo !($filtro['revisado']) ? 'checked' : '';
                                                } ?>>
                                                <label class="form-check-label" for="revisadoP">
                                                    Pendente
                                                </label>
                                            </div>
                                        </div>
                                        <div id="site" class="topico-filtro col-md-2">
                                            <span id="titulo-filtro">Site</span>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="site" id="siteC"
                                                       value="1" <?php if (isset($filtro['site'])) {
                                                    echo ($filtro['site']) ? 'checked' : '';
                                                } ?>>
                                                <label class="form-check-label" for="siteC">
                                                    Confirmado
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="site" id="siteP"
                                                       value="0" <?php if (isset($filtro['site'])) {
                                                    echo !($filtro['site']) ? 'checked' : '';
                                                } ?>>
                                                <label class="form-check-label" for="siteP">
                                                    Pendente
                                                </label>
                                            </div>
                                        </div>
                                        <div id="impresso" class="topico-filtro col-md-2">
                                            <span id="titulo-filtro">Impresso</span>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="impresso"
                                                       id="impressoC" value="1" <?php if (isset($filtro['impresso'])) {
                                                    echo ($filtro['impresso']) ? 'checked' : '';
                                                } ?>>
                                                <label class="form-check-label" for="impressoC">
                                                    Confirmado
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="impresso"
                                                       id="impressoP" value="0" <?php if (isset($filtro['impresso'])) {
                                                    echo !($filtro['impresso']) ? 'checked' : '';
                                                } ?>>
                                                <label class="form-check-label" for="impressoP">
                                                    Pendente
                                                </label>
                                            </div>
                                        </div>
                                        <div id="foto" class="topico-filtro col-md-2">
                                            <span id="titulo-filtro">Foto</span>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="foto" id="fotoC"
                                                       value="1" <?php if (isset($filtro['foto'])) {
                                                    echo ($filtro['foto']) ? 'checked' : '';
                                                } ?>>
                                                <label class="form-check-label" for="fotoC">
                                                    Confirmado
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="foto" id="fotoP"
                                                       value="0" <?php if (isset($filtro['foto'])) {
                                                    echo !($filtro['foto']) ? 'checked' : '';
                                                } ?>>
                                                <label class="form-check-label" for="fotoP">
                                                    Pendente
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row margin-top-20">
                                    <div class="col-md-10">
                                        <button type="submit" name="_filtros" class="btn btn-primary btn-lg btn-block">
                                            Filtrar
                                        </button>
                                    </div>
                                </div>
                            </form>
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
                                                    <div class="quad-legenda bg-fuchsia"><span> Revisado </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="quad-legenda bg-green"><span> Site </span></div>
                                                </td>
                                                <td>
                                                    <div class="quad-legenda bg-yellow"><span> Impresso </span>
                                                    </div>
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
                                if (aplicarFiltro($evento['idEvento'], $filtro)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $evento['nome_evento'] ?>
                                        </td>
                                        <td>
                                            <?= $evento['nome_usuario'] ?>
                                        </td>
                                        <td>
                                            <?= retornaPeriodoNovo($evento['idEvento'], 'ocorrencias'); ?>
                                        </td>
                                        <td>
                                            <div class="status-comunicacao">
                                                <?php geraLegendas($evento['idEvento'], 'eventos', 'comunicacoes'); ?>
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
                                            <?php geraLegendas($agendao['idEvento'], 'agendoes', 'comunicacao_agendoes'); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <form method="post" action="?perfil=comunicacao&p=comunicacao">
                                            <input type="hidden" name="agendao" value="<?= $agendao['idEvento'] ?>">
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


    let filtros = document.querySelector('.filtros');

    // console.log(filtros);
</script>
<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
