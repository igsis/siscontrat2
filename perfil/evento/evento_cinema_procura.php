<?php

$con = bancoMysqli();
include "includes/menu_interno.php";

$exibir = ' ';
$resultado = "<td></td>";
$idEvento = $_SESSION['idEvento'];

if (isset($_POST['procurar'])){
    $procurar = $_POST['procurar'];
    if ($procurar != NULL) {

        $sql = "SELECT COUNT(titulo) 'qtd'
                FROM   `filmes`
                WHERE titulo LIKE '%$procurar%' AND publicado = 1";
//        $query = mysqli_query($con, $sql);
        $filmes = mysqli_fetch_assoc(mysqli_query($con, $sql));

        if ($filmes['qtd'] > 0) {
            $exibir = true;

            $sql = "SELECT  id, titulo, ano_producao, duracao, diretor
                    FROM `filmes`
                    WHERE titulo LIKE '%$procurar%' AND publicado =1";
            $resultado = mnysqli_query($con,$sql);

        } else {
            $exibir = false;
            $resultado = "<td colspan='4'>
                        <span style='margin: 50% 40%;'>Sem resultados</span>
                      </td>
                      <td>
                        <a class=\"btn btn-primary\" href=\"?perfil=evento&p=evento_cinema_cadastro\">
                            <i class=\"glyphicon glyphicon-plus\">        
                            </i>Adicionar
                         </a>
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
                        <h3 class="box-title">Procurar filme</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="?perfil=evento&p=evento_cinema_procura" method="post">
                            <div class="form-group">
                                <label for="procurar">Pesquisar:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="procurar" placeholder="Digite o nome do filme..." value="<?=$_POST['procurar'] != NULL?$procurar:"";?>" >
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
                                            <th>Nome do filme</th>
                                            <th>Ano</th>
                                            <th>Duração</th>
                                            <th>Diretor</th>
                                            <th width="10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (!$exibir){
                                                echo $resultado;
                                            }elseif($exibir){
                                                while($filmes = mysqli_fetch_array($resultado)){
                                                    echo "<tr>";
                                                    echo "<td>".$filmes['titulo']."</td>";
                                                    echo "<td>".$filmes['ano_producao']."</td>";
                                                    echo "<td>".$filmes['duracao']."</td>";
                                                    echo "<td>".$filmes['diretor']."</td>";
                                                    echo "<td>
                                                    <form action='?perfil=evento&p=evento_cinema_lista'>
                                                        <input type='hidden' name='idFilme' value='".$filmes['id']."'>
                                                        <input class='btn btn-primary' type='submit' value='Selecionar'>
                                                    </form>
                                                    </td>";
                                                    echo "</tr>";
                                                }
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
