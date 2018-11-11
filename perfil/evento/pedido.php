<?php
include "includes/menu_interno.php";

$con = bancoMysqli();
$idEvento = $_SESSION['idEvento'];
$sql = "SELECT * FROM pedidos WHERE origem_tipo_id = '1' AND origem_id = '$idEvento' AND publicado = '1'";
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
                                    <form method="POST" action="?perfil=evento&p=pf_cadastro_pesquisa" role="form">
                                        <button type="submit" name = "pessoa_fisica" class="btn btn-block btn-primary btn-lg">Pessoa Física</button>
                                    </form>
                                </div>
                                <div class="form-group col-md-3">
                                    <form method="POST" action="?perfil=evento&p=pj_pesquisa" role="form">
                                        <button type="submit" name = "pesquisar_pessoa_juridica" class="btn btn-block btn-primary btn-lg">Pessoa Jurídica</button>
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
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Proponente</th>
                                    <th>Atração</th>
                                    <th width="15%">Anexos</th>
                                    <th width="15%">Ação</th>
                                </tr>
                                </thead>
                                <?php
                                echo "<body>";
                                while ($pedido = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    echo "<td>" . $pedido['id'] . "</td>";
                                    if($pedido['pessoa_tipo_id'] == 2){
                                        $pj = recuperaDados("pessoa_juridicas","id",$pedido['pessoa_juridica_id']);
                                        echo "<td>".$pj['razao_social']."</td>";
                                    }
                                    else{
                                        $pf = recuperaDados("pessoa_fisicas","id",$pedido['pessoa_fisica_id']);
                                        echo "<td>".$pf['nome']."</td>";
                                    }
                                    echo "<td>ATRAÇÃO AQUI</td>";
                                    echo "<td>
                                        <form method=\"POST\" action=\"?perfil=evento&p=pedido_anexo\" role=\"form\">
                                        <input type='hidden' name='idPedido' value='".$pedido['id']."'>
                                        <button type=\"submit\" name='carregar' class=\"btn btn-primary btn-block\">Anexos do pedido</button>
                                        </form>
                                        </td>";
                                    echo "<td>
                                        <form method=\"POST\" action=\"?perfil=evento&p=pedido_edita\" role=\"form\">
                                        <input type='hidden' name='idPedido' value='".$pedido['id']."'>
                                        <button type=\"submit\" name='carregar' class=\"btn btn-primary btn-block\">Editar pedido</button>
                                        </form>
                                        </td>";
                                    echo "</tr>";
                                }
                                echo "</body>";
                                ?>
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