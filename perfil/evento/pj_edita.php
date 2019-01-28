<?php
$con = bancoMysqli();
//$idPessoaJuridica = $_SESSION['idPj_pedido'] ?? null;

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $razao_social = addslashes($_POST['razao_social']);
    $cnpj = $_POST['cnpj'];
    $ccm = $_POST['ccm'] ?? NULL;
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cep = $_POST['cep'];
    $logradouro = $_POST['logradouro'];
    $bairro = $_POST['bairro'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'] ?? NULL;
    $uf = $_POST['uf'];
    $cidade = $_POST['cidade'];

}

if (isset($_POST['editProponente'])){
    $idPessoaJuridica = $_POST['idProponente'];
    $_SESSION['idPessoaJuridica'] = $idPessoaJuridica;
    $_SESSION['idPj_pedido'] = $_POST['idProponente'];
}

if (isset($_POST['cadastra'])) {
    $ultima_atualizacao = date('Y-m-d H:i:s');
    $sql = "INSERT INTO pessoa_juridicas 
                                (razao_social,
                                 cnpj, 
                                 ccm,
                                 email,
                                 ultima_atualizacao) 
                          VALUES ('$razao_social',
                                  '$cnpj',
                                  '$ccm',
                                  '$email',
                                  '$ultima_atualizacao')";

    if (mysqli_query($con, $sql)) {
        $idPessoaJuridica = recuperaUltimo('pessoa_juridicas');
        $_SESSION['idPessoaJuridica'] = $idPessoaJuridica;
        $_SESSION['idPj_pedido']  = $idPessoaJuridica;
       

        // cadastrar o telefone de pj
        $sqlTelefone = "INSERT INTO pj_telefones
                                                (pessoa_juridica_id,
                                                 telefone) 
                                          VALUES ('$idPessoaJuridica',
                                                  '$telefone')";
        mysqli_query($con, $sqlTelefone);

        // cadastrar endereco de pj
        $sqlEndereco = "INSERT INTO pj_enderecos
                                                (pessoa_juridica_id,
                                                 logradouro,
                                                 numero,
                                                 complemento,
                                                 bairro,
                                                 cidade,
                                                 uf,
                                                 cep)
                                          VALUES ('$idPessoaJuridica',
                                                  '$logradouro',
                                                  '$numero',
                                                  '$complemento',
                                                  '$bairro',
                                                  '$cidade',
                                                  '$uf',
                                                  '$cep')";

        mysqli_query($con, $sqlEndereco);

        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if (isset($_POST['edita'])) {
    $ultima_atualizacao = date('Y-m-d H:i:s');
    $idPessoaJuridica = $_POST['idPessoaJuridica'];
    $sql = "UPDATE pessoa_juridicas SET
                              razao_social = '$razao_social',
                              cnpj = '$cnpj', 
                              ccm = '$ccm',
                              email = '$email',
                              ultima_atualizacao = '$ultima_atualizacao'
                              WHERE id = '$idPessoaJuridica'";

    $sqlTelefone = "UPDATE pj_telefones SET
                                          telefone = '$telefone'
                                          WHERE pessoa_juridica_id = '$idPessoaJuridica'";

    $sqlEndereco = "UPDATE pj_enderecos SET
                                          cep = '$cep',
                                          logradouro = '$logradouro',
                                          uf = '$uf',
                                          cidade = '$cidade',
                                          bairro = '$bairro',
                                          numero = '$numero',
                                          complemento = '$complemento'
                                          WHERE pessoa_juridica_id = '$idPessoaJuridica'";

    If (mysqli_query($con, $sql) && mysqli_query($con, $sqlTelefone) && mysqli_query($con, $sqlEndereco)) {
        $mensagem = mensagem("success", "Atualizado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao atualizar! Tente novamente.");
        //gravarLog($sql);
    }
}

if (isset($_POST['carregar'])) {
    $idPessoaJuridica = $_POST['idPj'];
    $_SESSION['idPessoaJuridica'] = $idPessoaJuridica;
    $_SESSION['idPj_pedido'] = $_POST['idPj'];
}

if (isset($_POST['inserir'])){
    $idPessoaJuridica = $_SESSION['idPessoaJuridica'];

    $representante = $_SESSION['tipo_representante'];

    if($representante == 1){
        $representante = "representante_legal1_id";
    } else if($representante == 2){
        $representante = "representante_legal2_id";
    }

    $idRepresentante = $_SESSION['idRepresentante'];

    $sqlPessoaJuridica = "UPDATE pessoa_juridicas SET $representante = $idRepresentante WHERE id = '$idPessoaJuridica'";
    if(mysqli_query($con, $sqlPessoaJuridica)){
        $mensagem = mensagem("success", "Representante legal inserido");
        //gravarLog($sql);
    }else{
        $mensagem = $mensagem("danger", "Erro ao inserir presentante");
    }
}

$pessoa_juridica = recuperaDados("pessoa_juridicas", "id", $idPessoaJuridica);
$pj_telefone = recuperaDados("pj_telefones", "pessoa_juridica_id", $idPessoaJuridica);
$pj_endereco = recuperaDados("pj_enderecos", "pessoa_juridica_id", $idPessoaJuridica);
include "includes/menu_pj.php";
?>
<script>
    $(document).ready(function () {
        $("#cep").mask('00000-000', {reverse: true});
        $("#telefone").mask('(00) 0000-00009', {reverse: true});
    });
</script>
<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro Pessoa Jurídica</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações Pessoa Jurídica</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>

                    <form method="POST" action="?perfil=evento&p=pj_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="razao_social">Razão Social: </label>
                                    <input type="text" class="form-control" id="razao_social" name="razao_social"
                                           maxlength="100" required value="<?= $pessoa_juridica['razao_social'] ?>">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="email">Email: </label>
                                    <input type="email" class="form-control" id="email" name="email" maxlength="60"
                                           required value="<?= $pessoa_juridica['email'] ?>">
                                </div>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-4">
                                    <label for="cnpj">CNPJ: </label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj"
                                           required value="<?= $pessoa_juridica['cnpj'] ?>" readonly>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="ccm">CCM: </label>
                                    <input type="text" class="form-control" id="ccm" name="ccm"
                                           value="<?= $pessoa_juridica['ccm'] ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="telefone">Telefone: </label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" required
                                           value="<?= $pj_telefone['telefone'] ?>" data-mask="(00) 0000-00000">
                                </div>
                            </div>

                            <div class="row ">
                                <?php
                                if (isset($pessoa_juridica['representante_legal1_id'])) {
                                    ?>
                                    <div class="form-group col-md-offset-3 col-md-3">
                                        <label for="nome_representante">Representante Legal 1: </label>
                                        <?php
                                        $representante1 = recuperaDados('representante_legais', 'id',
                                            $pessoa_juridica['representante_legal1_id']);
                                        ?>
                                        <input type="text" readonly name="nome_representante" id="nome_representante"
                                               value="
                                <?= $representante1['nome'] ?>" class="form-control">
                                    </div>
                                    <?php
                                }

                                if (isset($pessoa_juridica['representante_legal2_id'])) {
                                    ?>
                                    <div class="form-group col-lg-offset- col-md-3">
                                        <label for="nome_representante">Representante Legal 2: </label>
                                        <?php
                                        $representante2 = recuperaDados('representante_legais', 'id',
                                            $pessoa_juridica['representante_legal2_id']);
                                        ?>
                                        <input type="text" readonly name="nome_representante" id="nome_representante"
                                               value="
                                <?= $representante2['nome'] ?>" class="form-control">
                                    </div>
                                    <?php
                                }
                                ?>


                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="cep">CEP: </label>
                                    <input type="text" class="form-control" id="cep" name="cep"
                                           maxlength="100" required value="<?= $pj_endereco['cep'] ?>" data-mask="00000-000">
                                </div>

                                <div class="form-group col-md-5">
                                    <label for="logradouro">Rua: </label>
                                    <input type="text" class="form-control" id="rua" name="logradouro"
                                           maxlength="200"
                                           readonly value="<?= $pj_endereco['logradouro'] ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="bairro">Bairro:</label>
                                    <input type="text" class="form-control" id="bairro" name="bairro" readonly
                                           value="<?= $pj_endereco['bairro'] ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="numero">Número: </label>
                                    <input type="number" class="form-control" id="numero" name="numero" required
                                           value="<?= $pj_endereco['numero'] ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="complemento">Complemento: </label>
                                    <input type="text" class="form-control" id="complemento" name="complemento"
                                           maxlength="20" value="<?= $pj_endereco['complemento'] ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="cidade">Cidade:</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade" readonly
                                           value="<?= $pj_endereco['cidade'] ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="uf">Estado:</label>
                                    <input type="text" class="form-control" id="estado" name="uf" readonly
                                           value="<?= $pj_endereco['uf'] ?>">
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-default">Cancelar</button>
                                <input type="hidden" name="idPessoaJuridica" value="<?= $idPessoaJuridica ?>">
                                <button type="submit" name="edita" id="edita" class="btn btn-info pull-right">
                                    Atualizar
                                </button>
                            </div>
                    </form>
                    <div class="row">
                        
                        <div class="col-md-6">
                            <?php if ($pessoa_juridica['representante_legal1_id'] == null){ ?>
                            <form method="POST" action="?perfil=evento&p=representante_busca"
                                  role="form">
                                <input type="hidden" name="tipo_representante" id="tipo_representante"
                                       value="1">
                                <button type="submit" name="busca" id="busca"
                                        class="btn btn-block btn-primary btn-lg"> Buscar Representante Legal 1
                                </button>
                            </form>
                            <?php }else{ ?>
                                <form method="post" action="?perfil=evento&p=representante_edita" role="form">
                                    <input type="hidden" name="tipo_representante" id="tipo_representante"
                                           value="1">
                                    <input type="hidden" name="idRepresentante" id="idRepresentante" value="<?= $pessoa_juridica['representante_legal1_id'] ?>">
                                    <button type="submit" name="carregar" id="carregar"
                                            class="btn btn-block btn-primary btn-lg"> Editar Representante Legal 1
                                    </button>
                                </form>
                            <?php } ?>
                        </div>
                        <div class="col-md-6">
                            <form method="POST" action="?perfil=evento&p=representante_busca"
                                  role="form">
                                <input type="hidden" name="tipo_representante" id="tipo_representante"
                                       value="2">
                                <button type="submit" name="busca" id="busca"
                                        class="btn btn-block btn-primary btn-lg"> Buscar Representante Legal 2
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
