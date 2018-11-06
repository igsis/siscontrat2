<?php
include "includes/menu_interno.php";

$con = bancoMysqli();
$idEvento = $_SESSION['idEvento'];
$sql = "SELECT id FROM pedidos WHERE origem_tipo_id = '1' AND origem_id = '$idEvento' AND publicado = '1'";
$query = mysqli_query($con,$sql);
$num = mysqli_num_rows($query);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Pedido de Contração</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Escolha um tipo de pessoa</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <?php
                        /*
                         * Caso não haja pedido de contratação registrado
                         */
                        if($num == 0){
                        ?>
                            <div class="row">
                                <div class="form-group col-md-offset-3 col-md-3">
                                    <form method="POST" action="?perfil=pf_cadastro_pesquisa" role="form">
                                        <button type="submit" name = "pessoa_fisica" class="btn btn-block btn-primary btn-lg">Pessoa Física</button>
                                    </form>
                                </div>
                                <div class="form-group col-md-3">
                                    <form method="POST" action="?perfil=pj_cadastro_pesquisa" role="form">
                                        <button type="submit" name = "pessoa_juridica" class="btn btn-block btn-primary btn-lg">Pessoa Jurídica</button>
                                    </form>
                                </div>
                            </div>
                        <?php
                        }
                        else{
                            /*
                             * Caso haja pedido de contratração
                             */
                        ?>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Proponente</th>
                                    <th>Atração</th>
                                    <th>Anexos</th>
                                    <th colspan="2" width="10%">Ação</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <!-- Início da preparação da lista -->
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Proponente</th>
                                    <th>Atração</th>
                                    <th>Anexos</th>
                                    <th colspan="2" width="10%">Ação</th>
                                </tr>
                                </tfoot>
                            </table>
                        <?php
                        }
                        ?>

                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>