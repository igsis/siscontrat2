<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || isset($_POST['edita'])){
    $razao_social =  addslashes($_POST['razao_social']);
    $cnpj = $_POST['cnpj'];
    $ccm = $_POST['ccm'] ?? NULL;
    $email =  $_POST['email'];
    $representante_legal1_id = $_POST['representante_legal1_id'];
    $representante_legal2_id = $_POST['representante_legal2_id'] ?? NULL;
}

if (isset($_POST['cadastra'])) {
    $ultima_atualizacao = date('Y-m-d H:i:s');
    $sql = "INSERT INTO pessoa_juridicas 
                                (razao_social,
                                 cnpj, 
                                 ccm,
                                 email,
                                 representante_legal1_id,
                                 representante_legal2_id,
                                 ultima_atualizacao) 
                          VALUES ('$razao_social',
                                  '$cnpj',
                                  '$ccm',
                                  '$email',
                                  '$representante_legal1_id',
                                  '$representante_legal2_id',
                                  '$ultima_atualizacao')";

    if(mysqli_query($con, $sql))
    {
        $idPessoaJuridica = recuperaUltimo('pessoa_juridicas');
        $_SESSION['idPessoaJuridica'] = $idPessoaJuridica;
        $mensagem = mensagem("success","Cadastrado com sucesso!");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if(isset($_POST['edita'])){
    $ultima_atualizacao = date('Y-m-d H:i:s');
    $idPessoaJuridica = $_POST['idPessoaJuridica'];
    $sql = "UPDATE pessoa_juridicas SET
                              razao_social = '$razao_social',
                              cnpj = '$cnpj', 
                              ccm = '$ccm',
                              email = '$email',
                              representante_legal1_id = '$representante_legal1_id',
                              representante_legal2_id = '$representante_legal2_id',
                              ultima_atualizacao = '$ultima_atualizacao'
                              WHERE id = '$idPessoaJuridica'";
    If(mysqli_query($con,$sql)){
        $mensagem = mensagem("success","Atualizado com sucesso!");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao atualizar! Tente novamente.");
        //gravarLog($sql);
    }
}

if(isset($_POST['carregar'])){
    $idPessoaJuridica = $_POST['idPessoaJuridica'];
    $_SESSION['idPessoaJuridica'] = $idPessoaJuridica;
}

$pessoa_juridica = recuperaDados("pessoa_juridicas","id", $idPessoaJuridica);
include "includes/menu_interno.php";
?>

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
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>

                    <form method="POST" action="?perfil=pessoa_juridica/pj_edita" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="razao_social">Razão Social: </label>
                                    <input type="text" class="form-control" id="razao_social" name="razao_social"
                                           maxlength="100" required value="<?= $pessoa_juridica['razao_social']?>" >
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="email">Email: </label>
                                    <input type="email" class="form-control" id="email" name="email" maxlength="60"
                                           required value="<?= $pessoa_juridica['email']?>">
                                </div>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-6">
                                    <label for="cnpj">CNPJ: </label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj"
                                           required value="<?= $pessoa_juridica['cnpj']?>">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="ccm">CCM: </label>
                                    <input type="text" class="form-control" id="ccm" name="ccm" value="<?= $pessoa_juridica['ccm']?>">
                                </div>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-6">
                                    <label for="representante_legal1_id">Representante Legal 1: </label>
                                    <select name="representante_legal1_id" id="representante_legal1_id" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("representante_legais", $pessoa_juridica['representante_legal1_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="representante_legal2_id">Representante Legal 2: </label>
                                    <select name="representante_legal2_id" id="representante_legal2_id" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("representante_legais", $pessoa_juridica['representante_legal2_id']);
                                        ?>
                                    </select>
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
                </div>
            </div>
        </div>

    </section>
</div>
