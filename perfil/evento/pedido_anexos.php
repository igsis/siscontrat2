<?php
$con = bancoMysqli();

$idPedido = $_SESSION['idPedido'];
$idEvento = $_SESSION['idEvento'];

$pessoaTipoPedido = recuperaDados('pedidos', 'id', $idPedido)['pessoa_tipo_id'];
if ($pessoaTipoPedido == 1) {
    $tipo = '10';
} else {
    $tipo = '9';
}

$sqlAtracoes = "SELECT id FROM atracoes WHERE evento_id = '$idEvento' AND publicado = '1'";
$atracoes = $con->query($sqlAtracoes)->fetch_all(MYSQLI_ASSOC);

$musica = false;
$oficina = false;
$artesCenicas = false;
$edital = false;

foreach ($atracoes as $atracao) {
    $sqlAcao = "SELECT acao_id FROM acao_atracao WHERE atracao_id = '{$atracao['id']}'";
    $acoes = $con->query($sqlAcao)->fetch_all(MYSQLI_ASSOC);
    foreach ($acoes as $acao) {
        switch ($acao['acao_id']) {
            case 7:
                $musica = true;
                break;

            case 2:
            case 3:
            case 11:
                $artesCenicas = true;
                break;

            case 8;
                $oficina = true;
                break;

            default:
                break;
        }
    }
}

if ($musica) {
    $whereAdicional[] = "musica = '1'";
}
if ($oficina) {
    $whereAdicional[] = "oficina = '1'";
}
if ($artesCenicas) {
    $whereAdicional[] = "teatro = '1'";
}
if ($edital) {
    $whereAdicional[] = "edital = '1'";
}

if (isset($_POST["enviarArquivo"])) {
    $sql_arquivos = "SELECT * FROM lista_documentos WHERE tipo_documento_id IN ('3','$tipo') and publicado = 1";
    $query_arquivos = mysqli_query($con, $sql_arquivos);
    while ($arq = mysqli_fetch_array($query_arquivos)) {
        $y = $arq['id'];
        $x = $arq['sigla'];
        $nome_arquivo = isset($_FILES['arquivo']['name'][$x]) ? $_FILES['arquivo']['name'][$x] : null;
        $f_size = isset($_FILES['arquivo']['size'][$x]) ? $_FILES['arquivo']['size'][$x] : null;

        if ($f_size > 5242880) {
            $mensagem = mensagem("danger", "<strong>Erro! Tamanho de arquivo excedido! Tamanho máximo permitido: 05 MB.</strong>");
        } else {
            if ($nome_arquivo != "") {
                $nome_temporario = $_FILES['arquivo']['tmp_name'][$x];
                $new_name = date("YmdHis", strtotime("-3 hours")) . "_" . semAcento($nome_arquivo); //Definindo um novo nome para o arquivo
                $hoje = date("Y-m-d H:i:s", strtotime("-3 hours"));
                $dir = '../uploadsdocs/'; //Diretório para uploads
                $allowedExts = array(".pdf", ".PDF"); //Extensões permitidas
                $ext = strtolower(substr($nome_arquivo, -4));

                if (in_array($ext, $allowedExts)) //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                {
                    if (move_uploaded_file($nome_temporario, $dir . $new_name)) {
                        $sql_insere_arquivo = "INSERT INTO `arquivos` (`origem_id`, `lista_documento_id`, `arquivo`, `data`, `publicado`) VALUES ('$idPedido', '$y', '$new_name', '$hoje', '1'); ";
                        $query = mysqli_query($con, $sql_insere_arquivo);

                        if ($query) {
                            $mensagem = mensagem("success", "Arquivo recebido com sucesso");
                            echo "<script>
                                swal('Clique nos arquivos após efetuar o upload e confira a exibição do documento!', '', 'warning');                             
                            </script>";
                            gravarLog($sql_insere_arquivo);
                        } else {
                            $mensagem = mensagem("danger", "Erro ao gravar no banco");
                        }
                    } else {
                        $mensagem = mensagem("danger", "Erro no upload");
                    }
                } else {
                    echo "<script>
                            swal('Erro no upload!', 'Anexar documentos somente no formato PDF.', 'error');                             
                        </script>";
                }
            }
        }
    }
}

if (isset($_POST['apagarArquivo'])) {
    $idArquivo = $_POST['idArquivo'];
    $sql_apagar_arquivo = "UPDATE arquivos SET publicado = 0 WHERE id = '$idArquivo'";
    if (mysqli_query($con, $sql_apagar_arquivo)) {
        $arq = recuperaDados("arquivos", 'id', $idArquivo);
        $mensagem = mensagem("success", "Arquivo " . $arq['arquivo'] . " apagado com sucesso!");
        gravarLog($sql_apagar_arquivo);
    } else {
        $mensagem = mensagem("danger", "Erro ao apagar o arquivo. Tente novamente!");
    }
}

include "includes/menu_interno.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Pedido de Contratação</h2>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Anexos do Proponente</h3>
                    </div>

                    <div class="row" align="center">
                        <?= $mensagem ?? "" ?>
                    </div>

                    <div class="box-body">
                        <div class="card-body">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Arquivos anexados</h3>
                                </div>
                                <div class="box-body">
                                    <?php
                                    //lista arquivos de determinado pedido
                                    $sql = "SELECT * 
                                            FROM lista_documentos as list
                                            INNER JOIN arquivos as arq ON arq.lista_documento_id = list.id
                                            WHERE arq.origem_id = '$idPedido' AND list.tipo_documento_id IN  (3, {$tipo})
                                            AND arq.publicado = '1' ORDER BY arq.id";
                                    $query = mysqli_query($con, $sql);
                                    $linhas = mysqli_num_rows($query);
                                    if ($linhas > 0) {
                                        ?>
                                        <table class='table text-center table-striped table-bordered table-condensed'>
                                            <thead>
                                            <tr class='bg-info text-bold'>
                                                <td>Tipo de arquivo</td>
                                                <td>Nome do documento</td>
                                                <td>Data de envio</td>
                                                <td width='15%'></td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            while ($arquivo = mysqli_fetch_array($query)) {
                                                ?>
                                                <tr>
                                                    <td class='list_description'><?= $arquivo['documento'] ?></td>
                                                    <td class='list_description'><a
                                                                href='../uploadsdocs/<?= $arquivo['arquivo'] ?>'
                                                                target='_blank'>
                                                            <?= mb_strimwidth($arquivo['arquivo'], 15, 25, "...") ?></a>
                                                    </td>
                                                    <td class='list_description'>(<?= exibirDataBr($arquivo['data']) ?>
                                                        )
                                                    </td>
                                                    <td class='list_description'>
                                                        <form id='formExcliuir' method='POST'>
                                                            <button class='btn btn-danger glyphicon glyphicon-trash'
                                                                    type='button'
                                                                    data-toggle='modal' data-target='#exclusao'
                                                                    data-nome='<?= $arquivo['arquivo'] ?>'
                                                                    data-id='<?= $arquivo['id'] ?>'>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <?php
                                    } else {
                                        ?>
                                        <p>Não há listas disponíveis no momento.</p><br/>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                            $evento = recuperaDados('eventos', 'id', $idEvento);
                            if ($evento['tipo_evento_id'] == 1) {
                                $IN_adicional = "";
                                if ($musica || $oficina || $artesCenicas) {
                                    $sqlAdicional = "AND (" . implode(" OR ", $whereAdicional) . ")";
                                } else
                                    $sqlAdicional = "";
                                $sql_arquivos = "SELECT * FROM lista_documentos WHERE tipo_documento_id IN (3,{$tipo}) and publicado = 1 $sqlAdicional";
                            } else {
                                $IN_adicional = "lista_documento_id IN (SELECT id FROM lista_documentos WHERE tipo_documento_id = '3' and publicado = 1) AND";
                                $sql_arquivos = "SELECT * FROM lista_documentos WHERE tipo_documento_id = '3' and publicado = 1 AND (musica = 1 AND teatro = 1 AND oficina = 1 AND documento NOT LIKE '%Pessoa Jurídica%')";
                            }
                            $query_arquivos = mysqli_query($con, $sql_arquivos);
                            $numArquivosListagem = mysqli_num_rows($query_arquivos);
                            if ($linhas < $numArquivosListagem): ?>
                                <div id="envioArq" class="box box-success">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Enviar Arquivos</h3>
                                    </div>
                                    <div class="box-body">
                                        <h4 class="text-center">Nesta página, você envia documentos digitalizados. O
                                            tamanho
                                            máximo do arquivo deve ser 5MB.</h4>

                                        <form method="POST" enctype="multipart/form-data"
                                              action="?perfil=evento&p=pedido_anexos" role="form">
                                            <table class="table text-center table-striped">
                                                <tbody>
                                                <?php
                                                while ($arq = mysqli_fetch_array($query_arquivos)) {
                                                    $idDoc = $arq['id'];
                                                    $sqlExistentes = "SELECT * FROM arquivos WHERE $IN_adicional lista_documento_id = '$idDoc' AND origem_id = '$idPedido' AND publicado = 1";
                                                    $queryExistentes = mysqli_query($con, $sqlExistentes);

                                                    if (mysqli_num_rows($queryExistentes) == 0) {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <label><?php echo $arq['documento'] ?></label>
                                                            </td>
                                                            <td>
                                                                <input type='file'
                                                                       name='arquivo[<?php echo $arq['sigla']; ?>]'>
                                                            </td>
                                                        </tr>
                                                        <!--                                    Aparece com a Parte de envio de arquivos-->
                                                        <script>
                                                            document.querySelector('#envioArq').style.display = 'block';
                                                        </script>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <!--                             REMOVIDO , POIS ESTAVA DANDO CONFLITO QUANDO ERA ANEXADO SOMENTE O ULTIMO ARQUIVO

                                                                                            Sumir com a Parte de envio de arquivos
                                                                                            <script>
                                                                                                document.querySelector('#envioArq').style.display = 'none';
                                                                                            </script>  -->
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                            <br>
                                            <?php
                                            $num_lista = mysqli_num_rows($query_arquivos);
                                            //$IN_adicional funciona para impedir que o botão suma sem necessidade, algumas vezes causava conflito de não sumir o botão mesmo tendo sido upado todos os arquivos
                                            $num_arquivos = $con->query("SELECT * FROM arquivos WHERE $IN_adicional origem_id = '$idPedido' AND publicado = 1")->num_rows;
                                            $num_total = $num_lista - $num_arquivos;
                                            if ($num_total != 0) {
                                                ?>
                                                <input type='hidden' name='idPedido' value='<?= $idPedido ?>'/>
                                                <input type="hidden" name="tipoPessoa"
                                                       value="3"/>
                                                <input type="submit" class="btn btn-primary btn-lg btn-block"
                                                       name="enviarArquivo" value='Enviar'>
                                                <?php
                                            }
                                            ?>
                                        </form>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

<!--.modal-->
<div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmação de Exclusão</h4>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este arquivo?</p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="?perfil=evento&p=pedido_anexos" role="form" data-etapa="Envio de Arquivos">
                    <input type="hidden" name="idArquivo" id="idArquivo" value="">
                    <input type="hidden" name="tipoPessoa" id="tipoPessoa" value="">
                    <input type="hidden" name="idPedido" id="idPedido" value="<?= $idPedido ?>">
                    <input type="hidden" name="apagarArquivo" id="apagar">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                    </button>
                    <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
                </form>
            </div>
        </div>
    </div>
</div>
<!--  Fim Modal de Upload de arquivo  -->

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let nome = $(e.relatedTarget).attr('data-nome');
        let id = $(e.relatedTarget).attr('data-id');
        let pessoa = $(e.relatedTarget).attr('data-pessoa');

        $(this).find('p').text(`Tem certeza que deseja excluir o arquivo ${nome} ?`);
        $(this).find('#idArquivo').attr('value', `${id}`);
        $(this).find('#tipoPessoa').attr('value', `${pessoa}`);

    });
</script>