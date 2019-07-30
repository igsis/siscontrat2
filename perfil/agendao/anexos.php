<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$idEvento = $_SESSION['idEvento'];

if (isset($_POST["enviar"])) {

    foreach ($_FILES as $key => $arquivo) {

        foreach ($arquivo as $key => $dados) {

            for ($i = 0; $i < sizeof($dados); $i++) {
                $arquivos[$i][$key] = $arquivo[$key][$i];
            }
        }
    }
    $i = 1;

    foreach ($arquivos as $file) {
        if ($file['name'] != "") {
            $y = 107;
            $x = $key;
            $nome_arquivo = isset($file['name']) ? $file['name'] : null;
            $f_size = isset($file['size']) ? $file['size'] : null;

            if ($f_size > 6144000) {
                $mensagem = mensagem("danger", "<strong>Erro! Tamanho de arquivo excedido! Tamanho máximo permitido: 6 MB.</strong>");
            } else {
                if ($nome_arquivo != "") {
                    $nome_temporario = $file['tmp_name'];
                    $new_name = date("YmdHis") . "_" . semAcento($nome_arquivo); //Definindo um novo nome para o arquivo
                    $hoje = date("Y-m-d H:i:s");
                    $dir = '../uploadsdocs/'; //Diretório para uploads
                    $ext = strtolower(substr($nome_arquivo, -4));

                    if (move_uploaded_file($nome_temporario, $dir . $new_name)) {
                        $sql_insere_arquivo = "INSERT INTO `arquivos` (`origem_id`, `lista_documento_id`, `arquivo`, `data`, `publicado`) VALUES ('$idEvento', '$y', '$new_name', '$hoje', '1')";

                        if (mysqli_query($con, $sql_insere_arquivo)) {
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

// $campo = recuperaPessoa($_REQUEST['idPessoa'],$_REQUEST['tipoPessoa']);

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Comunicação/Produção</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Arquivos anexados</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 text-center">
                                <div class="table-responsive list_info"><h4><strong>Nesta página você envia os arquivos
                                            como o rider, mapas de cenas e luz, logos de parceiros, programação de
                                            filmes de mostras de cinema, entre outros arquivos destinados à comunicação
                                            e produção.</strong></h4><br>
                                    <?php
                                    //lista arquivos de determinado pedido
                                    $sql = "SELECT * FROM arquivos as arq
                                    WHERE arq.origem_id = '$idEvento' AND lista_documento_id = 107
                                    AND arq.publicado = '1' ORDER BY arq.id";
                                    $query = mysqli_query($con, $sql);
                                    $linhas = mysqli_num_rows($query);

                                    if ($linhas > 0) {
                                        echo "
                                    <table class='table text-center table-striped table-bordered table-condensed'>
                                        <thead>
                                            <tr class='bg-info text-bold'>
                                                <td>Nome do documento</td>
                                                <td>Data de envio</td>
                                                <td width='15%'></td>
                                            </tr>
                                        </thead>
                                        <tbody>";
                                        while ($arquivo = mysqli_fetch_array($query)) {
                                            $NameArquivo = $arquivo['arquivo'];
                                            echo "<tr>";
                                            echo "<td class='list_description'><a href='../uploadsdocs/$NameArquivo' target='_blank'>" . mb_strimwidth($NameArquivo, 15, 25, "...") . "</a></td>";
                                            echo "<td class='list_description'>(" . exibirDataBr($arquivo['data']) . ")</td>";
                                            echo "
                                          <td class='list_description'>
                                                    <form id='formExcliuir' method='POST'>
                                                        <button class='btn btn-danger glyphicon glyphicon-trash' type='button' data-toggle='modal' data-target='#exclusao' data-nome='" . mb_strimwidth($NameArquivo, 15, 25, "...") . "' data-id='" . $arquivo['id'] . "'>
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
                                <?php
                                if ($linhas != 10) {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <br/>
                                            <div class="center">
                                                <form method="POST" action="?perfil=agendao&p=anexos"
                                                      enctype="multipart/form-data">
                                                    <table class="table text-center table-striped">
                                                        <tbody>
                                                        <tr>
                                                            <h1 class="text-center">Envio de Arquivos</h1>
                                                        </tr>
                                                        <tr>
                                                            <h4 class="text-center"><em>O tamanho
                                                                    máximo do arquivo deve ser 6 MB.</em>

                                                                <br><br>Não envie cópias de documentos nesta página.
                                                                Para o
                                                                envio, vá até a área de <a
                                                                        href="?perfil=evento&p=pedido">"Pedidos
                                                                    de contratação"</a> e anexe direto em cada
                                                                contratado.

                                                                <br><br>Em caso de envio de fotografia, considerar as
                                                                seguintes especificações técnicas:
                                                                <br>- formato: horizontal
                                                                <br>- tamanho: mínimo de 300dpi”</h4>
                                                        </tr>
                                                        <tr class="text-center">
                                                            <td class="text-center">
                                                                <?php
                                                                for ($i = 10; $i > $linhas; $i--) {
                                                                    ?>
                                                                    <input type='file' name='arquivo[]'><br>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <br>
                                                    <?php
                                                    if (isset($_POST['volta'])) {
                                                        echo "<input type='hidden' name='volta' value='" . $_POST['volta'] . "' />";
                                                    }
                                                    ?>
                                                    <input type='hidden' name='idEvento' value='<?= $idEvento ?>'/>
                                                    <input type="submit" class="btn btn-primary btn-lg btn-block"
                                                           name="enviar" value='Enviar'>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <!--.modal-->
                                <div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">Confirmação de Exclusão</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>Tem certeza que deseja excluir este arquivo?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="?perfil=agendao&p=anexos"
                                                      method="post">
                                                    <input type="hidden" name="idArquivo" id="idArquivo"
                                                           value="">
                                                    <input type="hidden" name="apagar" id="apagar">
                                                    <button type="button" class="btn btn-default pull-left"
                                                            data-dismiss="modal">Cancelar
                                                    </button>
                                                    <input class="btn btn-danger btn-outline" type="submit"
                                                           name="excluir" value="Apagar">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  Fim Modal de Upload de arquivo  -->
                            </div>
                        </div>
                        <div class="box-footer">
                            <form action="?perfil=evento&p=evento_edita" method="post">
                                <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                                <button type="submit" name="Voltar" class="btn btn-default pull-left">Voltar</button>
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