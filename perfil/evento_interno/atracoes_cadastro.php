<?php
include "includes/menu_interno.php";
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Cadastro de Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atração</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="POST" action="?perfil=evento_interno&p=atracoes_edita" role="form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nome_atracao">Nome da atração *</label>
                                <input type="text" id="nome_atracao" name="nome_atracao" class="form-control"
                                       maxlength="100" required>
                            </div>

                            <div class="form-group">
                                <label for="categoria_atracao_id">Categoria da atração *</label>
                                <select class="form-control" id="categoria_atracao_id" name="categoria_atracao_id"
                                        required>
                                    <option value="">Selecione...</option>
                                    <?php
                                    geraOpcao("categoria_atracoes")
                                    ?>
                                </select>
                                <!--                            </div>-->

                                <div class="row ">
                                    <div class="form-group col-md-12">
                                        <label for="ficha_tecnica">Ficha técnica completa</label><br/>
                                        <i>Esse campo deve conter a listagem de pessoas envolvidas no espetáculo, como
                                            elenco, técnicos, e outros profissionais envolvidos na realização do
                                            mesmo.</i>
                                        <p align="justify"><span
                                                    style="color: gray; "><strong><i>Elenco de exemplo:</strong><br/>Lúcio Silva (guitarra e vocal)<br/>Fabio Sá (baixo)<br/>Marco da Costa (bateria)<br/>Eloá Faria (figurinista)<br/>Leonardo Kuero (técnico de som)</span></i>
                                        </p>
                                        <textarea id="ficha_tecnica" name="ficha_tecnica" class="form-control"
                                                  rows="8"></textarea>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="form-group col-md-6">
                                        <label for="classificacao_indicativa_id">Classificação indicativa * </label>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#modal-default"><i class="fa fa-info"></i></button>
                                        <select class="form-control" id="classificacao_indicativa_id"
                                                name="classificacao_indicativa_id" required>
                                            <option value="">Selecione...</option>
                                            <?php
                                            geraOpcao("classificacao_indicativas")
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="quantidade_apresentacao">Quantidade de Apresentação *</label>
                                        <input type="number" class="form-control" id="quantidade_apresentacao"
                                               name="quantidade_apresentacao" maxlength="2" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="release_comunicacao">Release</label><br/>
                                    <i>Esse campo deve abordar informações relacionadas ao artista, abordando breves
                                        marcos na carreira e ações realizadas anteriormente.</i>
                                    <p align="justify"><span
                                                style="color: gray; "><strong><i>Texto de exemplo:</strong><br/>A cantora e compositora paulistana lançou, em 2007, o seu primeiro disco, "Amor e Caos". Dois anos depois, lançou "Hein?", disco produzido por Liminha e que contou com "Esconderijo", canção composta por Ana, eleita entre as melhores do ano pela revista Rolling Stone e que alcançou repercussão nacional por integrar a trilha sonora da novela "Viver a Vida" de Manoel Carlos, na Rede Globo. Ainda em 2009, grava, a convite do cantor e compositor Nando Reis, a bela canção "Pra Você Guardei o Amor". Em 2012, Ana lança o terceiro disco de inéditas, "Volta", com versões para Led Zeppelin ("Rock'n'Roll") e Edith Piaf ("La Vie en Rose"), além das inéditas autorais "Urubu Rei" (que ganhou clipe dirigido por Vera Egito) e "Será Que Você Me Ama?". Em 2013, veio o primeiro DVD, "Coração Inevitável", registrando o show que contou com a direção e iluminação de Ney Matogrosso.</span></i>
                                    </p>
                                    <textarea id="release_comunicacao" name="release_comunicacao"
                                              class="form-control" rows="5"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="links">Links</label><br/>
                                    <i>Esse campo deve conter os links relacionados ao espetáculo, ao artista/grupo
                                        que auxiliem na divulgação do evento.</i>
                                    <p align="justify"><span
                                                style="color: gray; "><strong><i>Links de exemplo:</i></strong><br/> https://www.facebook.com/anacanasoficial/<br/>https://www.youtube.com/user/anacanasoficial</span></i>
                                    </p>
                                    <textarea id="links" name="links" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar
                                </button>
                            </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->
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

    </section>
    <!-- /.content -->
</div>