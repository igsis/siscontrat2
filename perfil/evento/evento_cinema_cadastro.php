<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

$nomeFilme = $_POST['nomeFilme'] ?? NULL;

?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Filme</h2>
        <div class="row">
            <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Inserir Filme</h3>
                </div>
                <form method="POST" action="?perfil=evento&p=evento_cinema_edita" role="form">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="tituloFilme">Título do filme *:</label>
                            <input type="text" class="form-control" id="tituloFilme" name="tituloFilme" placeholder="Digite o título do filme" maxlength="100" required value="<?= $nomeFilme ?>" <?= isset($nomeFilme) ? "readonly" : NULL ?>>
                        </div>
                        <div class="form-group">
                            <label for="tituloOriginal">Título original:</label>
                            <input type="text" class="form-control" id="tituloOriginal" name="tituloOriginal" placeholder="Digite o título original" maxlength="100">
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label
                                >País de origem*:</label>
                                <select required class="form-control" name="paisOrigem" id="paisOrigem" >
                                    <option value="">Selecione uma opção...</option>
                                    <?php
                                    geraOpcao("paises");
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>País de origem (co-produção)* :</label>
                                <select required class="form-control" name="paisCoProducao" id="paisCoProducao">
                                    <option value="">Selecione uma opção...</option>
                                    <?php
                                    geraOpcao("paises");
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="anoProducao">Ano de produção: *</label>
                                <input type="number" class="form-control" id="anoProducao" name="anoProducao" placeholder="Ex: 1995" min="0" maxlength="4" required>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="genero">Gênero:</label>
                                <input type="text" class="form-control" id="genero" name="genero" placeholder="Digite o Gênero" maxlength="20" pattern="[a-zA-ZàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇáéíóúýÁÉÍÓÚÝ ]{1,20}" title="Apenas letras">
                            </div>

                            <div class="form-group col-md-2">
                                <label for="bitola">Bitola:</label>
                                <input type="text" class="form-control" maxlength="30" id="bitola" name="bitola">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="direcao">Direção *:</label>
                            <textarea class="form-control" name="direcao" id="direcao" rows="5" required></textarea>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="sinopse">Sinopse:</label>
                                <textarea class="form-control" name="sinopse" id="sinopse" rows="10"></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="elenco">Elenco:</label>
                                <textarea class="form-control" name="elenco" id="elenco" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="duracao">Duração (em minutos) *:</label>
                                <input type="number" class="form-control" name="duracao" min="1" id="duracao" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="classidicacaoIndicativa">Classificação indicativa: *</label>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modal-default"><i class="fa fa-info"></i></button>
                                <select required class="form-control" name="classidicacaoIndicativa" id="classidicacaoIndicativa">
                                    <option value="">Selecione uma opção...</option>
                                    <?php
                                    geraOpcao("classificacao_indicativas");
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
                        <a href="?perfil=evento&p=evento_edita">
                            <button type="button" class="btn btn-default">Voltar</button>
                        </a>
                        <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </section>
</div>

<?php @include "../perfil/includes/modal_classificacao.php"?>