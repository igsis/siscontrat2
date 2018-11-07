<?php
$con = bancoMysqli();

$idPessoaJuridica = $_SESSION['idPessoaJuridica'];

$pessoa_juridica = recuperaDados('pessoa_juridicas', 'id', $idPessoaJuridica);

if (isset($_POST['cadastra']) || isset($_POST['edita'])){
    $nome =  addslashes($_POST['nome']);
    $rg = $_POST['rg'];
    $cpf = $_POST['cpf'];
    $representante = $_SESSION['tipo_representante'];

    if($representante == 1){
        $representante = "representante_legal1_id";
    } else if($representante == 2){
        $representante = "representante_legal2_id";
    }
}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO representante_legais 
                                (nome,
                                 rg, 
                                 cpf ) 
                          VALUES ('$nome',
                                  '$rg',
                                  '$cpf')";

    if(mysqli_query($con, $sql))
    {
        $idRepresentante = recuperaUltimo('representante_legais');
        // salvar o represente na pessoa juridica
        $sqlPessoaJuridica = "UPDATE pessoa_juridicas SET $representante = $idRepresentante WHERE id = '$idPessoaJuridica'";
        mysqli_query($con, $sqlPessoaJuridica);
        $mensagem = mensagem("success","Cadastrado com sucesso!");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if(isset($_POST['edita'])){
    $idRepresentante = $_POST['idRepresentante'];
    $sql = "UPDATE representante_legais SET
                              nome = '$nome', 
                              rg = '$rg', 
                              cpf = '$cpf' 
                              WHERE id = '$idRepresentante'";
    If(mysqli_query($con,$sql)){
        $mensagem = mensagem("success","Atualizado com sucesso!");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao atualizar! Tente novamente.");
        //gravarLog($sql);
    }
}

$representante = recuperaDados("representante_legais","id",$idRepresentante);
include "includes/menu_interno.php";
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Representante</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações Presentante</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>

                    <form method="POST" action="?perfil=pessoa_juridica/representante_edita" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nome">Nome: </label>
                                    <input type="text" class="form-control" id="nome" name="nome"
                                           maxlength="70" required value="<?= $representante['nome']?>" >
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="rg">RG: </label>
                                    <input type="text" class="form-control" id="rg" name="rg" required value="<?= $representante['rg']?>" >
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="cpf">CPF: </label>
                                    <input type="text" class="form-control" id="cpf" name="cpf" required value="<?= $representante['cpf']?>" >
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-default">Cancelar</button>
                                    <input type="hidden" name="idRepresentante" value="<?= $idRepresentante ?>">
                                    <button type="submit" name="edita" id="edita" class="btn btn-info pull-right">Atualizar</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
</div>
