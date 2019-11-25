<?php
$con = bancoMysqli();

if(isset($_POST['editar'])){
    $idEC = $_POST['idEC'];
    $cargo = $_POST['cargo'];
    $justificativa = $_POST['justificativa'];
    $sqlUpdate = "UPDATE emia_cargos SET
                    cargo = '$cargo',
                    justificativa = '$justificativa'
                    WHERE id = '$idEC'";
    if(mysqli_query($con,$sqlUpdate)){
        $mensagem = mensagem("success", "Gravado com sucesso!");
    }else{
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
    }
    $ec = recuperaDados('emia_cargos', 'id', $idEC);
}

if(isset($_POST['edit'])){
    $idEC = $_POST['idECEdit'];
    $ec = recuperaDados('emia_cargos', 'id', $idEC);
}


$_SESSION['idPedido'] = $idEC;
?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2>EMIA - Cargos</h2>
        </div>
        <div class="box box-primary">
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">Edição de cargo</h3>
            </div>
            <form method="post" action="?perfil=emia&p=administrativo&sp=cargo&spp=edita" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="cargo">Cargo: *</label>
                            <input class="form-control" type="text" required name="cargo" id="cargo" value="<?=$ec['cargo']?>">
                        </div>
                        <div class="col-md-8">
                            <label for="justificativa">Justificativa: *</label>
                            <input class="form-control" type="text" required name="justificativa" id="justificativa" value="<?=$ec['justificativa']?>">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="?perfil=emia&p=administrativo&sp=cargo&spp=listagem">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <input type="hidden" name="idEC" value="<?=$idEC?>" id="idEC">
                    <button name="editar" id="editar" type="submit" class="btn btn-primary pull-right">Salvar</button>
            </form>
        </div>
    </section>
</div>


