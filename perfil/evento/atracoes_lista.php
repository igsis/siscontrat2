<?php
include "includes/menu_interno.php";

$con = bancoMysqli();

$idEvento = $_SESSION['idEvento'];
$sql = "SELECT at.id AS idAtracao, nome_atracao, a2.categoria_atracao,produtor_id FROM atracoes AS at
        INNER JOIN atracao_eventos a on at.id = a.atracao_id
        INNER JOIN categoria_atracoes a2 on at.categoria_atracacao_id = a2.id
        WHERE publicado = 1 AND a.evento_id = '$idEvento'";
$query = mysqli_query($con,$sql);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Atrações</h2>
        <div class="row">
            <div class="col-md-2">
                <a href="?perfil=evento&p=atracoes_cadastro">
                    <button type="button" class="btn btn-block btn-info"><i class="fa fa-plus"></i> Adiciona</button>
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
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nome da atração</th>
                                <th>Categoria da atração</th>
                                <th>Produtor</th>
                                <th colspan="2" width="10%">Ação</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($atracao = mysqli_fetch_array($query)){

                                echo "<tr>";
                                echo "<td>".$atracao['nome_atracao']."</td>";
                                echo "<td>".$atracao['categoria_atracao']."</td>";
                                if($atracao['produtor_id'] > 0){
                                    $idProdutor = $atracao['produtor_id'];
                                    $sql_produtor = "SELECT nome FROM produtores WHERE id = '$idProdutor'";
                                    $query_produtor = mysqli_query($con,$sql_produtor);
                                    $produtor = mysqli_fetch_array($query_produtor);
                                    echo "<td>
                                              <form method=\"POST\" action=\"?perfil=evento&p=produtor_edita\" role=\"form\">
                                        <input type='hidden' name='idAtracao' value='".$atracao['idAtracao']."'>
                                        <button type=\"submit\" name='carregar' class=\"btn btn-primary\"><i class=\"fa fa-pencil-square-o\"></i></button>
                                        ".$produtor['nome']."</form>
                                        </td>";
                                }
                                else{
                                    echo "<td>
                                        <form method=\"POST\" action=\"?perfil=evento&p=produtor_cadastra\" role=\"form\">
                                        <input type='hidden' name='idAtracao' value='".$atracao['idAtracao']."'>
                                        <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><i class=\"fa fa-plus\"></i> Produtor</button>
                                        </form>
                                    </td>";
                                }
                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento&p=atracoes_edita\" role=\"form\">
                                    <input type='hidden' name='idAtracao' value='".$atracao['idAtracao']."'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\">Carregar</button>
                                    </form>
                                </td>";
                                echo "<td>
                                    <button type=\"button\" class=\"btn btn-block btn-danger\">Apagar</button>
                                  </td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Nome da atração</th>
                                <th>Categoria da atração</th>
                                <th>Produtor</th>
                                <th colspan="2" width="10%">Ação</th>
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
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

    </section>
    <!-- /.content -->
</div>