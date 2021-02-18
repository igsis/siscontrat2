<?php
include "includes/menu_interno.php";

$con = bancoMysqli();
$idEvento = $_SESSION['idEvento'];
$evento = recuperaDados('eventos', 'id', $idEvento);

if (isset($_POST['apagar'])) {

    $sql = "SELECT * FROM pedidos where origem_id = '$idEvento' AND origem_tipo_id = 1 AND publicado = 1";
    $queryPedido = mysqli_query($con, $sql);
    $rowPedido = mysqli_num_rows($queryPedido);

    $idAtracao = $_POST['idAtracao'];

    $consulta = "UPDATE atracoes SET publicado = 0 WHERE id = '$idAtracao'";

    if ($query = mysqli_query($con, $consulta)) {
        $mensagem = mensagem("success", "Atração apagada com sucesso");
        $deletaOcorrenciasAtracao = "UPDATE ocorrencias SET publicado = 0 WHERE atracao_id = '$idAtracao' AND tipo_ocorrencia_id = 1";
        mysqli_query($con, $deletaOcorrenciasAtracao);

        if ($rowPedido > 0) {
            $idPedido = mysqli_fetch_array($queryPedido)['id'];
            $sql = "SELECT sum(valor_individual) as 'valores' FROM atracoes WHERE evento_id = '$idEvento' AND publicado = 1";
            $valor = mysqli_fetch_array(mysqli_query($con, $sql))['valores'];

            $sql = "UPDATE pedidos SET valor_total = '$valor' where id = '$idPedido' AND publicado = 1";

            if (mysqli_query($con, $sql)) {
                $mensagem2 = mensagem("warning", "Lembre-se de ajustar o valor das parcelas e do equipamento!");
            }
        }

    } else {
        $mensagem = mensagem("danger", "Erro ao tentar executar operação na atração");
    }
}

if (isset($_POST['cadastraProdutor'])){
    $nome = addslashes($_POST['nome']);
    $email = $_POST['email'];
    $telefone1 = $_POST['telefone1'];
    $telefone2 = $_POST['telefone2'];
    $observacao = addslashes($_POST['observacao']);
    $idAtracoes = $_POST['idAtracoes'];
    $sqlInsert = "INSERT INTO `produtores`
                      (nome, email, telefone1, telefone2, observacao)
                      VALUES ('$nome','$email','$telefone1','$telefone2','$observacao')";

    if (mysqli_query($con,$sqlInsert)){
        $idProdutor = recuperaUltimo("produtores");
        $sqlUpdate = "UPDATE `atracoes`
                          SET produtor_id = '$idProdutor'
                          WHERE id ='$idAtracoes'";
        if(mysqli_query($con,$sqlUpdate)){
            $mensagem = mensagem("success","Produtor cadastrado");
        }else{
            $mensagem = mensagem("danger","Erro ao cadastrar");
        }
    }
}

$sql = "SELECT at.id AS idAtracao, nome_atracao, produtor_id 
        FROM atracoes AS at
        WHERE at.publicado = 1 AND at.evento_id = '$idEvento'";
$query = mysqli_query($con, $sql);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Atrações</h2>
        <div class="row">
            <div class="col-md-2" id="adiciona">
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

                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem2)) {
                            echo $mensagem2;
                        }; ?>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblAtracao" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nome da atração</th>
                                <th>Produtor</th>
                                <th>Integrantes</th>
                                <th>Especificidade</th>
                                <th>Ocorrência</th>
                                <th>Editar</th>
                                <th>Apagar</th>

                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";

                            $numRows = mysqli_num_rows($query);
                            if ($numRows == 0) {
                                ?>
                                <tr>
                                    <td width="100%" class="text-center" colspan="6">
                                        Não existe atração cadastrada
                                    </td>
                                </tr>
                                <?php
                            }

                            while ($atracao = mysqli_fetch_array($query)) {
                                $integrantes = $con->query("SELECT * FROM atracao_integrante WHERE atracao_id = {$atracao['idAtracao']}")->num_rows;
                                echo "<tr>";
                                echo "<td>" . $atracao['nome_atracao'] . "</td>";
                                if ($atracao['produtor_id'] > 0) {
                                    $idProdutor = $atracao['produtor_id'];
                                    $sql_produtor = "SELECT id,nome FROM produtores WHERE id = '$idProdutor'";
                                    $query_produtor = mysqli_query($con, $sql_produtor);
                                    $produtor = mysqli_fetch_array($query_produtor);
                                    echo "<td>
                                              <form method=\"POST\" action=\"?perfil=evento&p=produtor_edita\" role=\"form\">
                                        <input type='hidden' name='idProdutor' value='" . $produtor['id'] . "'>
                                        <button type=\"submit\" name='carregar' class=\"btn btn-primary\"><i class=\"fa fa-pencil-square-o\"></i></button>
                                        " . $produtor['nome'] . "</form>
                                        </td>";
                                } else {
                                    echo "<td>
                                        <form method=\"POST\" action=\"?perfil=evento&p=produtor_cadastro\" role=\"form\">
                                        <input type='hidden' name='idAtracao' value='" . $atracao['idAtracao'] . "'>
                                        <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><i class=\"fa fa-plus\"></i> Produtor</button>
                                        </form>
                                    </td>";
                                }

                                /*
                                 * Integrantes
                                 */
                                ?>
                                <?php if ($integrantes): ?>
                                    <td>
                                        <form action="?perfil=evento&p=integrantes_lista&atracao=<?= $atracao['idAtracao'] ?>" method="post" role="form">
                                            <input type="hidden" name="idAtracao" value="<?= $atracao['idAtracao'] ?>">
                                            <button type="submit" class="btn btn-block btn-primary">Lista Integrantes</button>
                                        </form>
                                    </td>
                                <?php else: ?>
                                    <td>
                                        <form action="?perfil=evento&p=integrantes_pesquisa&atracao=<?= $atracao['idAtracao'] ?>" method="post" role="form">
                                            <input type="hidden" name="idAtracao" value="<?= $atracao['idAtracao'] ?>">
                                            <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-plus"></i> Integrante</button>
                                        </form>
                                    </td>
                                <?php endif; ?>

                                <?php
                                /*
                                 * Especificidades
                                 */
                                $acoes = recuperaDados("acao_atracao", "atracao_id", $atracao['idAtracao']);
                                $idAcao = $acoes['acao_id'];
                                switch ($idAcao) {
                                    case 11: //teatro
                                        $disabled = "";
                                        $teatro = recuperaDados("teatro", "atracao_id", $atracao['idAtracao']);
                                        if ($teatro != NULL) {
                                            $url = "?perfil=evento&p=teatro_edita";
                                            $name = "idTeatro";
                                            $value = $teatro['id'];
                                            $icon = "<i class='fa fa-pencil-square-o'></i> Editar";
                                        } else {
                                            $url = "?perfil=evento&p=teatro_cadastro";
                                            $name = "idAtracao";
                                            $value = $atracao['idAtracao'];
                                            $icon = "<i class='fa fa-plus'></i> Cadastrar";
                                        }
                                        break;
                                    case 7: //música
                                        $disabled = "";
                                        $musica = recuperaDados("musica", "atracao_id", $atracao['idAtracao']);
                                        if ($musica != NULL) {
                                            $url = "?perfil=evento&p=musica_edita";
                                            $name = "idMusica";
                                            $value = $musica['id'];
                                            $icon = "<i class='fa fa-pencil-square-o'></i> Editar";
                                        } else {
                                            $url = "?perfil=evento&p=musica_cadastro";
                                            $name = "idAtracao";
                                            $value = $atracao['idAtracao'];
                                            $icon = "<i class='fa fa-plus'></i> Cadastrar";
                                        }
                                        break;
                                    case 5: //exposição (feira)
                                        $disabled = "";
                                        $exposicao = recuperaDados("exposicoes", "atracao_id", $atracao['idAtracao']);
                                        if ($exposicao != NULL) {
                                            $url = "?perfil=evento&p=exposicao_edita";
                                            $name = "idExposicao";
                                            $value = $exposicao['id'];
                                            $icon = "<i class='fa fa-pencil-square-o'></i> Editar";
                                        } else {
                                            $url = "?perfil=evento&p=exposicao_cadastro";
                                            $name = "idAtracao";
                                            $value = $atracao['idAtracao'];
                                            $icon = "<i class='fa fa-plus'></i> Cadastrar";
                                        }
                                        break;
                                    case 8: //oficina
                                        $disabled = "";
                                        $oficina = recuperaDados("oficinas", "atracao_id", $atracao['idAtracao']);
                                        if ($oficina != NULL) {
                                            $url = "?perfil=evento&p=oficina_edita";
                                            $name = "idOficina";
                                            $value = $oficina['id'];
                                            $icon = "<i class='fa fa-pencil-square-o'></i> Editar";
                                        } else {
                                            $url = "?perfil=evento&p=oficina_cadastro";
                                            $name = "idAtracao";
                                            $value = $atracao['idAtracao'];
                                            $icon = "<i class='fa fa-plus'></i> Cadastrar";
                                        }
                                        break;
                                    default:
                                        $url = "#";
                                        $name = "";
                                        $value = "";
                                        $icon = "Não se aplica";
                                        $disabled = "disabled";
                                        break;
                                }
                                ?>
                                <td>
                                    <form method="POST" action="<?= $url ?>" role="form">
                                        <input type="hidden" name="<?= $name ?>" value="<?= $value ?>">
                                        <button type="submit" <?= $disabled ?> name='carregar'
                                                class="btn btn-block btn-primary"><?= $icon ?></button>
                                    </form>
                                </td>
                                <?php
                                /*
                                 * Ocorrência
                                 */
                                $ocorrencias = recuperaOcorrenciaDados('ocorrencias', 'atracao_id', $atracao['idAtracao'], $evento['tipo_evento_id']);

                                if ($ocorrencias > 0) {
                                    $idProdutor = $atracao['produtor_id'];
                                    $sql_produtor = "SELECT nome FROM produtores WHERE id = '$idProdutor'";
                                    $query_produtor = mysqli_query($con, $sql_produtor);
                                    $produtor = mysqli_fetch_array($query_produtor);
                                    echo "<td>
                                              <form method=\"POST\" action=\"?perfil=evento&p=ocorrencia_lista\" role=\"form\">
                                        <input type='hidden' name='idOrigem' value='" . $atracao['idAtracao'] . "'>
                                        <button type=\"submit\" name='carregar' class=\"btn btn-block    btn-primary\"><i class=\"fa fa-pencil-square-o\"></i> Listar ocorrência</button>
                                        </form>
                                        </td>";
                                } else {
                                    echo "<td>
                                        <form method=\"POST\" action=\"?perfil=evento&p=ocorrencia_cadastro\" role=\"form\">
                                        <input type='hidden' name='idOrigem' value='" . $atracao['idAtracao'] . "'>
                                        <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><i class=\"fa fa-plus\"></i> Ocorrência</button>
                                        </form>
                                    </td>";
                                }
                                echo "<td width='5%'>
                                    <form method=\"POST\" action=\"?perfil=evento&p=atracoes_edita\" role=\"form\">
                                    <input type='hidden' name='idAtracao' value='" . $atracao['idAtracao'] . "'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><i class='fa fa-file-text-o'></i> </button>
                                    </form>
                                </td>";
                                echo "<td width='5%'>                                        
                                        <button class='btn btn-block btn-danger' data-toggle='modal' data-target='#apagar' data-ocorrencia-id='" . $atracao['idAtracao'] . "' data-tittle='Apagar Atração' data-message='Você deseja mesmo apagar essa atração?' onclick ='passarId(" . $atracao['idAtracao'] . ")'><span class='glyphicon glyphicon-trash'></span></button>
                                  </td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Nome da atração</th>
                                <th>Produtor</th>
                                <th>Especificidade</th>
                                <th>Ocorrência</th>
                                <th>Editar</th>
                                <th>Apagar</th>
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

<!--Apagar atracao-->
<div class="modal fade" id="apagar" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id='formApagar' action="?perfil=evento&p=atracoes_lista" class="form-horizontal"
                  role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><p>Apagar atração</p></h4>
                </div>
                <div class="modal-body">
                    <p>Deseja mesmo apagar esta atração? </p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="idAtracao">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type='submit' class='btn btn-danger btn-sm' name="apagar">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblAtracao').DataTable({
            "language": {
                "url": 'bower_components/datatables.net/Portuguese-Brasil.json'
            },
            "responsive": true,
            "dom": "<'row'<'col-sm-6'l><'col-sm-6 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>",
        });
    });
    let cat = "<?= $idCategoriaAtracao ?>";
    if (cat == 4) {
        $("#adiciona").attr("style", "display:none");
        // $("#oficina_txt").attr("style", "display:block");
    } else {
        $("#adiciona").attr("style", "display:block");
        //$("#oficina_txt").attr("style", "display:none");
    }


</script>

<script>

    function passarId(id) {
        document.querySelector('#formApagar input[name="idAtracao"]').value = id;
    }
</script>