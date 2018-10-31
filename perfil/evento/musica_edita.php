<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || isset($_POST['edita'])){
    $idAtracao = $_POST['idAtracao'] ?? NULL;
    $idMusica = $_POST['idMusica'] ?? NULL;
    $genero = $_POST['genero'];
    $venda =  ($_POST['venda']);
    $descricao = addslashes($_POST['descricao']);
    }

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO musica (atracao_id, 
                                  genero,
                                  venda,
                                  descricao) 
                          VALUES ('$idAtracao',
                                  '$genero',
                                  '$venda',
                                  '$descricao')";

    if(mysqli_query($con, $sql)) {

        $idMusica = recuperaUltimo("musica");

        $mensagem = mensagem("success","Cadastrado com sucesso!");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if(isset($_POST['edita'])){
    $sql = "UPDATE musica SET
                            genero = '$genero',
                            venda = '$venda',
                            descricao = '$descricao'
                            WHERE id = '$idMusica'";

    if(mysqli_query($con,$sql)){
        $mensagem = mensagem("success","Gravado com sucesso!");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}
if(isset($_POST['carregar'])){
    $idMusica = $_POST['idMusica'];
}

$musica = recuperaDados("musica","id",$idMusica);


include "includes/menu_interno.php";

?>


<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atração - Especificidades de Área</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>
                    <form method="POST" action="?perfil=evento&p=musica_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="gereno">Gênero</label><br/>
                                    <input type="text" name="genero" size="30" value="<?= $musica['genero']?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="venda">Venda de material?</label> <br>
                                    <label><input type="radio" name="venda" value="1" <?= $musica['venda'] == 1 ? 'checked' : NULL ?> > Sim </label>
                                    <label><input type="radio" name="venda" value="0" <?= $musica['venda'] == 0 ? 'checked' : NULL ?> > Não </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-8">
                                    <label for="Descrição">Descrição</label>
                                    <textarea name="sinopse" id="sinopse" class="form-control" rows="5"> <?= $musica['descricao'] ?> </textarea>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-default">Cancelar</button>
                                <input type="hidden" name="idMusica" value="<?= $idMusica ?>">
                                <button type="submit" name="edita" class="btn btn-info pull-right">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


