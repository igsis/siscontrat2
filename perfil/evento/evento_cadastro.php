<?php
$con = bancoMysqli();
include "includes/menu_interno.php";
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Infoemações Gerais</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=evento_edita" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="original">É um evento original?</label> <br>
                                    <label><input type="radio" name="original" value="1" checked> Sim </label>
                                    <label><input type="radio" name="original" value="0"> Não </label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="contratacao">Haverá contratação?</label> <br>
                                    <label><input type="radio" name="contratacao" value="1" checked> Sim </label>
                                    <label><input type="radio" name="contratacao" value="0"> Não </label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo">Tipo do Evento</label>
                                    <select class="form-control" id="tipo" name="tipo" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("tipo_eventos", "");
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nomeEvento">Nome do evento</label>
                                <input type="text" class="form-control" id="nomeEvento" name="nomeEvento"
                                       placeholder="Digite o nome do evento" maxlength="240" required>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="relacaoJuridica">Tipo de relação jurídica</label>
                                    <select class="form-control" name="relacaoJuridica" id="relacaoJuridica">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("relacao_juridicas", "");
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="projetoEspecial">Projeto Especial</label>
                                    <select class="form-control" id="projetoEspecial" name="projetoEspecial">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcaoPublicado("projeto_especiais", "");
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sinopse">Sinopse</label><br/>
                                <i>Esse campo deve conter uma breve descrição do que será apresentado no evento.</i>
                                <p align="justify"><span style="color: gray; "><strong><i>Texto de exemplo:</strong><br/>Ana Cañas faz o show de lançamento do seu quarto disco, “Tô na Vida” (Som Livre/Guela Records). Produzido por Lúcio Maia (Nação Zumbi) em parceria com Ana e mixado por Mario Caldato Jr, é o primeiro disco totalmente autoral da carreira da cantora e traz parcerias com Arnaldo Antunes e Dadi entre outros.</span></i></p>
                                <textarea name="sinopse" id="sinopse" class="form-control" rows="5"></textarea>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-6">
                                    <label for="fiscal">Fiscal</label>
                                    <select class="form-control" id="fiscal" name="fiscal">
                                        <option value="">Selecione um fiscal...</option>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, "");
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="suplente">Suplente</label>
                                    <select class="form-control" id="suplente" name="suplente">
                                        <option value="">Selecione um suplente...</option>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, "");
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-default">Cancelar</button>
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
