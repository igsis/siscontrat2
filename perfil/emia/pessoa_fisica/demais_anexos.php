<?php
$con = bancoMysqli();
$conn = bancoPDO();
$idPf = $_POST['idPf'];


if (isset($_POST["enviar"])) {
    $sql_arquivos = "SELECT * FROM lista_documentos WHERE tipo_documento_id IN (1, 11) and publicado = 1";
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
                $new_name = date("YmdHis") . "_" . semAcento($nome_arquivo); //Definindo um novo nome para o arquivo
                $hoje = date("Y-m-d H:i:s");
                $dir = '../uploadsdocs/'; //Diretório para uploads
                $allowedExts = array(".pdf", ".PDF"); //Extensões permitidas
                $ext = strtolower(substr($nome_arquivo, -4));

                if (in_array($ext, $allowedExts)) //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                {
                    if (move_uploaded_file($nome_temporario, $dir . $new_name)) {
                        $sql_insere_arquivo = "INSERT INTO `arquivos` (`origem_id`, `lista_documento_id`, `arquivo`, `data`, `publicado`) VALUES ('$idPf', '$y', '$new_name', '$hoje', '1'); ";
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
                            swal('Erro no upload! Anexar documentos somente no formato PDF.', '', 'error');                             
                        </script>";
                }
            }
        }
    }
}

if (isset($_POST['apagar'])) {
    $idArquivo = $_POST['idArquivo'];
    $sql_apagar_arquivo = "UPDATE arquivos SET publicado = 0 WHERE id = '$idArquivo'";
    if (mysqli_query($con, $sql_apagar_arquivo)) {
        $arq = recuperaDados("arquivos", $idArquivo, "id");
        $mensagem = mensagem("success", "Arquivo " . $arq['arquivo'] . "apagado com sucesso!");
        gravarLog($sql_apagar_arquivo);
    } else {
        $mensagem = mensagem("danger", "Erro ao apagar o arquivo. Tente novamente!");
    }
}

?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Pessoa Física</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Upload de arquivos</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 text-center">
                                <div class="table-responsive list_info"><h4><strong>Update de arquivos somente em
                                            PDF!</strong></h4><br>
                                    <?php
                                    $sql = "SELECT * FROM lista_documentos as list
			                        INNER JOIN arquivos as arq ON arq.lista_documento_id = list.id
                                    WHERE arq.origem_id = '$idPf' AND list.tipo_documento_id IN (1, 11)
                                    AND arq.publicado = '1' ORDER BY arq.id";
                                    $query = mysqli_query($con, $sql);
                                    $linhas = mysqli_num_rows($query);

                                    if ($linhas > 0) {
                                        echo "
                                    <table class='table text-center table-striped table-bordered table-condensed'>
                                        <thead>
                                            <tr class='bg-info text-bold'>
                                                <td>Tipo de arquivo</td>
                                                <td>Nome do documento</td>
                                                <td>Data de envio</td>
                                                <td width='15%'></td>
                                            </tr>
                                        </thead>
                                        <tbody>";
                                        while ($arquivo = mysqli_fetch_array($query)) {
                                                echo "<tr>";
                                                echo "<td class='list_description'> " . $arquivo['documento'] . "</td>";
                                                echo "<td class='list_description'><a href='../uploadsdocs/" . $arquivo['arquivo'] . "' target='_blank'>" . mb_strimwidth($arquivo['arquivo'], 15, 25, "...") . "</a></td>";
                                                echo "<td class='list_description'>(" . exibirDataBr($arquivo['data']) . ")</td>";
                                                echo "
                                          <td class='list_description'>
                                                    <form id='formExcliuir' method='POST'>
                                                        <button class='btn btn-danger glyphicon glyphicon-trash' type='button' data-toggle='modal' data-target='#exclusao' data-nome='" . $arquivo['arquivo'] . "' data-id='" . $arquivo['id'] . "' data-pessoa='1'>
                                                        </button></td>
                                                    </form>";
                                                echo "</tr>";
                                        }
                                        echo "
                                        </tbody>
                                        </table>";
                                    } else {
                                        echo "<p>Não há listas disponíveis no momento.<p/><br/>";
                                    }

                                    ?>
                                </div>
                                <hr/>

                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <br/>
                                        <div class="center">
                                            <form method="POST" action="?perfil=emia&p=pessoa_fisica&sp=demais_anexos"
                                                  enctype="multipart/form-data">
                                                <table class="table text-center table-striped">
                                                    <tbody>
                                                    <tr>
                                                        <h1 class="text-center">Envio de Arquivos</h1>
                                                    </tr>
                                                    <tr>
                                                        <h4 class="text-center">Nesta página, você envia documentos
                                                            digitalizados. O tamanho máximo do arquivo deve ser
                                                            05MB.</h4>
                                                    </tr>
                                                    <?php
                                                    $sql_arquivos = "SELECT * FROM lista_documentos WHERE tipo_documento_id IN (1, 11) and publicado = 1";
                                                    $query_arquivos = mysqli_query($con, $sql_arquivos);
                                                    while ($arq = mysqli_fetch_array($query_arquivos)) {
                                                        $idDoc = $arq['id'];
                                                        $sqlExistentes = "SELECT * FROM arquivos WHERE lista_documento_id = '$idDoc' AND origem_id = '$idPf' AND publicado = 1";
                                                        $queryExistentes = mysqli_query($con, $sqlExistentes);

                                                        if (mysqli_num_rows($queryExistentes) == 0) {

                                                            ?>
                                                            <tr>
                                                                <td><label><?php echo $arq['documento'] ?></label></td>
                                                                <td>
                                                                    <input type='file'
                                                                           name='arquivo[<?php echo $arq['sigla']; ?>]'>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                                <br>
                                                <?php
                                                $num_lista = mysqli_num_rows($query_arquivos);
                                                $num_arquivos = $con->query("SELECT * FROM arquivos WHERE lista_documento_id IN (SELECT id FROM lista_documentos WHERE tipo_documento_id = 1 and publicado = 1) AND origem_id = '$idPf' AND publicado = 1")->num_rows;
                                                $num_total = $num_lista - $num_arquivos;
                                                if($num_total != 0) {
                                                    ?>
                                                    <input type='hidden' name='idPf' value='<?= $idPf ?>'/>
                                                    <input type="submit" class="btn btn-primary btn-lg btn-block"
                                                           name="enviar" value='Enviar'>
                                                    <?php
                                                }
                                                ?>
                                            </form>
                                        </div>
                                    </div>
                                    <!--.modal-->
                                    <div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Confirmação de Exclusão</h4>
                                                </div>
                                            <div class="modal-body text-center">
                                                <p>Tem certeza que deseja excluir este arquivo?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="?perfil=emia&p=pessoa_fisica&sp=demais_anexos" method="post">
                                                <input type="hidden" name="idArquivo" id="idArquivo" value="">
                                                <input type="hidden" name="idPf" id="idPf" value="<?= $idPf ?>">
                                                <input type="hidden" name="apagar" id="apagar">
                                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                                            <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                   <!--  Fim Modal de Upload de arquivo  -->
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <form action="?perfil=emia&p=pessoa_fisica&sp=edita" method="post">
                        <input type="hidden" value="<?= $idPf ?>" name="idPf">
                        <button type="submit" name="carregar" class="btn btn-default pull-left">Voltar</button>
                    </form>
                </div>
            </div>
        </div>
     </div>
   </section>
</div>

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let nome = $(e.relatedTarget).attr('data-nome');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir o arquivo ${nome} ?`);
        $(this).find('#idArquivo').attr('value', `${id}`);
    })
</script>