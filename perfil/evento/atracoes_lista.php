<?php
include "includes/menu_interno.php";

$con = bancoMysqli();
$idEvento = $_SESSION['idEvento'];
$evento = recuperaDados('eventos', 'id', $idEvento);

if (isset($_POST['apagar'])) {

    $idAtracao = $_POST['idAtracao'];

    $consulta = "UPDATE atracoes SET publicado = 0 WHERE id = '$idAtracao'";

    if ($query = mysqli_query($con, $consulta)) {
        $mensagem = mensagem("success", "Atração apagada com sucesso");
        $deletaOcorrenciasAtracao = "UPDATE ocorrencias SET publicado = 0 WHERE atracao_id = '$idAtracao'";
        mysqli_query($con, $deletaOcorrenciasAtracao);
    } else {
        $mensagem = mensagem("danger", "Erro ao tentar executar operação na atração");
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

                    <?php if (isset($mensagem)) {
                        echo $mensagem;
                    } ?>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblAtracao" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nome da atração</th>
                                <th>Produtor</th>
                                <th>Especificidade</th>
                                <th>Ocorrência</th>
                                <th>Visualizar</th>
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
                                        $exposicao = recuperaDados("exposicao", "atracao_id", $atracao['idAtracao']);
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
                                $ocorrencias = recuperaOcorrenciaDados($atracao['idAtracao'], $evento['tipo_evento_id']);

                                if ($ocorrencias > 0) {
                                    $idProdutor = $atracao['produtor_id'];
                                    $sql_produtor = "SELECT nome FROM produtores WHERE id = '$idProdutor'";
                                    $query_produtor = mysqli_query($con, $sql_produtor);
                                    $produtor = mysqli_fetch_array($query_produtor);
                                    echo "<td>
                                              <form method=\"POST\" action=\"?perfil=evento&p=ocorrencia_lista\" role=\"form\">
                                        <input type='hidden' name='idOrigem' value='" . $atracao['idAtracao'] . "'>
                                        <button type=\"submit\" name='carregar' class=\"btn btn-primary\"><i class=\"fa fa-pencil-square-o\"></i> Listar ocorrência</button>
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
                                        <buttonn class='btn btn-block btn-danger' data-toggle='modal' data-target='#apagar' data-ocorrencia-id='" . $atracao['idAtracao'] . "' data-tittle='Apagar Atração' data-message='Você deseja mesmo apagar essa atração?' onclick ='passarId(" . $atracao['idAtracao'] . ")'><span class='glyphicon glyphicon-trash'></span></buttonn>
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
                                <th>Visualizar</th>
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

<!--Apagar ocorrência-->
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