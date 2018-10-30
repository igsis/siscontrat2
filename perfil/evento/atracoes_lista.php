<?php

$con = bancoMysqli();

$idEvento = $_SESSION['idEvento'];

$evento = recuperaDados('eventos', 'id', $idEvento);
    
$sql = "SELECT at.id AS idAtracao, nome_atracao, a2.categoria_atracao,produtor_id,at.categoria_atracao_id FROM atracoes AS at
        INNER JOIN atracao_eventos a on at.id = a.atracao_id
        INNER JOIN categoria_atracoes a2 on at.categoria_atracao_id = a2.id
        WHERE at.publicado = 1 AND a.evento_id = '$idEvento'";
$query = mysqli_query($con,$sql);

include "includes/menu_interno.php";
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
                                <th>Especificidade</th>
                                <th>Ocorrência</th>
                                <th colspan="2" width="10%">Ação</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<body>";
                            while ($atracao = mysqli_fetch_array($query)){

                                echo "<tr>";
                                echo "<td>".$atracao['nome_atracao']."</td>";
                                echo "<td>".$atracao['categoria_atracao']."</td>";
                                if($atracao['produtor_id'] > 0){
                                    $idProdutor = $atracao['produtor_id'];
                                    $sql_produtor = "SELECT id,nome FROM produtores WHERE id = '$idProdutor'";
                                    $query_produtor = mysqli_query($con,$sql_produtor);
                                    $produtor = mysqli_fetch_array($query_produtor);
                                    echo "<td>
                                              <form method=\"POST\" action=\"?perfil=evento&p=produtor_edita\" role=\"form\">
                                        <input type='hidden' name='idProdutor' value='".$produtor['id']."'>
                                        <button type=\"submit\" name='carregar' class=\"btn btn-primary\"><i class=\"fa fa-pencil-square-o\"></i></button>
                                        ".$produtor['nome']."</form>
                                        </td>";
                                }
                                else{
                                    echo "<td>
                                        <form method=\"POST\" action=\"?perfil=evento&p=produtor_cadastro\" role=\"form\">
                                        <input type='hidden' name='idAtracao' value='".$atracao['idAtracao']."'>
                                        <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><i class=\"fa fa-plus\"></i> Produtor</button>
                                        </form>
                                    </td>";
                                }
                                /*
                                 * Especificidades
                                 */
                                $idCategoriaAtracao = $atracao['categoria_atracao_id'];
                                $array_teatro = array(3,7,23,24);
                                if(in_array($idCategoriaAtracao, $array_teatro)){
                                    $teatro = recuperaDados("teatro","atracao_id",$atracao['idAtracao']);
                                    if($teatro != NULL){
                                        echo "<td>
                                                <form method=\"POST\" action=\"?perfil=evento&p=teatro_edita\" role=\"form\">
                                                <input type=\"hidden\" name='idTeatro' value='".$teatro['id']."'>
                                                <button type=\"submit\" name='carregar' class=\"btn btn-primary\"><i class=\"fa fa-pencil-square-o\"></i></button>
                                                </form>
                                                </td>";
                                    }
                                    else{
                                        echo "<td>
                                                <form method=\"POST\" action=\"?perfil=evento&p=teatro_cadastro\" role=\"form\">
                                                <input type='hidden' name='idAtracao' value='".$atracao['idAtracao']."'>
                                                <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><i class=\"fa fa-plus\"></i> Especificidade</button>
                                                </form>
                                                </td>";
                                    }
                                }
                                else{
                                    $array_musica = array(10,11,15,17);
                                    if(in_array($idCategoriaAtracao, $array_musica)){
                                        $musica = recuperaDados("musica","atracao_id",$atracao['idAtracao']);
                                        if($musica != NULL){
                                            echo "<td>
                                                    <form method=\"POST\" action=\"?perfil=evento&p=musica_edita\" role=\"form\">
                                                    <input type=\"hidden\" name='idMusica' value='".$musica['id']."'>
                                                    <button type=\"submit\" name='carregar' class=\"btn btn-primary\"><i class=\"fa fa-pencil-square-o\"></i>Especificidade</button>
                                                    </form>
                                                    </td>";
                                        }
                                        else{
                                            echo "<td>
                                                    <form method=\"POST\" action=\"?perfil=evento&p=musica_cadastro\" role=\"form\">
                                                    <input type='hidden' name='idAtracao' value='".$atracao['idAtracao']."'>
                                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><i class=\"fa fa-plus\"></i>Especificidade</button>
                                                    </form>
                                                    </td>";
                                        }
                                    }
                                    else{
                                        if($idCategoriaAtracao == 2){
                                            $exposicao = recuperaDados("exposicoes","atracao_id",$atracao['idAtracao']);
                                            if($exposicao != NULL){
                                                echo "<td>
                                                    <form method=\"POST\" action=\"?perfil=evento&p=exposicao_edita\" role=\"form\">
                                                    <input type='hidden' name='idExposicao' value='".$exposicao['id']."'>
                                                    <button type=\"submit\" name='carregar' class=\"btn btn-primary\"><i class=\"fa fa-pencil-square-o\"></i>Especificidade</button>
                                                    </form>
                                                    </td>";
                                            }
                                            else{
                                                echo "<td>
                                                    <form method=\"POST\" action=\"?perfil=evento&p=exposicao_cadastro\" role=\"form\">
                                                    <input type='hidden' name='idAtracao' value='".$atracao['idAtracao']."'>
                                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><i class=\"fa fa-plus\"></i>Especificidade</button>
                                                    </form>
                                                    </td>";
                                            }
                                        }
                                        else{
                                            if($idCategoriaAtracao == 4 || $idCategoriaAtracao == 5){
                                                $oficina = recuperaDados("oficinas","atracao_id",$atracao['idAtracao']);
                                                if($oficina != NULL){
                                                    echo "<td>
                                                    <form method=\"POST\" action=\"?perfil=evento&p=oficina_edita\" role=\"form\">
                                                    <input type='hidden' name='idOficina' value='".$oficina['id']."'>
                                                    <button type=\"submit\" name='carregar' class=\"btn btn-primary\"><i class=\"fa fa-pencil-square-o\"></i>Especificidade</button>
                                                    </form>
                                                    </td>";
                                                }
                                                else{
                                                    echo "<td>
                                                    <form method=\"POST\" action=\"?perfil=evento&p=oficina_cadastro\" role=\"form\">
                                                    <input type='hidden' name='idAtracao' value='".$atracao['idAtracao']."'>
                                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><i class=\"fa fa-plus\"></i> Especificidade</button>
                                                    </form>
                                                    </td>";
                                                }
                                            }
                                            else{
                                                echo "<td>Não há especificidades.</td>";
                                            }
                                        }
                                    }
                                }
                                /*
                                 * Ocorrência
                                 */
                                $ocorrencias = recuperaOcorrenciaDados($atracao['idAtracao'], $evento['tipo_evento_id']);

                                if($ocorrencias > 0){
                                    $idProdutor = $atracao['produtor_id'];
                                    $sql_produtor = "SELECT nome FROM produtores WHERE id = '$idProdutor'";
                                    $query_produtor = mysqli_query($con,$sql_produtor);
                                    $produtor = mysqli_fetch_array($query_produtor);
                                    echo "<td>
                                              <form method=\"POST\" action=\"?perfil=evento&p=ocorrencia_lista\" role=\"form\">
                                        <input type='hidden' name='idOrigem' value='".$atracao['idAtracao']."'>
                                        <button type=\"submit\" name='carregar' class=\"btn btn-primary\"><i class=\"fa fa-pencil-square-o\"></i> Listar ocorrência</button>
                                        </form>
                                        </td>";
                                }
                                else{
                                    echo "<td>
                                        <form method=\"POST\" action=\"?perfil=evento&p=ocorrencia_cadastro\" role=\"form\">
                                        <input type='hidden' name='idOrigem' value='".$atracao['idAtracao']."'>
                                        <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><i class=\"fa fa-plus\"></i> Ocorrência</button>
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