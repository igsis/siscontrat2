<?php
    $con = bancoMysqli();
    include "includes/menu_principal.php";
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Evento</h2>
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Inserir Filme</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=evento_cinema" role="form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="tituloFilme">Título do filme *:</label>
                                <input type="text" class="form-control" id="tituloFilme" name="tituloFilme"
                                       placeholder="Digite o título do filme" maxlength="100" required>
                            </div>
                            <div class="form-group">
                                 <label for="tituloOriginal">Título original:</label>
                                 <input type="text" class="form-control" id="tituloOriginal" name="tituloOriginal" placeholder="Digite o título original" maxlength="100">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>País de origem*:</label>
                                    <select class="form-control" name="paisOrigem" id="paisOrigem" required>
                                            <option value="">Selecione uma opção...</option>
                                            <?php
                                                geraOpcaoPublicado("paises", "");
                                            ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>País de origem (co-produção):</label>
                                    <select class="form-control" name="paisCoProducao" id="paisCoProducao">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                            geraOpcaoPublicado("paises", "");
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="anoProducao">Ano de produção:</label>
                                    <input type="text" class="form-control" id="anoProducao" name="anoProducao" placeholder="Ex: 1995" maxlength="4">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="genero">Gênero:</label>
                                    <input type="text" class="form-control" id="genero" name="genero" placeholder="Digite o Gênero" maxlength="20">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bitola">Bitola:</label>
                                <input type="text" class="form-control" maxlength="30" id="bitola" name="bitola">
                            </div>
                            <div class="form-group">
                                <label for="direcao">Direção:</label>
                                <textarea class="form-control" name="direcao" id="direcao" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="sinopse">Sinopse:</label>
                                <textarea class="form-control" name="sinopse" id="sinopse" rows="10"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="elenco">Elenco:</label>
                                <textarea class="form-control" name="elenco" id="elenco" rows="10"></textarea>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="duracao">Duração (em minutos):</label>
                                    <input type="number" class="form-control" name="duracao" id="duracao">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="classidicacaoIndicativa">Classificação indicativa:</label>
                                    <select class="form-control" name="classidicacaoIndicativa" id="classidicacaoIndicativa">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                            geraOpcaoPublicado("classificacao_indicativas", "", "");
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="link">Link para Trailer:</label>
                                <input type="text" class="form-control" name="link" id="link" placeholder="Cole aqui o link para o trailer">
                            </div>

                        </div>



                        <div class="box-footer">
                            <button type="submit" class="btn btn-default">Cancel</button>
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php

if (isset($_POST['cadastra'])){

    $tituloFilme = $_POST['tituloFilme'];
    $tituloOriginal = $_POST['tituloOriginal'];
    $paisOrigem = $_POST['paisOrigem'];
    $paisCoProducao = $_POST['paisCoProducao'];
    $anoProducao = $_POST['anoProducao'];
    $genero = $_POST['genero'];
    $bitola = $_POST['bitola'];
    $direcao = $_POST['direcao'];
    $sinopse = $_POST['sinopse'];
    $elenco = $_POST['elenco'];
    $duracao = $_POST['duracao'];
    $classidicacaoIndicativa = $_POST['classidicacaoIndicativa'];
    $link = $_POST['link'];
}

$sql = "INSERT INTO filmes 
            (titulo,
            titulo_original, 
            ano_producao, 
            genero, 
            bitola, 
            direcao, 
            sinopse, 
            elenco,
            duracao,
            link_trailer,
            classificacao_indicativa_id,
            pais_origem_id,
            pais_origem_coproducao_id
            )
            VALUES
            (
              '$tituloFilme',
              '$tituloOriginal',
              '$anoProducao',
              '$genero',
              '$bitola',
              '$direcao',
              '$sinopse',
              '$elenco',
              '$duracao',
              '$link',
              '$classidicacaoIndicativa',
              '$paisOrigem',
              '$paisCoProducao'
            )";

?>
</div>