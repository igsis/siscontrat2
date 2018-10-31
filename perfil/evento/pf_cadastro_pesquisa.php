<?php

$con = bancoMysqli();
include "includes/menu_interno.php";

$exibir = ' ';
$resultado = "<td></td>";
$idEvento = $_SESSION['idEvento'];
$procurar = NULL;


if (isset($_POST['procurar'])){
    $procurar = $_POST['procurar'];
    if ($procurar != NULL) {

        $sql = "SELECT COUNT(cpf) 'qtd'
                FROM   `pessoa_fisicas`
                WHERE cpf = '$procurar' OR passaporte = '$procurar'";
//        $query = mysqli_query($con, $sql);
        $pessoa = mysqli_fetch_assoc(mysqli_query($con, $sql));

        if ($pessoa['qtd'] > 0) {
            $exibir = true;

            $sql = "SELECT  id, nome, cpf, email
                    FROM `pessoa_fisicas`
                    WHERE cpf = '$procurar' OR passaporte = '$procurar'";
            $query = mysqli_query($con,$sql);

            $resultado = "";

            while($pessoa = mysqli_fetch_array($query)){
                $resultado .= "<tr>";
                $resultado .= "<td>".$pessoa['nome']."</td>";
                $resultado .= "<td>".$pessoa['cpf']."</td>";
                $resultado .= "<td>".$pessoa['email']."</td>";
                $resultado .= "<td>
                                     <form action='?perfil=evento&p=pf_cadastro_pesquisa' method='post'>
                                        <input type='hidden' name='idPessoa' value='".$pessoa['id']."'>
                                        <input class='btn btn-primary' type='submit' name='selecionar' value='Selecionar'>
                                     </form>
                               </td>";
                $resultado .= "</tr>";
            }

        }else {
            $exibir = false;
            $resultado = "<td colspan='4'>
                        <span style='margin: 50% 40%;'>Sem resultados</span>
                      </td>
                      <td>
                        <form method='post' action='?perfil=evento&p=pf_cadastro'>
                            <input type='hidden' name='cpf-' value='$procurar'>
                            <button class=\"btn btn-primary\" type='submit'>
                                <i class=\"glyphicon glyphicon-plus\">        
                                </i>Adicionar
                            </button>
                        </form>
                      </td>";

        }
    }

}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Procurar pessoa fisica</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="?perfil=evento&p=pf_cadastro_pesquisa" method="post">
                            <div class="form-group">
                                <label for="procurar">Pesquisar:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="procurar" placeholder="Digite o CPF da pessoa..." value="<?=$procurar?>" >
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i> Procurar</button>
                                    </span>
                                </div>
                            </div>
                        </form>

                            <div class="panel panel-default">
                                <!-- Default panel contents -->
                                <!-- Table -->
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>CPF</th>
                                            <th>E-mail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($exibir){
                                                echo $resultado;
                                            }elseif(!$exibir){
                                                echo $resultado;
                                            }else{
                                                echo $resultado;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>


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
