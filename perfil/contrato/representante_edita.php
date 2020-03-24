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
    $siglaRG = "rg_rl2";
    $siglaCPF = "cpf_rl2";
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

$representantes = recuperaDados("representante_legais", "id", $idRepresentante);

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
                    <form method="POST" action="?perfil=contrato&p=representante_edita" role="form">
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
                            
                            <input type="hidden" name="idPj" value="<?= $idPj ?>">
                            <input type="hidden" name="idRepresentante" value="<?= $idRepresentante ?>">
                            <input type="hidden" name="tipoRepresentante" value="<?= $tipoRepresentante ?>">
                            <button type="submit" name="edita" id="edita" class="btn btn-info pull-right">
                                Atualizar
                            </button>
                    </form>
                    <form action="?perfil=contrato&p=edita_pj" method="post">
                        <button type="submit" name="idPj" id="idPj" value="<?= $idPj ?>" class="btn btn-default">Voltar</button>
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

    });

</script>

