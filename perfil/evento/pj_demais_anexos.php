<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$idPj = $_POST['idPj'];
$tipoPessoa = 2; // arquivos necessarios para pessoa juridica
$arquivosRepresentantes = "AND list.id NOT IN (23,24,85,86)";

if(isset($_POST["enviar"])) {
    $sql_arquivos = "SELECT * FROM lista_documentos WHERE tipo_documento_id = '$tipoPessoa' and publicado = 1";
    $query_arquivos = mysqli_query($con, $sql_arquivos);
    while ($arq = mysqli_fetch_array($query_arquivos)) {
        $y = $arq['id'];
        $x = $arq['sigla'];
        $nome_arquivo = isset($_FILES['arquivo']['name'][$x]) ? $_FILES['arquivo']['name'][$x] : null;
        $f_size = isset($_FILES['arquivo']['size'][$x]) ? $_FILES['arquivo']['size'][$x] : null;

        //print_r($f_size);
        //echo "<br>";
        //print_r($_FILES);

        if ($f_size > 5242880) {
            $mensagem = mensagem("danger", "<strong>Erro! Tamanho de arquivo excedido! Tamanho máximo permitido: 05 MB.</strong>");
        } else {
            if ($nome_arquivo != "") {
                $nome_temporario = $_FILES['arquivo']['tmp_name'][$x];
                $new_name = date("YmdHis",strtotime("-3 hours")) . "_" . semAcento($nome_arquivo); //Definindo um novo nome para o arquivo
                $hoje = date("Y-m-d H:i:s",strtotime("-3 hours"));
                $dir = '../uploadsdocs/'; //Diretório para uploads
                $allowedExts = array(".pdf", ".PDF"); //Extensões permitidas
                $ext = strtolower(substr($nome_arquivo,-4));

                if(in_array($ext, $allowedExts)) //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                {

                    if (move_uploaded_file($nome_temporario, $dir . $new_name)) {
                        $sql_insere_arquivo = "INSERT INTO `arquivos` (`origem_id`, `lista_documento_id`, `arquivo`, `data`, `publicado`) VALUES ('$idPj', '$y', '$new_name', '$hoje', '1'); ";
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



if(isset($_POST['apagar']))
{
    $idArquivo = $_POST['idArquivo'];
    $sql_apagar_arquivo = "UPDATE arquivos SET publicado = 0 WHERE id = '$idArquivo'";
    if(mysqli_query($con,$sql_apagar_arquivo))
    {
        $arq = recuperaDados("arquivos",$idArquivo,"id");
        $mensagem = mensagem("success", "Arquivo ".$arq['arquivo']."apagado com sucesso!");
        gravarLog($sql_apagar_arquivo);
    }
    else
    {
        $mensagem = mensagem("danger", "Erro ao apagar o arquivo. Tente novamente!");
    }
}

// $campo = recuperaPessoa($_REQUEST['idPessoa'],$_REQUEST['tipoPessoa']);

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Pessoa Jurídica</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Demais anexos</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 text-center">
                                <div class="table-responsive list_info"><h4><strong>Update de arquivos somente em PDF!</strong></h4><br>
                                    <?php
                                    //lista arquivos da pessoa juridica
                                    $sql = "SELECT * FROM lista_documentos as list
			                        INNER JOIN arquivos as arq ON arq.lista_documento_id = list.id
                                    WHERE arq.origem_id = '$idPj' AND list.tipo_documento_id = '$tipoPessoa'
                                    AND arq.publicado = '1' $arquivosRepresentantes ORDER BY arq.id";
                                    $query = mysqli_query($con,$sql);
                                    $linhas = mysqli_num_rows($query);

                                    if ($linhas > 0)
                                    {
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
                                        while($arquivo = mysqli_fetch_array($query))
                                        {
                                            echo "<tr>";
                                            echo "<td class='list_description'> " .$arquivo['documento'] ."</td>";
                                            echo "<td class='list_description'><a href='../uploadsdocs/".$arquivo['arquivo']."' target='_blank'>". mb_strimwidth($arquivo['arquivo'], 15 ,25,"..." )."</a></td>";
                                            echo "<td class='list_description'>(".exibirDataBr($arquivo['data']).")</td>";
                                            echo "
                                          <td class='list_description'>
                                                    <form id='formExcliuir' method='POST'>
                                                        <button class='btn btn-danger glyphicon glyphicon-trash' type='button' data-toggle='modal' data-target='#exclusao' 
                                                        data-nome='" . $arquivo['arquivo'] . "' data-id='". $arquivo['id'] ."' >
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
                                <hr/>

                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <br />
                                        <div class="center">
                                            <form method="POST" action="?perfil=evento&p=pj_demais_anexos" enctype="multipart/form-data">
                                                <table class="table text-center table-striped">
                                                    <tbody>
                                                    <tr>
                                                        <h1 class="text-center">Envio de Arquivos</h1>
                                                    </tr>
                                                    <tr>
                                                        <h4 class="text-center">Nesta página, você envia documentos digitalizados. O tamanho máximo do arquivo deve ser 5MB.</h4>
                                                    </tr>
                                                    <?php

                                                    $sql_arquivos = "SELECT * FROM lista_documentos as list WHERE tipo_documento_id = '$tipoPessoa' and publicado = 1 $arquivosRepresentantes";
                                                    $query_arquivos = mysqli_query($con,$sql_arquivos);
                                                    while($arq = mysqli_fetch_array($query_arquivos))
                                                    {
                                                        $idDoc = $arq['id'];
                                                        $sqlExistentes = "SELECT * FROM arquivos WHERE lista_documento_id = '$idDoc' AND origem_id = '$idPj' AND publicado = 1";
                                                        $queryExistentes = mysqli_query($con, $sqlExistentes);

                                                        if (mysqli_num_rows($queryExistentes) == 0) {
                                                            ?>
                                                            <tr>
                                                                <td><label><?php echo $arq['documento'] ?></label></td>
                                                                <td><input type='file'
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
                                                if(isset($_POST['volta']))
                                                {
                                                    echo "<input type='hidden' name='volta' value='".$_POST['volta']."' />";
                                                }

                                                $num_lista = mysqli_num_rows($query_arquivos);
                                                $num_arquivos = $con->query("SELECT * FROM arquivos WHERE lista_documento_id IN (SELECT id FROM lista_documentos as list WHERE tipo_documento_id = '$tipoPessoa' and publicado = 1 $arquivosRepresentantes) AND origem_id = '$idPj' AND publicado = 1")->num_rows;
                                                $num_total = $num_lista - $num_arquivos;
                                                if($num_total != 0) {
                                                    ?>
                                                    <input type='hidden' name='idPj' value='<?= $idPj ?>'/>
                                                    <input type="hidden" name="tipoPessoa" value="<?= $tipoPessoa; ?>"/>
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
                                                    <form action="?perfil=evento&p=pj_demais_anexos" method="post">
                                                        <input type="hidden" name="idArquivo" id="idArquivo" value="">
                                                        <input type="hidden" name="tipoPessoa" id="tipoPessoa" value="">
                                                        <input type="hidden" name="idPj" id="idPj" value="<?=$idPj?>">
                                                        <input type="hidden" name="apagar" id="apagar">
                                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                                                        </button>
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
                            <form action="?perfil=evento&p=pj_edita" method="post">
                                <input type="hidden" value="<?= $idPj ?>" name="idPj">
                                <button type="submit" name="voltar" class="btn btn-default pull-left">Voltar</button>
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
        let pessoa = $(e.relatedTarget).attr('data-pessoa');

        $(this).find('p').text(`Tem certeza que deseja excluir o arquivo ${nome} ?`);
        $(this).find('#idArquivo').attr('value', `${id}`);
        $(this).find('#tipoPessoa').attr('value', `${pessoa}`);

    })
</script>