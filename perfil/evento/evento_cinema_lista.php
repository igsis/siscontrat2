<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

$mensagem = '';

$query = "SELECT 	id,titulo,	ano_producao, duracao, direcao FROM filmes WHERE publicado = 1 ";
$resul = mysqli_query($con, $query);

if (isset($_POST['cadastra']) || isset($POST['edita'])) {

    $tituloFilme = $_POST['tituloFilme'];
    $tituloOriginal = $_POST['tituloOriginal'];
    $paisOrigem = $_POST['paisOrigem'];
    $paisCoProducao = $_POST['paisCoProducao'];
    $anoProducao = $_POST['anoProducao'];
    $genero = $_POST['genero'];
    $bitola = $_POST['bitola'];
    $direcao = $_POST['direcao'];
    $sinopse = $_POST['sinopse'];
    $elenco = $_POST['elenco'];
    $duracao = $_POST['duracao'];
    $classidicacaoIndicativa = $_POST['classidicacaoIndicativa'];
    $link = $_POST['link'];
}

if (isset($_POST['cadastra'])) {
  $sql = "INSERT INTO `filmes`
            (titulo, titulo_original, ano_producao,
              genero, bitola, direcao,
              sinopse, elenco, duracao,
              link_trailer, classificacao_indicativa_id, pais_origem_id,
              pais_origem_coproducao_id)
            VALUES ('$tituloFilme','$tituloOriginal','$anoProducao',
                      '$genero','$bitola','$direcao',
                      '$sinopse','$elenco','$duracao',
                      '$link','$classidicacaoIndicativa',
                      '$paisOrigem','$paisCoProducao');";

  // $mensagem = mysqli_query($con, $sql) or die(mysqli_error($con));
  if(mysqli_query($con,$sql)){
    $mensagem = "Adicionado ao banco de dados";
    $query = "SELECT 	id,titulo,	ano_producao, duracao, direcao FROM filmes WHERE publicado = 1 ";
    $resul = mysqli_query($con, $query);
  }else{
    $mensagem = die(mysqli_error($con));
  }
}


?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Cinema</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                      <?php

                      echo $mensagem;

                      ?>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Filme</th>
                                <th>Ano </th>
                                <th>Duração</th>
                                <th>Diretor</th>
                                <th width="10%"></th>
                                <th width="10%"></th>
                            </tr>
                            </thead>
                            <?php
                            echo "<tbody>";
                            while ($filmes = mysqli_fetch_assoc($resul)){
                                echo "<tr>";
                                echo "<td>".$filmes['titulo']."</td>";
                                echo "<td>".$filmes['ano_producao']."</td>";
                                echo "<td>".$filmes['duracao']."</td>";
                                echo "<td>".$filmes['direcao']."</td>";
                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento&p=evento_edita\" role=\"form\">
                                    <input type='hidden' name='idEvento' value='".$filmes['id']."''>
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
                              <th>Filme</th>
                              <th>Ano </th>
                              <th>Duração</th>
                              <th>Diretor</th>
                              <th width="10%"></th>
                              <th width="10%"></th>
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
