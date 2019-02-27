<?php
$con = bancoMysqli();
$idPessoa = $_POST['idPessoa'];
$tipoPessoa = $_POST['tipoPessoa'];

if(isset($_POST["enviar"]))
{
    $sql_arquivos = "SELECT * FROM arquivos";
    $query_arquivos = mysqli_query($con,$sql_arquivos);
    while($arq = mysqli_fetch_array($query_arquivos))
    {
        $y = $arq['idTipoDoc'];
        $x = $arq['sigla'];
        $nome_arquivo = $_FILES['arquivo']['name'][$x];

        if($nome_arquivo != "")
        {
            $nome_temporario = $_FILES['arquivo']['tmp_name'][$x];
            $new_name = date("YmdHis")."_".semAcento($nome_arquivo); //Definindo um novo nome para o arquivo
            $hoje = date("Y-m-d H:i:s");
            $dir = '../uploadsdocs/'; //Diretório para uploads

            if(move_uploaded_file($nome_temporario, $dir.$new_name))
            {
                $sql_insere_arquivo = "INSERT INTO `arquivos` (`origem_id`, `lista_documento_id`, `arquivo`, `data`, `publicado`) VALUES ('$idPessoa', '$new_name', '$hoje', '1', '$y'); ";
                $query = mysqli_query($con,$sql_insere_arquivo);

                if($query)
                {
                    $mensagem = "Arquivo recebido com sucesso";
                }
                else
                {
                    $mensagem = "Erro ao gravar no banco";
                }

            }
            else
            {
                $mensagem = "Erro no upload";
            }
        }
    }
}


if(isset($_POST['apagar']))
{
    $idArquivo = $_POST['apagar'];
    $sql_apagar_arquivo = "UPDATE arquivos SET publicado = 0 WHERE idArquivosPessoa = '$idArquivo'";
    if(mysqli_query($con,$sql_apagar_arquivo))
    {
        $arq = recuperaDados("arquivos",$idArquivo,"id");
        $mensagem =	"Arquivo ".$arq['arquivo']."apagado com sucesso!";
        gravarLog($sql_apagar_arquivo);
    }
    else
    {
        $mensagem = "Erro ao apagar o arquivo. Tente novamente!";
    }
}

$campo = recuperaPessoa($_REQUEST['idPessoa'],$_REQUEST['tipoPessoa']);

?>

<?php
include 'includes/menu.php';
?>


<section id="list_items" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="section-heading">
                    <h3><?php echo $campo["nome"] ?>  </h3>
                    <h5><?php echo $campo["tipo"] ?></h5>
                    <p>&nbsp;</p>
                    <h4>Arquivos anexados</h4>
                    <p><strong>Se na lista abaixo, o seu arquivo começar com "http://", por favor, clique, grave em seu computador, faça o upload novamente e apague a ocorrência citada.</strong></p>
                </div>
                <div class="table-responsive list_info">
                    <?php if($tipoPessoa == 4){$tipo = 1; } ?>
                    <?php if($tipoPessoa == 2){$tipo = 2; } ?>
                    <?php if($tipoPessoa == 1){$tipo = 1; } ?>
                    <?php //if($tipoPessoa == 3){$tipo = 3; } ?>
                    <?php $pag = "contratos"; ?>
                    <?php listaArquivosPessoaSiscontrat($idPessoa,$tipo,$_SESSION['idPedido'],$p,$pag); ?>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-8">
                        <a href="../perfil/m_contratos/frm_arquivos_todos.php?idPessoa=<?php echo $idPessoa ?>&tipo=<?php echo $tipo ?>" class="btn btn-theme btn-lg btn-block" target="_blank">Baixar todos os arquivos de uma vez</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-offset-2 col-md-8"><h1>&nbsp;</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <hr>
                <div class="section-heading">
                    <h4>Envio de Arquivos</h4>
                    <p><?php if(isset($mensagem)){echo $mensagem;} ?></p>
                    <p>Nesta página, você envia documentos digitalizados. O tamanho máximo do arquivo deve ser 60MB.</p>
                    <br />
                    <div class = "center">
                        <form method="POST" action="?<?php echo $_SERVER['QUERY_STRING'] ?>" enctype="multipart/form-data">
                            <table>
                                <tr>
                                    <td width="50%"><td>
                                </tr>
                                <?php
                                $sql_arquivos = "SELECT * FROM igsis_upload_docs WHERE tipoUpload = '$tipoPessoa'";
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
                            <input type='hidden' name='<?php echo $p; ?>' value='1' />
                            <input type="hidden" name="enviar" value="1"  />
                            <input type="submit" class="btn btn-theme btn-lg btn-block" value='Enviar'>
                        </form>
                    </div>
                    <br />
                    <div class="center">
                        <?php echo $form ?>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8">
                                <input type="hidden" name="idPessoa" value="<?php echo $idPessoa; ?>"  />
                                <input type="hidden" name="tipoPessoa" value="<?php echo $tipoPessoa; ?>"  />
                                <input type="submit" class="btn btn-theme btn-block" value='Voltar ao Cadastro de Pessoa'>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
