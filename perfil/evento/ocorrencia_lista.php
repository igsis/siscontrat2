<?php
include "includes/menu_interno.php";

$con = bancoMysqli();

$idAtracao = $_POST['idAtracao'];
$sql = "SELECT * FROM ocorrencias o
        INNER JOIN locais l ON o.local_id = l.id
        WHERE o.origem_ocorrencia_id = '$idAtracao' ";

$query = mysqli_query($con,$sql);
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Ocorrências</h2>
        <div class="row">
            <div class="col-md-2">
                <form method="POST" action="?perfil=evento&p=ocorrencia_cadastro">
                    <input type="hidden" name="idAtracao" value="<?= $idAtracao ?>">
                    <button type="submit" class="btn btn-block btn-info"><i class="fa fa-plus"></i> Adiciona</button>
                </form>
            </div>
        </div>
        <br/>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>

                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Data início</th>
                                <th>Horario início</th>
                                <th>Horario final</th>
                                <th>Local</th>
                                <th colspan="2" width="10%">Ação</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($ocorrencia = mysqli_fetch_array($query)){

                                echo "<tr>";
                                echo "<td>".exibirDataBr($ocorrencia['data_inicio'])."</td>";
                                echo "<td>".exibirHora($ocorrencia['horario_inicio'])."</td>";
                                echo "<td>".exibirHora($ocorrencia['horario_fim'])."</td>";
                                echo "<td>".$ocorrencia['local']."</td>";


                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento&p=ocorrencia_edita\" role=\"form\">
                                    <input type='hidden' name='idOcorrencia' value='".$ocorrencia['id']."'>
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
                                <th>Data início</th>
                                <th>Horario início</th>
                                <th>Horario final</th>
                                <th>Local</th>
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