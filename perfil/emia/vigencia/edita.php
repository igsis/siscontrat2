<?php
$con = bancoMysqli();

if(isset($_POST['editar'])){
    $idEV = $_POST['idEV'];
    $ano = $_POST['ano'];
    $desc = $_POST['desc'];
    $sqlUpdate = "UPDATE emia_vigencias SET
                    ano = '$ano',
                    descricao = '$desc'
                    WHERE id = '$idEV'";
    if(mysqli_query($con,$sqlUpdate)){
        $mensagem = mensagem("success", "Gravado com sucesso!");
    }else{
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
    }
    $ev = recuperaDados('emia_vigencias', 'id', $idEV);
}

if (isset($_POST['edit'])) {
    $idEV = $_POST['idEVEdit'];
    $ev = recuperaDados('emia_vigencias', 'id', $idEV);
    $ano = $ev['ano'];
    $desc = $ev['descricao'];
}


$_SESSION['idEV'] = $idEV;
?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2>Cadastro de Vigência</h2>
        </div>
        <div class="box box-primary">
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">Vigência</h3>
            </div>
            <form method="post" action="?perfil=emia&p=vigencia&sp=edita" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="ano">Ano: *</label>
                            <input class="form-control" type="number" min="2018" required name="ano" id="ano" value="<?=$ev['ano']?>">
                        </div>
                        <div class="col-md-8">
                            <label for="descricao">Descrição: *</label>
                            <input class="form-control" type="text" required name="desc" id="desc" value="<?=$ev['descricao']?>">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="?perfil=emia&p=vigencia&sp=listagem">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <input type="hidden" name="idEV" value="<?=$idEV?>" id="idEV">
                    <button name="editar" id="editar" type="submit" class="btn btn-primary pull-right">Salvar</button>
            </form>
        </div>
    </section>
</div>

