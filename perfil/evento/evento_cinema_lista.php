<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

$idEvento = $_SESSION['idEvento'];

$evento = recuperaDados('eventos', 'id', $idEvento);


if (isset($_POST['selecionar'])){
    $idFilme = $_POST['idFilme'];

    $sql = "INSERT INTO filme_eventos 
    VALUES ('$idFilme','$idEvento')";

    if (mysqli_query($con,$sql)){
        $mensagem = mensagem("success","Filme adicionado a lista!");
    }else{
        $mensagem = mensagem("danger",die(mysqli_error($con)));
    }

}


$query = "SELECT f.id, f.titulo, f.ano_producao, f.duracao, f.direcao 
FROM filmes f
INNER JOIN filme_eventos fe ON fe.filme_id = f.id
WHERE f.publicado = 1 AND fe.evento_id='$idEvento'";

$resul = mysqli_query($con, $query);

?>

<div class="content-wrapper">

    <section class="content">

        <h2 class="page-header">Cinema</h2>

        <div class="row">
            <?php
            if(isset($mensagem))
                echo $mensagem;
            ?>
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <div class="box-body">

                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Filme</th>
                                    <th>Ano </th>
                                    <th>Duração</th>
                                    <th>Diretor</th>
                                    <th>Ocorrência</th>
                                    <th colspan="2">
                                        <a class="btn btn-primary" href="?perfil=evento&p=evento_cinema_procura" style="margin: 0 25%;">
                                            <span class="glyphicon glyphicon-plus" style="font-size: 12px;margin-right: 5px">        
                                            </span>Adicionar
                                        </a>
                                    </th>
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


                               // OCORRENCIAS 

                                $ocorrencias = recuperaOcorrenciaDados($filmes['id'], $evento['tipo_evento_id']);

                                if($ocorrencias > 0){

                                    echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento&p=ocorrencia_lista\" role=\"form\">
                                    <input type='hidden' name='idOrigem' value='".$filmes['id']."'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-primary\"><i class=\"fa fa-pencil-square-o\"></i> Listar ocorrência</button>
                                    </form>
                                    </td>";
                                }
                                else{

                                    echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento&p=ocorrencia_cadastro\" role=\"form\">
                                    <input type='hidden' name='idOrigem' value='".$filmes['id']."'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><i class=\"fa fa-plus\"></i> Ocorrência</button>
                                    </form>
                                    </td>";
                                }
                                echo "<td>
                                <form method=\"POST\" action=\"?perfil=evento&p=evento_cinema_edita\" role=\"form\">
                                <input type='hidden' name='idFilme' value='".$filmes['id']."''>
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
                                  <th width="20%" colspan="2"></th>
                              </tr>
                          </tfoot>
                      </table>

                  </div>
              </div>
          </div>
      </div>

  </section>
</div>
