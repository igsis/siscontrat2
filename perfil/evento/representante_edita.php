<?php
$con = bancoMysqli();
$idPj = $_SESSION['idPj'];
$pessoa_juridica = recuperaDados('pessoa_juridicas', 'id', $idPj);

if (isset($_POST['abrirPag'])) {
    $idRepresentante = $_POST['idRepresentante'] ?? $_POST['idPessoa'];
    $tipoRepresentante = $_POST['tipoRepresentante'] ?? $_POST['tipoPessoa'];
}


if (isset($_POST['carregar']) || isset($_POST['apagar']) || isset($_POST['enviar'])) {
    $idRepresentante = $_POST['idRepresentante'] ?? $_POST['idPessoa'];
    $tipoRepresentante = $_POST['tipoRepresentante'] ?? $_POST['tipoPessoa'];

}

if (isset($_POST['cadastra']) || isset($_POST['edita']) || isset($_POST['carregar']) ) {
    $nome = addslashes($_POST['nome']) ?? null;
    $rg = $_POST['rg'];
    $cpf = $_POST['cpf'];
    $tipoRepresentante = $_POST['tipoRepresentante'];
}

if ($tipoRepresentante == 1) {
    $representante = "representante_legal1_id";
    $RG = "23";
    $CPF = "24";
    $siglaRG = "rg_rl";
    $siglaCPF = "cpf_rl";
    $nomeRg = "RG/RNE/PASSAPORTE Representante Legal #1";
    $nomeCpf = "CPF Representante Legal #1";

} else if ($tipoRepresentante == 2) {
    $representante = "representante_legal2_id";
    $RG = "85";
    $CPF = "86";
    $siglaRG = "rg_rl";
    $siglaCPF = "cpf_rl";
    $nomeRg = "RG Representante Legal #2";
    $nomeCpf = "CPF Representante Legal #2";
}

if (isset($_POST['cadastra'])) {
    $sql = "INSERT INTO representante_legais 
                                (nome,
                                 rg, 
                                 cpf) 
                          VALUES ('$nome',
                                  '$rg',
                                  '$cpf')";

    if (mysqli_query($con, $sql)) {
        if (isset($idRepresentante)) {
            if ($idRepresentante != null) {
                $idRepresentante = recuperaUltimo('representante_legais');
            }
        }

        $idRepresentante = recuperaUltimo("representante_legais");

        // salvar o represente na pessoa juridica
        $sqlPessoaJuridica = "UPDATE pessoa_juridicas SET $representante = '$idRepresentante' WHERE id = '$idPj'";
        mysqli_query($con, $sqlPessoaJuridica);

        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        //gravarLog($sql);

    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if (isset($_POST['edita']) || isset($_POST['carregar'])) {
    $idRepresentante = $_POST['idRepresentante'];

    $sql = "UPDATE representante_legais SET
                              nome = '$nome', 
                              rg = '$rg', 
                              cpf = '$cpf' 
                              WHERE id = '$idRepresentante'";
    if (mysqli_query($con, $sql)) {

        if (isset($_POST['edita'])) {
            $mensagem = mensagem("success", "Dados atualizados com sucesso!");

        }elseif (isset($_POST['carregar'])) {

            $sqlSeleciona = "UPDATE pessoa_juridicas SET $representante = '$idRepresentante' WHERE id = '$idPj'";
            mysqli_query($con, $sqlSeleciona);

            echo "<script>swal('Lembre-se de conferir os dados', '', 'warning') </script>";

            $mensagem = mensagem("success", "Representante selecionado com sucesso!");
        }
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao atualizar! Tente novamente.");
        //gravarLog($sql);
    }
}

if (isset($_POST["enviar"])) {
    $idRepresentante = $_POST['idPessoa'];
    $tipoRepresentante = $_POST['tipoPessoa'];
    $tipoPessoa = "2";

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
                $allowedExts = array(".pdf", ".PDF"); //Extensões permitidas
                $ext = strtolower(substr($nome_arquivo, -4));

                if (in_array($ext, $allowedExts)) //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                {
                    if (move_uploaded_file($nome_temporario, $dir . $new_name)) {
                        $sql_insere_arquivo = "INSERT INTO `arquivos` (`origem_id`, `lista_documento_id`, `arquivo`, `data`, `publicado`) VALUES ('$idPj', '$y', '$new_name', '$hoje', '1'); ";

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

$representantes = recuperaDados("representante_legais", "id", $idRepresentante);
include "includes/menu_interno.php";
?>
<script>
    $(document).ready(function () {
        $('#cpf').mask('000.000.000-00', {reverse: true});
    });
</script>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastro de Representante</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações Representante</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <form method="POST" action="?perfil=evento&p=representante_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nome">Nome: </label>
                                    <input type="text" class="form-control" id="nome" name="nome"
                                           maxlength="70" required value="<?= $representantes['nome'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="rg">RG: </label>
                                    <input type="text" class="form-control" id="rg" name="rg" required
                                           value="<?= $representantes['rg'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="cpf">CPF: </label>
                                    <input type="text" class="form-control" id="cpf" name="cpf" required
                                           value="<?= $representantes['cpf'] ?>" data-mask="000.000.000-00" readonly>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <h2 class="text-center"><strong>Upload de arquivos somente em PDF!</strong></h2><br>
                                <div class="col-md-12 text-center">
                                    <div class="form-group col-md-offset-2 col-md-4">
                                        <?php
                                        anexosNaPagina($RG, $idPj, "modal-$siglaRG", $nomeRg);
                                        ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <?php
                                        anexosNaPagina($CPF, $idPj, "modal-$siglaCPF", $nomeCpf);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="idPj" value="<?= $idPj ?>">
                            <input type="hidden" name="idRepresentante" value="<?= $idRepresentante ?>">
                            <input type="hidden" name="tipoRepresentante" value="<?= $tipoRepresentante ?>">
                            <button type="submit" name="edita" id="edita" class="btn btn-info pull-right">
                                Atualizar
                            </button>
                    </form>
                    <form action="?perfil=evento&p=pj_edita" method="post">
                        <button type="submit" name="idPj" id="idPj" value="<?= $idPj ?>" class="btn btn-default">Voltar</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <?php
        modalUploadArquivoUnico("modal-$siglaRG", "?perfil=evento&p=representante_edita", $nomeRg, $siglaRG, $idPj, $tipoRepresentante);
        modalUploadArquivoUnico("modal-$siglaCPF", "?perfil=evento&p=representante_edita", $nomeCpf, $siglaCPF, $idPj, $tipoRepresentante);
        ?>

    </section>
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
            <div class="modal-body text-center">
                <p>Tem certeza que deseja excluir este arquivo?</p>
            </div>
            <div class="modal-footer">
                <form action="?perfil=evento&p=representante_edita" method="post">
                    <input type="hidden" name="idArquivo" id="idArquivo" value="">
                    <input type="hidden" name="idPj" id="idPj" value="<?= $idPj ?>">
                    <input type="hidden" name="tipoRepresentante" value="<?= $tipoRepresentante ?>">
                    <input type="hidden" name="idRepresentante" value="<?= $idRepresentante ?>">
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


<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let nome = $(e.relatedTarget).attr('data-nome');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir o arquivo ${nome} ?`);
        $(this).find('#idArquivo').attr('value', `${id}`);

    });

</script>
