<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

$idEvento = $_SESSION['idEvento'];

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {

    $tituloFilme = $_POST['tituloFilme'];
    $tituloOriginal = addslashes($_POST['tituloOriginal']);
    $paisOrigem = addslashes($_POST['paisOrigem']);
    $paisCoProducao = addslashes($_POST['paisCoProducao']);
    $anoProducao = $_POST['anoProducao'];
    $genero = addslashes($_POST['genero']);
    $bitola = addslashes($_POST['bitola']);
    $direcao = addslashes($_POST['direcao']);
    $sinopse = addslashes($_POST['sinopse']);
    $elenco = addslashes($_POST['elenco']);
    $duracao = $_POST['duracao'];
    $classidicacaoIndicativa = $_POST['classidicacaoIndicativa'];
    $link = addslashes($_POST['link']);
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
    if (mysqli_query($con, $sql)) {
        $idFilme = recuperaUltimo("filmes");
        $sql = "INSERT INTO `filme_eventos`
                    VALUES('$idFilme','$idEvento')";
        mysqli_query($con, $sql);
        $mensagem = mensagem("success", "Filme gravado com sucesso.");
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar o filme. Tente novamente.");
    }
}

if(isset($_POST['adicionar'])){
    $idFilme = $_POST['idFilme'];

    $sql = "INSERT INTO `filme_eventos`
                    VALUES('$idFilme','$idEvento')";
    if(mysqli_query($con, $sql)){
        $mensagem = mensagem("success", "Evento adicionado com sucesso ao evento.");
    }else{
        $mensagem = mensagem("danger", "Erro ao adicionar o filme ao evento.");

    }
}

if (isset($_POST['edita'])) {
    $idFilme = recuperaUltimo("filmes");
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
        $sql = "INSERT INTO `filme_eventos`
                    VALUES('$idFilme','$idEvento')";
        mysqli_query($con, $sql);
        $mensagem = mensagem("success", "Cadastro atualizado!");
    } else {
        $mensagem = mensagem("danger", "Erro ao atualizar! Tente novamente.");
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
                                <label for="tituloFilme">Título do filme *:</label>
                                <input type='text' class='form-control' id='tituloFilme' name='tituloFilme' maxlength='100' required value='<?= $row['titulo'] ?>'>
                            </div>
                            <div class="form-group">
                                <label for="tituloOriginal">Título original:</label>
                                <input type="text" class="form-control" id="tituloOriginal" name="tituloOriginal" placeholder="Digite o título original" maxlength="100" value='<?= $row['titulo_original'] ?>'>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>País de origem *:</label>
                                    <select class="form-control" name="paisOrigem" id="paisOrigem" required>
                                        <option value="">Selecione uma opção...</option>
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
                                    <input type="text" class="form-control" id="anoProducao" name="anoProducao" placeholder="Ex: 1995" maxlength="4" required value="<?= $row['ano_producao'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="genero">Gênero:</label>
                                    <input type="text" class="form-control" id="genero" name="genero" placeholder="Digite o Gênero" maxlength="20" value="<?= $row['genero'] ?>"/>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="bitola">Bitola:</label>
                                    <input type="text" class="form-control" maxlength="30" id="bitola" name="bitola" value="<?= $row['bitola'] ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="direcao">Direção:</label>
                                <textarea class="form-control" name="direcao" id="direcao" rows="5"><?= $row['direcao'] ?></textarea>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="sinopse">Sinopse:</label>
                                    <textarea class="form-control" name="sinopse" id="sinopse" rows="10"><?= $row['sinopse'] ?></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="elenco">Elenco:</label>
                                    <textarea class="form-control" name="elenco" id="elenco" rows="10"><?= $row['elenco'] ?> </textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="duracao">Duração (em minutos):</label>
                                    <input type="number" class="form-control" name="duracao" id="duracao" value="<?= $row['duracao'] ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="classidicacaoIndicativa">Classificação indicativa: *</label>
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
                            <button type="submit" name="edita" class="btn btn-info pull-right">Gravar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- /modal -->
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>Classificação Indicativa</strong></h4>
            </div>
            <div class="modal-body">
                <h4><strong>Informação e Liberdade de Escolha</strong></h4>
                <p align="justify">A Classificação Indicativa é um conjunto de informações sobre o conteúdo de
                    obras audiovisuais e diversões públicas quanto à adequação de horário, local e faixa etária.
                    Ela alerta os pais ou responsáveis sobre a adequação da programação à idade de crianças e
                    adolescentes. É da Secretaria Nacional de Justiça (SNJ), do Ministério da Justiça (MJ), a
                    responsabilidade da Classificação Indicativa de programas TV, filmes, espetáculos, jogos
                    eletrônicos e de interpretação (RPG).</p>
                <p align="justify">Programas jornalísticos ou noticiosos, esportivos, propagandas eleitorais e
                    publicidade, espetáculos circenses, teatrais e shows musicais não são classificados pelo
                    Ministério da Justiça e podem ser exibidos em qualquer horário.</p>
                <p align="justify">Os programas ao vivo poderão ser classificados se apresentarem inadequações,
                    a partir de monitoramento ou denúncia.</p>
                <p align="justify">
                    <strong>Livre:</strong> Não expõe crianças a conteúdos potencialmente prejudiciais. Exibição
                    em qualquer horário.<br>
                    <strong>10 anos:</strong> Conteúdo violento ou linguagem inapropriada para crianças, ainda
                    que em menor intensidade. Exibição em qualquer horário.<br>
                    <strong>12 anos:</strong> As cenas podem conter agressão física, consumo de drogas e
                    insinuação sexual. Exibição a partir das 20h.<br>
                    <strong>14 anos:</strong> Conteúdos mais violentos e/ou de linguagem sexual mais acentuada.
                    Exibição a partir das 21h.<br>
                    <strong>16 anos:</strong> Conteúdos mais violentos ou com conteúdo sexual mais intenso, com
                    cenas de tortura, suicídio, estupro ou nudez total. Exibição a partir das 22h.<br>
                    <strong>18 anos:</strong> Conteúdos violentos e sexuais extremos. Cenas de sexo, incesto ou
                    atos repetidos de tortura, mutilação ou abuso sexual. Exibição a partir das 23h.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->