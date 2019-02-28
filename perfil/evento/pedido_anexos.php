<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$idPedido = $_SESSION['idPedido'];
//$idPessoa = $_POST['idPessoa'];
$tipoPessoa = $_POST['tipoPessoa'];

if(isset($_POST["enviar"])) {
    $sql_arquivos = "SELECT * FROM lista_documentos WHERE tipo_documento_id = '$tipoPessoa' and publicado = 1";
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

                if (move_uploaded_file($nome_temporario, $dir . $new_name)) {
                    $sql_insere_arquivo = "INSERT INTO `arquivos` (`origem_id`, `lista_documento_id`, `arquivo`, `data`, `publicado`) VALUES ('$idPedido', '$y', '$new_name', '$hoje', '1'); ";
                    $query = mysqli_query($con, $sql_insere_arquivo);

                    if ($query) {
                        $mensagem = mensagem("success", "Arquivo recebido com sucesso");
                        gravarLog($sql_insere_arquivo);
                    } else {
                        $mensagem = mensagem("danger", "Erro ao gravar no banco <br>" . $sql_insere_arquivo);
                    }
                } else {
                    $mensagem = mensagem("danger", "Erro no upload");
                }
            }
        }
    }
}



if(isset($_POST['apagar']))
{
    $idArquivo = $_POST['apagar'];
    $sql_apagar_arquivo = "UPDATE arquivos SET publicado = 0 WHERE id = '$idArquivo'";
    if(mysqli_query($con,$sql_apagar_arquivo))
    {
        $arq = recuperaDados("arquivos",$idArquivo,"id");
        $mensagem = mensagem("success", "Arquivo ".$arq['arquivo']."apagado com sucesso!");
        gravarLog($sql_apagar_arquivo);
    }
    else
    {
        $mensagem = "Erro ao apagar o arquivo. Tente novamente!";
    }
}

// $campo = recuperaPessoa($_REQUEST['idPessoa'],$_REQUEST['tipoPessoa']);

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Pedido</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Arquivos anexados</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-10 text-center col-md-offset-1">
                                <h4><strong>Se na lista abaixo, o seu arquivo começar com "http://", por favor, clique, grave em seu computador, faça o upload novamente e apague a ocorrência citada.</strong></h4>
                                <div class="table-responsive list_info"><h6>Listas de Projetos Aprovados</h6>
                            <?php
                            //lista arquivos de determinado pedido
                            $sql = "SELECT * FROM lista_documentos as list
			                        INNER JOIN arquivos as arq ON arq.lista_documento_id = list.id
                                    WHERE arq.origem_id = '$idPedido'
                                    AND arq.publicado = '1' ORDER BY arq.id";
                            $query = mysqli_query($con,$sql);
                            $linhas = mysqli_num_rows($query);

                            if ($linhas > 0)
                            {
                                echo "
                                    <table class='table table-condensed'>
                                        <thead>
                                            <tr class='list_menu'>
                                                <td>Tipo de arquivo</td>
                                                <td>Nome do documento</td>
                                                <td>Data de envio</td>
                                                <td width='15%'></td>
                                            </tr>
                                        </thead>
                                        <tbody>";
                                while($arquivo = mysqli_fetch_array($query))
                                {
                                    echo "<tr>";
                                    echo "<td class='list_description'> " .$arquivo['documento'] ."</td>";
                                    echo "<td class='list_description'><a href='uploadsdocs/".$arquivo['arquivo']."' target='_blank'>". mb_strimwidth($arquivo['arquivo'], 15 ,25,"..." )."</a></td>";
                                    echo "<td class='list_description'>(".exibirDataHoraBr($arquivo['data']).")</td>";
                                    echo "
                                          <td class='list_description'>
                                                    <form id='apagarArq' method='POST' action='?perfil=evento&p=pedido_anexos'>
                                                        <input type='hidden' name='tipoPessoa' value='".$tipoPessoa."' />
                                                        <input type='hidden' name='apagar' value='".$arquivo['id']."' />
                                                        <button class='btn btn-danger' type='submit' data-toggle='modal' data-target='#confirmApagar' data-title='Remover Arquivo?' data-message='Deseja realmente excluir o arquivo ".$arquivo['arquivo']."?'>Remover
                                                        </button></td>
                                                    </form>";
                                    echo "</tr>";
                                }
                                echo "
                                        </tbody>
                                        </table>";
                            }
                            else
                            {
                                echo "<p>Não há listas disponíveis no momento.<p/><br/>";
                            }

                            ?>
                                </div>

                            <div class="row">
                                <div class="col-md-offset-2 col-md-8">
                                    <hr>
                                    <br />
                                    <div class="center">
                                        <form method="POST" action="?perfil=evento&p=pedido_anexos" enctype="multipart/form-data">
                                            <table class="table text-center table-striped">
                                                <tbody>
                                                <tr>
                                                    <h1 class="text-center">Envio de Arquivos</h1>
                                                </tr>
                                                <tr>
                                                    <h4 class="text-center">Nesta página, você envia documentos digitalizados. O tamanho máximo do arquivo deve ser 60MB.</h4>
                                                </tr>
                                                <?php
                                                $sql_arquivos = "SELECT * FROM lista_documentos WHERE tipo_documento_id = '$tipoPessoa' and publicado = 1";
                                                $query_arquivos = mysqli_query($con,$sql_arquivos);
                                                while($arq = mysqli_fetch_array($query_arquivos))
                                                {
                                                    ?>
                                                    <tr>
                                                        <td><label><?php echo $arq['documento']?></label></td><td><input type='file' name='arquivo[<?php echo $arq['sigla']; ?>]'></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>

                                                </tbody>
                                            </table>
                                            <br>
                                            <input type="hidden" name="idPessoa" value="<?php echo $idPessoa; ?>"  />
                                            <input type="hidden" name="tipoPessoa" value="<?php echo $tipoPessoa; ?>"  />
                                            <?php
                                            if(isset($_POST['volta']))
                                            {
                                                echo "<input type='hidden' name='volta' value='".$_POST['volta']."' />";
                                            }
                                            ?>
                                            <input type='hidden' name='idPedido' value='<?=$idPedido?>' />
                                            <input type="submit" class="btn btn-primary btn-lg btn-block" name="enviar" value='Enviar'>
                                        </form>
                                    </div>
                                </div>
                                <!-- Fim Upload de arquivo -->
                                <!-- Confirmação de Exclusão -->
                                <div class="modal fade" id="confirmApagar" role="dialog" aria-labelledby="confirmApagarLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">Excluir Arquivo?</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>Confirma?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                <button type="button" class="btn btn-danger" id="confirm">Remover</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fim Confirmação de Exclusão -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
