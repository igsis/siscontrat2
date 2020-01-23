<?php
include "includes/menu.php";
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
                                                <td><div class="quad-legenda bg-aqua"> <span> Editado </span></div></td>
                                                <td><div class="quad-legenda bg-fuchsia"><span> Revisado </span></div></td>
                                                <td><div class="quad-legenda bg-green"> <span> Site </span></div></td>
                                                <td><div class="quad-legenda bg-yellow"><span> Impresso </span></div></td>
                                                <td><div class="quad-legenda bg-red"> <span> Foto </span></div></td>
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
                                <th>ID</th>
                                <th>Nome do Evento</th>
                                <th>Enviado por</th>
                                <th>Início/Termino</th>
                                <th>Status</th>
                                <th>Operação</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>TESTE</td>
                                    <td>Qwerty Silva</td>
                                    <td>De 07/03/2020 a 22/03/2020</td>
                                    <td>
                                        <div class="status-comunicacao">
                                            <div class="quadr bg-aqua" data-toggle="popover" data-trigger="hover" data-content="Editado"></div>
                                            <div class="quadr bg-fuchsia" data-toggle="popover" data-trigger="hover" data-content="Revisado"></div>
                                            <div class="quadr bg-green" data-toggle="popover" data-trigger="hover" data-content="Site"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn-info btn">Editar</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
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
    $(document).ready(function(){
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
