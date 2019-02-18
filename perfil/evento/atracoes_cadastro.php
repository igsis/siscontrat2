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
                    <form method="POST" action="?perfil=evento&p=atracoes_edita" role="form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nome_atracao">Nome da atração *</label>
                                <input type="text" id="nome_atracao" name="nome_atracao" class="form-control" maxlength="100" required>
                            </div>

                            <div class="form-group">
                                <label for="categoria_atracao_id">Categoria da atração *</label>
                                <select class="form-control" id="categoria_atracao_id" name="categoria_atracao_id" required>
                                    <option value="">Selecione...</option>
                                    <?php
                                    geraOpcao("categoria_atracoes")
                                    ?>
                                </select>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-6">
                                    <label for="ficha_tecnica">Ficha técnica completa</label><br/>
                                    <i>Esse campo deve conter a listagem de pessoas envolvidas no espetáculo, como elenco, técnicos, e outros profissionais envolvidos na realização do mesmo.</i>
                                    <p align="justify"><span style="color: gray; "><strong><i>Elenco de exemplo:</strong><br/>Lúcio Silva (guitarra e vocal)<br/>Fabio Sá (baixo)<br/>Marco da Costa (bateria)<br/>Eloá Faria (figurinista)<br/>Leonardo Kuero (técnico de som)</span></i></p>
                                    <textarea id="ficha_tecnica" name="ficha_tecnica" class="form-control" rows="8"></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="integrantes">Integrantes</label><br/>
                                    <i>Esse campo deve conter a listagem de pessoas envolvidas no espetáculo <span style="color: #FF0000; ">incluindo o líder do grupo</span>.<br/>Apenas o <span style="color: #FF0000; ">nome civil, RG e CPF</span> de quem irá se apresentar, excluindo técnicos.</i>
                                    <p align="justify"><span style="color: gray; "><strong><i>Elenco de exemplo:</strong><br/>Ana Cañas RG 00000000-0 CPF 000.000.000-00<br/>Lúcio Maia RG 00000000-0 CPF 000.000.000-00<br/>Fabá Jimenez RG 00000000-0 CPF 000.000.000-00<br/>Fabio Sá RG 00000000-0 CPF 000.000.000-00<br/>Marco da Costa RG 00000000-0 CPF 000.000.000-00</span></i></p>
                                    <textarea id="integrantes" name="integrantes" class="form-control" rows="8"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="classificacao_indicativa_id">Classificação indicativa *</label>
                                <select class="form-control" id="classificacao_indicativa_id" name="classificacao_indicativa_id" required>
                                    <option value="">Selecione...</option>
                                    <?php
                                    geraOpcao("classificacao_indicativas")
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="release_comunicacao">Release</label><br/>
                                <i>Esse campo deve abordar informações relacionadas ao artista, abordando breves marcos na carreira e ações realizadas anteriormente.</i>
                                <p align="justify"><span style="color: gray; "><strong><i>Texto de exemplo:</strong><br/>A cantora e compositora paulistana lançou, em 2007, o seu primeiro disco, "Amor e Caos". Dois anos depois, lançou "Hein?", disco produzido por Liminha e que contou com "Esconderijo", canção composta por Ana, eleita entre as melhores do ano pela revista Rolling Stone e que alcançou repercussão nacional por integrar a trilha sonora da novela "Viver a Vida" de Manoel Carlos, na Rede Globo. Ainda em 2009, grava, a convite do cantor e compositor Nando Reis, a bela canção "Pra Você Guardei o Amor". Em 2012, Ana lança o terceiro disco de inéditas, "Volta", com versões para Led Zeppelin ("Rock'n'Roll") e Edith Piaf ("La Vie en Rose"), além das inéditas autorais "Urubu Rei" (que ganhou clipe dirigido por Vera Egito) e "Será Que Você Me Ama?". Em 2013, veio o primeiro DVD, "Coração Inevitável", registrando o show que contou com a direção e iluminação de Ney Matogrosso.</span></i></p>
                                <textarea id="release_comunicacao" name="release_comunicacao" class="form-control" rows="5"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="links">Links</label><br/>
                                <i>Esse campo deve conter os links relacionados ao espetáculo, ao artista/grupo que auxiliem na divulgação do evento.</i>
                                <p align="justify"><span style="color: gray; "><strong><i>Links de exemplo:</i></strong><br/> https://www.facebook.com/anacanasoficial/<br/>https://www.youtube.com/user/anacanasoficial</span></i></p>
                                <textarea id="links" name="links" class="form-control" rows="5"></textarea>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-6">
                                    <label for="quantidade_apresentacao">Quantidade de Apresentação *</label>
                                    <input type="number" class="form-control" id="quantidade_apresentacao" name="quantidade_apresentacao" maxlength="2" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="valor_individual">Valor *</label> <i>Preencher 0,00 quando não houver valor</i>
                                    <input type="text" id="valor_individual" name="valor_individual" class="form-control" required onKeyPress="return(moeda(this,'.',',',event))">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" name = "cadastra" class="btn btn-info pull-right">Cadastrar</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

    </section>
    <!-- /.content -->
</div>