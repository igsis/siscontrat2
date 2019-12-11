<?php
/* Arquivo utilizado para include na importação do proponente caso não exista cadastro no Siscontrat */
?>
<form method="POST" action="?perfil=evento&p=importar_proponente_capac" role="form">
    <div class="box-body">

        <div class="row">
            <div class="form-group col-md-4">
                <label for="contratacao">Haverá contratação?</label> <br>
                <label><input type="radio" name="contratacao"
                              value="1" <?= $eventoCpc['tipo_contratacao_id'] != 5 ? 'checked' : NULL ?>> Sim
                </label>
                <label><input type="radio" name="contratacao"
                              value="0" <?= $eventoCpc['tipo_contratacao_id'] == 5 ? 'checked' : NULL ?>> Não
                </label>
            </div>
            <div class="form-group col-md-4">
                <label for="contratacao">Espaço em que será realizado o evento é público?</label>
                <br>
                <label><input type="radio" name="tipoLugar" value="1" <?= $eventoCpc['espaco_publico'] == 1 ? 'checked' : NULL ?>> Sim </label>&nbsp;&nbsp;
                <label><input type="radio" name="tipoLugar" value="0" <?= $eventoCpc['espaco_publico'] == 0 ? 'checked' : NULL ?>> Não </label>
            </div>

            <div class="form-group col-md-4">
                <label for="tipo">Este evento é cinema?</label> <br>
                <label><input type="radio" name="tipo" value="2">Sim </label>&nbsp;&nbsp;
                <label><input type="radio" name="tipo" value="1" checked>Não </label>
            </div>
            <div class="form-group col-md-4">
                <label for="fomento">É fomento/programa?</label> <br>
                <label><input type="radio" class="fomento" name="fomento" value="1"
                              id="sim" <?= $eventoCpc['fomento'] == 1 ? 'checked' : NULL ?>> Sim
                </label>&nbsp;&nbsp;
                <label><input type="radio" class="fomento" name="fomento" value="0"
                              id="nao" <?= $eventoCpc['fomento'] == 0 ? 'checked' : NULL ?>> Não
                </label>
            </div>
            <div class="form-group col-md-4">
                <label for="tipoFomento">Fomento/Programa </label> <br>
                <select class="form-control" name="tipoFomento" id="tipoFomento">
                    <option value="">Selecione uma opção...</option>
                    <?php
                    geraOpcao("fomentos", $fomento['fomento_id']);
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="nomeEvento">Nome do evento *</label>
            <input type="text" class="form-control" id="nomeEvento" name="nomeEvento"
                   maxlength="240" required value="<?= $eventoCpc['nome_evento'] ?>">
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="relacaoJuridica">Tipo de relação jurídica *</label>
                <select class="form-control" name="relacaoJuridica" id="relacaoJuridica" required>
                    <option value="">Selecione uma opção...</option>
                    <?php
                    geraOpcao("relacao_juridicas", $eventoCpc['relacao_juridica_id']);
                    ?>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="projetoEspecial">Projeto Especial *</label>
                <select class="form-control" id="projetoEspecial" name="projetoEspecial" required>
                    <option value="">Selecione uma opção...</option>
                    <?php
                    geraOpcaoPublicado("projeto_especiais", $eventoCpc['projeto_especial_id']);
                    ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label for="acao">Público (Representatividade e Visibilidade Sócio-cultural)* <i>(multipla
                        escolha) </i></label>
                <button class='btn btn-default' type='button' data-toggle='modal'
                        data-target='#modalPublico' style="border-radius: 30px;">
                    <i class="fa fa-question-circle"></i></button>
                <div class="row" id="msgEsconde">
                    <div class="form-group col-md-6">
                        <span style="color: red;">Selecione ao menos uma representatividade!</span>
                    </div>
                </div>
                <?php
                geraCheckBox('publicos', 'publico', 'capac_new.evento_publico', 'col-md-6', 'evento_id', 'publico_id', $idCapac);
                ?>
            </div>
        </div>

        <div class="form-group">
            <label for="sinopse">Sinopse *</label><br/>
            <i>Esse campo deve conter uma breve descrição do que será apresentado no evento.</i>
            <p align="justify"><span
                    style="color: gray; "><strong><i>Texto de exemplo:</strong><br/>Ana Cañas faz o show de lançamento do seu quarto disco, “Tô na Vida” (Som Livre/Guela Records). Produzido por Lúcio Maia (Nação Zumbi) em parceria com Ana e mixado por Mario Caldato Jr, é o primeiro disco totalmente autoral da carreira da cantora e traz parcerias com Arnaldo Antunes e Dadi entre outros.</span></i>
            </p>
            <textarea name="sinopse" id="sinopse" class="form-control" rows="5"
                      required><?= $eventoCpc['sinopse'] ?></textarea>
        </div>

        <div class="row ">
            <div class="form-group col-md-6">
                <label for="fiscal">Fiscal *</label>
                <select class="form-control" id="fiscal" name="fiscal" required>
                    <option value="">Selecione um fiscal...</option>
                    <?php
                    geraOpcaoUsuario("usuarios", 1, $eventoCpc['fiscal_id']);
                    ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="suplente">Suplente</label>
                <select class="form-control" id="suplente" name="suplente">
                    <option value="">Selecione um suplente...</option>
                    <?php
                    geraOpcaoUsuario("usuarios", 1, $eventoCpc['suplente_id']);
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="box-footer">
        <button type="submit" name="importaCapac" id="cadastra" class="btn btn-info pull-right">Gravar
        </button>
    </div>
</form>