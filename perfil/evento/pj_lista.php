<?php

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);
include "includes/menu_pj.php";


$con = bancoMysqli();

$sql = "SELECT * FROM pessoa_juridicas";
$query = mysqli_query($con,$sql);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Pessoas Jurídicas</h2>

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
                                <th width="50%">Razão Social</th>
                                <th>CNPJ</th>
                                <th>Última atualização</th>
                                <th colspan="2">Ações</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($pj = mysqli_fetch_array($query)){
                                echo "<tr>";
                                echo "<td>".$pj['razao_social']."</td>";
                                echo "<td>".$pj['cnpj']."</td>";
                                echo "<td>".exibirDataBr(retornaDataSemHora($pj['ultima_atualizacao']))."</td>";
                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento&p=pj_edita\" role=\"form\">
                                    <input type='hidden' name='idPessoaJuridica' value='".$pj['id']."'>
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
                                <th>Razão Social</th>
                                <th>CNPJ</th>
                                <th>Última atualização</th>
                                <th colspan="2">Ações</th>
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