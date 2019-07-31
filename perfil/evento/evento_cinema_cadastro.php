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
                                <label>País de origem*:</label>
                                <select class="form-control" name="paisOrigem" id="paisOrigem" required>
                                    <option value="1">Selecione uma opção...</option>
                                    <?php
                                    geraOpcao("paises");
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>País de origem (co-produção):</label>
                                <select class="form-control" name="paisCoProducao" id="paisCoProducao">
                                    <option value="">Selecione uma opção...</option>
                                    <?php
                                    geraOpcao("paises");
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="anoProducao">Ano de produção: *</label>
                                <input type="number" class="form-control" id="anoProducao" name="anoProducao" placeholder="Ex: 1995" maxlength="4" required>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="genero">Gênero:</label>
                                <input type="text" class="form-control" id="genero" name="genero" placeholder="Digite o Gênero" maxlength="20">
                            </div>

                            <div class="form-group col-md-2">
                                <label for="bitola">Bitola:</label>
                                <input type="text" class="form-control" maxlength="30" id="bitola" name="bitola">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="direcao">Direção:</label>
                            <textarea class="form-control" name="direcao" id="direcao" rows="5"></textarea>
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
                                <label for="duracao">Duração (em minutos):</label>
                                <input type="number" class="form-control" name="duracao" id="duracao">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="classidicacaoIndicativa">Classificação indicativa: *</label>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modal-default"><i class="fa fa-info"></i></button>
                                <select class="form-control" name="classidicacaoIndicativa" id="classidicacaoIndicativa">
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
