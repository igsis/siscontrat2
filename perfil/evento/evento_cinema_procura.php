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

        $sql = "SELECT COUNT(titulo) 'qtd'
                FROM   `filmes`
                WHERE titulo LIKE '%$procurar%' AND publicado = 1";
//        $query = mysqli_query($con, $sql);
        $filmes = mysqli_fetch_assoc(mysqli_query($con, $sql));

        if ($filmes['qtd'] > 0) {
            $exibir = true;

            $sql = "SELECT  id, titulo, ano_producao, duracao, direcao
                    FROM `filmes`
                    WHERE titulo LIKE '%$procurar%' AND publicado = 1";
            $query = mysqli_query($con,$sql);

            $resultado = "";

            while($filmes = mysqli_fetch_array($query)){
                $resultado .= "<tr>";
                $resultado .= "<td>".$filmes['titulo']."</td>";
                $resultado .= "<td>".$filmes['ano_producao']."</td>";
                $resultado .= "<td>".$filmes['duracao']."</td>";
                $resultado .= "<td>".$filmes['direcao']."</td>";
                $resultado .= "<td>
                                     <form action='?perfil=evento&p=evento_cinema_edita' method='post'>
                                        <input type='hidden' name='idFilme' value='".$filmes['id']."'>
                                        <td>
                                            <input class='btn btn-primary' type='submit' name='adicionar' value='Adicionar'>
                                        </td>
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
                         <form action=\"?perfil=evento&p=evento_cinema_cadastro\" method=\"post\">
                            <input type='hidden' name='nomeFilme' value='$procurar'>
                            <button class='btn btn-primary'><i class='glyphicon glyphicon-plus'></i> Adicionar</button>
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
                        <h3 class="box-title">Procurar filme</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="?perfil=evento&p=evento_cinema_procura" method="post">
                            <div class="form-group">
                                <label for="procurar">Pesquisar:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="procurar" placeholder="Digite o nome do filme..." value="<?=$procurar?>" >
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
                                <div class="box-footer" align="center">
                                <a href="?perfil=evento&p=evento_cinema_lista">
                                    <button type="button" class="btn btn-default">Voltar</button>
                                </a>
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
