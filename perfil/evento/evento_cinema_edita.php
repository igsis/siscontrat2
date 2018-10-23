<?php
    $con = bancoMysqli();
    include "includes/menu_principal.php";

   // if (isset($_POST['carregar'])) {
   //   $idFilme = $_POST['idFilme'];

   //   $query = "SELECT * FROM `filmes` WHERE id = '$idFilme'";
   //   $result = mysqli_query($con,$query);

   //   $result = mysqli_fetch_assoc($result);
   // }
    session_status();
    if (isset($_POST['cadastra']) || isset($_POST['edita'])) {

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

        $mensagem = mensagem("danger", $_POST['tituloFilme']);
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


        if(mysqli_query($con,$sql)){
            $idFilme = recuperaUltimo("filmes");
            $mensagem = mensagem("sucess","Filme criado");
        }else{
            mensagem("danger", die(mysqli_error($con)));
        }
    }

    if (isset($_POST['edita'])){

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


       if (mysqli_query($con,$sql)){
           mensagem("sucess", "Cadastro atualizado!");
       }
       else{
           mensagem("danger","Erro ao atualizar");
       }

    }

    if (isset($_POST['carregar'])){
        $idFilme = $_POST['idFilme'];
        
    }

    $row = recuperaDados("filmes","id", $idFilme);

?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastro de Filme</h2>

        <div class="row" align="center">
            <?php if(isset($mensagem)){echo $mensagem;};?>
        </div>
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar Filme</h3>
                    </div>



                    <form method="POST" action="?perfil=evento&p=evento_cinema_edita" role="form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="tituloFilme">Título do filme *:</label>
                                <input type='text' class='form-control' id='tituloFilme' name='tituloFilme'
                                    maxlength='100' required value='<?= $row['titulo']?>'>
                            </div>
                            <div class="form-group">
                                 <label for="tituloOriginal">Título original:</label>
                                 <input type="text" class="form-control" id="tituloOriginal" name="tituloOriginal" placeholder="Digite o título original" maxlength="100" value='<?= $row['titulo_original']?>'>
                                
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>País de origem *:</label>
                                    <select class="form-control" name="paisOrigem" id="paisOrigem" required>
                                            <option value="">Selecione uma opção...</option>
                                            <?php
                                                geraOpcao("paises", $row['pais_origem_id']);
                                            ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>País de origem (co-produção):</label>
                                    <select class="form-control" name="paisCoProducao" id="paisCoProducao">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                            geraOpcao("paises", $row['pais_origem_coproducao_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="anoProducao">Ano de produção: *</label>
                                    <input type="text" class="form-control" id="anoProducao" name="anoProducao" placeholder="Ex: 1995" maxlength="4" required value="<?= $row['ano_producao'] ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="genero">Gênero:</label>
                                    <input type="text" class="form-control" id="genero" name="genero" placeholder="Digite o Gênero" maxlength="20" value="<?= $row['genero'] ?>"/>
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bitola">Bitola:</label>
                                <input type="text" class="form-control" maxlength="30" id="bitola" name="bitola" value="<?= $row['bitola'] ?>">
                                
                            </div>
                            <div class="form-group">
                                <label for="direcao">Direção:</label>
                                <textarea class="form-control" name="direcao" id="direcao" rows="5"><?= $row['direcao'] ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="sinopse">Sinopse:</label>
                                <textarea class="form-control" name="sinopse" id="sinopse" rows="10"><?= $row['sinopse'] ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="elenco">Elenco:</label>
                                <textarea class="form-control" name="elenco" id="elenco" rows="10"><?= $row['elenco'] ?> </textarea>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="duracao">Duração (em minutos):</label>
                                    <input type="number" class="form-control" name="duracao" id="duracao" value="<?= $row['duracao'] ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="classidicacaoIndicativa">Classificação indicativa: *</label>
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
                            <input type="hidden" name="idFilme" value="<?= $idFilme ?>">
                        </div>



                        <div class="box-footer">
                            <button type="button" class="btn btn-default">Cancel</button>
                            
                            <button type="submit" name="edita" class="btn btn-info pull-right">Alterar</button>
                        </div>
                    </form>
                </div>
            </div>
            </section>
        </div>
