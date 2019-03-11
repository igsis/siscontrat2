<?php
$con = bancoMysqli();
require "includes/menu_principal.php";
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Evento Interno</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações Gerais</h3>
                    </div>
                    <form method="POST" action="?perfil=evento_interno&p=evento_edita" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="nomeEvento">Nome do evento *</label>
                                    <input type="text" class="form-control" id="nomeEvento" name="nomeEvento"
                                           placeholder="Digite o nome do evento" maxlength="240" required>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="projetoEspecial">Projeto Especial *</label>
                                        <select class="form-control" id="projetoEspecial" name="projetoEspecial"
                                                required>
                                            <option value="">Selecione uma opção...</option>
                                            <?php
                                            geraOpcaoPublicado("projeto_especiais", "");
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="fiscal">Fiscal *</label>
                                        <select class="form-control" id="fiscal" name="fiscal" required>
                                            <option value="">Selecione um fiscal...</option>
                                            <?php
                                            geraOpcaoUsuario("usuarios", 1, "");
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="sinopse">Sinopse *</label><br/>
                                    <i>Esse campo deve conter uma breve descrição do que será apresentado no evento.</i>
                                    <p align="justify"><span style="color: gray; "><strong><i>Texto de exemplo:</strong><br/>Ana Cañas faz o show de lançamento do seu quarto disco, “Tô na Vida” (Som Livre/Guela Records). Produzido por Lúcio Maia (Nação Zumbi) em parceria com Ana e mixado por Mario Caldato Jr, é o primeiro disco totalmente autoral da carreira da cantora e traz parcerias com Arnaldo Antunes e Dadi entre outros.</span></i>
                                    </p>
                                    <textarea name="sinopse" id="sinopse" class="form-control" rows="5"
                                              required></textarea>
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
