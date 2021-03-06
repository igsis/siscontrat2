<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

$idEvento = $_SESSION['idEvento'];

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {

    $tituloFilme = trim($_POST['tituloFilme']);
    $tituloOriginal = trim(addslashes($_POST['tituloOriginal']));
    $paisOrigem = addslashes($_POST['paisOrigem']);
    $paisCoProducao = addslashes($_POST['paisCoProducao']);
    $anoProducao = $_POST['anoProducao'];
    $genero = trim(addslashes($_POST['genero']));
    $bitola = trim(addslashes($_POST['bitola']));
    $direcao = trim(addslashes($_POST['direcao']));
    $sinopse = trim(addslashes($_POST['sinopse']));
    $elenco = trim(addslashes($_POST['elenco']));
    $duracao = $_POST['duracao'];
    $classidicacaoIndicativa = $_POST['classidicacaoIndicativa'];
    $link = trim(addslashes($_POST['link']));
}

if (isset($_POST['cadastra'])) {
    $sql = "INSERT INTO `filmes`
                (titulo, titulo_original, ano_producao,
                  genero, bitola, direcao,
                  sinopse, elenco, duracao,
                  link_trailer, classificacao_indicativa_id, pais_origem_id,
                  pais_origem_coproducao_id)
                VALUES ('$tituloFilme','$tituloOriginal','$anoProducao',
                          '$genero','$bitola','$direcao',
                          '$sinopse','$elenco','$duracao',
                          '$link','$classidicacaoIndicativa',
                          '$paisOrigem','$paisCoProducao');";
    //echo $sql;
    if (mysqli_query($con, $sql)) {
        $idFilme = recuperaUltimo("filmes");
        $sql = "INSERT INTO `filme_eventos` (filme_id, evento_id)
                VALUES('$idFilme','$idEvento')";
        mysqli_query($con, $sql);
        $mensagem = mensagem("success", "Filme gravado com sucesso.");
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar o filme. Tente novamente.");
    }
}

if(isset($_POST['adicionar'])){
    $idFilme = $_POST['idFilme'];

    $sql = "INSERT INTO `filme_eventos` (filme_id, evento_id) VALUES('$idFilme','$idEvento')";
    if(mysqli_query($con, $sql)){
        $mensagem = mensagem("success", "Filme adicionado com sucesso ao evento.");
    }else{
        $mensagem = mensagem("danger", "Erro ao adicionar o filme ao evento.");

    }
}

if (isset($_POST['edita'])) {
    $idFilme = $_POST['idFilme'];
    $sql = " UPDATE `filmes`
                SET  titulo = '$tituloFilme',
                     titulo_original = '$tituloOriginal',
                     ano_producao = '$anoProducao',
                     genero = '$genero',
                     bitola = '$bitola',
                     direcao = '$direcao',
                     sinopse = '$sinopse',
                     elenco = '$elenco',
                     duracao = '$duracao',
                     link_trailer = '$link',
                     classificacao_indicativa_id = '$classidicacaoIndicativa',
                     pais_origem_id = '$paisOrigem',
                     pais_origem_coproducao_id = '$paisCoProducao'
                 WHERE id = '$idFilme'";
    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Cadastro atualizado!");
    } else {
        $mensagem = mensagem("danger", "Erro ao atualizar! Tente novamente.");
    }
}

if(isset($_POST['apagar'])){
    $idFilme = $_POST['idFilme'];
    $sql_apaga = $con->query("UPDATE filmes SET publicado = 0 WHERE id = '$idFilme'");
    if($sql_apaga){
        $sql_filme_evento = $con->query("DELETE FROM filme_eventos WHERE filme_id = '$idFilme' AND evento_id = '$idEvento'");
        $mensagem = mensagem("success", "Filme apagado com sucesso!");
        echo "<meta http-equiv='refresh' content='3;url=?perfil=evento&p=evento_cinema_lista' />";
    }else{
        $mensagem = mensagem("danger", "Erro ao apagar filme");
    }
}

if (isset($_POST['carregar'])) {
    $idFilme = $_POST['idFilme'];
}

$row = recuperaDados("filmes", "id", $idFilme);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastro de Filme</h2>

        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar Filme</h3>
                    </div>

                    <form method="POST" action="?perfil=evento&p=evento_cinema_edita" role="form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="tituloFilme">Título do filme: *</label>
                                <input type='text' class='form-control' id='tituloFilme' name='tituloFilme' maxlength='100' value='<?= $row['titulo'] ?>' readonly>
                            </div>
                            <div class="form-group">
                                <label for="tituloOriginal">Título original:</label>
                                <input type="text" class="form-control" id="tituloOriginal" name="tituloOriginal" placeholder="Digite o título original" maxlength="100" value='<?= $row['titulo_original'] ?>'>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>País de origem: *</label>
                                    <select class="form-control" name="paisOrigem" id="paisOrigem"  value="">
                                        <option  value="" >Selecione uma opção... </option>
                                        <?php
                                        geraOpcao("paises", $row['pais_origem_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>País de origem (co-produção):</label>
                                    <select class="form-control" name="paisCoProducao" id="paisCoProducao">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("paises", $row['pais_origem_coproducao_id']);
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="anoProducao">Ano de produção: *</label>
                                    <input type="text" class="form-control" id="anoProducao"  min="1" name="anoProducao" placeholder="Ex: 1995" maxlength="4" value="<?= $row['ano_producao'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="genero">Gênero:</label>
                                    <input type="text" class="form-control" id="genero" name="genero" placeholder="Digite o Gênero" maxlength="20" value="<?= $row['genero'] ?>" pattern="[a-zA-ZàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇáéíóúýÁÉÍÓÚÝ ]{1,20}" title="Apenas letras"> 
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="bitola">Bitola:</label>
                                    <input type="text" class="form-control" maxlength="30" id="bitola" name="bitola" value="<?= $row['bitola'] ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="direcao">Direção: *</label>
                                <textarea class="form-control" required name="direcao" id="direcao" rows="5"><?= $row['direcao'] ?></textarea>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="sinopse">Sinopse:</label>
                                    <textarea class="form-control" name="sinopse" id="sinopse" rows="10"><?= $row['sinopse'] ?></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="elenco">Elenco:</label>
                                    <textarea class="form-control" name="elenco" id="elenco" rows="10"><?= $row['elenco'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="duracao">Duração (em minutos): *</label>
                                    <input type="number" class="form-control" name="duracao" min="1" id="duracao" required value="<?= $row['duracao'] ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="classidicacaoIndicativa" required>Classificação indicativa: *</label>
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#modal-default"><i class="fa fa-info"></i></button>
                                    <select class="form-control" name="classidicacaoIndicativa" id="classidicacaoIndicativa">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("classificacao_indicativas", $row['classificacao_indicativa_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="link">Link para Trailer:</label>
                                <input type="text" class="form-control" name="link" id="link" placeholder="Cole aqui o link para o trailer" value="<?= $row['link_trailer'] ?>">
                            </div>
                        </div>

                        <div class="box-footer">
                            <a href="?perfil=evento&p=evento_cinema_lista">
                                <button type="button" class="btn btn-info pull-left">Voltar</button>
                            </a>
                            <input type="hidden" name="idFilme" value="<?= $row['id'] ?>">
                            <button type="submit" name="apagar" class="btn btn-danger pull-left"><i class="fa fa-trash"></i> Apagar</button>
                            <button type="submit" name="edita" class="btn btn-info pull-right">Gravar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


<?php @include "../perfil/includes/modal_classificacao.php"?>
