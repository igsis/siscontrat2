<?php
$con = bancoMysqli();
require "includes/menu_principal.php";
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro do Agendão</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações Gerais</h3>
                    </div>
                    <form method="POST" action="?perfil=agendao&p=evento_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="nomeEvento">Nome do evento *</label>
                                    <input type="text" class="form-control" id="nomeEvento" name="nomeEvento"
                                           placeholder="Digite o nome do evento" maxlength="240" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="projetoEspecial">Projeto Especial *</label>
                                    <select class="form-control" id="projetoEspecial" name="projetoEspecial"
                                            required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcaoPublicado("projeto_especiais", "");
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-12">
                                    <label for="ficha_tecnica">Artistas *</label><br/>
                                    <textarea id="ficha_tecnica" name="ficha_tecnica" class="form-control"
                                              rows="8" required></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="contratacao">Espaço público?</label>
                                    <br>
                                    <label><input type="radio" name="tipoLugar" value="1"> Sim </label>&nbsp;&nbsp;
                                    <label><input type="radio" name="tipoLugar" value="0" checked> Não </label>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="qtdApresentacao">Quantidade de apresentação: *</label>
                                    <input type="number" min="1" class="form-control" name="qtdApresentacao"
                                           id="qtdApresentacao" required>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="fomento">É fomento/programa?</label> <br>
                                    <label><input type="radio" class="fomento" name="fomento" value="1" id="sim"> Sim
                                    </label>&nbsp;&nbsp;
                                    <label><input type="radio" class="fomento" name="fomento" value="0" id="nao"
                                                  checked> Não </label>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="tipoFomento">Fomento/Programa</label> <br>
                                    <select class="form-control" name="tipoFomento" id="tipoFomento">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("fomentos");
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="acao">Ações (Expressões Artístico-culturais) * <i>(multipla
                                            escolha) </i></label>
                                    <button class='btn btn-default' type='button' data-toggle='modal'
                                            data-target='#modalAcoes' style="border-radius: 30px;">
                                        <i class="fa fa-question-circle"></i></button>
                                        <div class="row" id="msgEscondeAcao">
                                        <div class="form-group col-md-12">
                                            <span style="color: red;">Selecione ao menos uma expressões artístico-culturais!</span>
                                        </div>
                                    </div>
                                    <?php
                                        geraCheckBox('acoes', 'acao', 'acao_agendao', 'col-md-6', 'evento_id', 'acao_id', null);
                                    ?>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="acao">Público (Representatividade e Visibilidade Sócio-cultural)* <i>(multipla
                                            escolha) </i></label>
                                    <button class='btn btn-default' type='button' data-toggle='modal'
                                            data-target='#modalPublico' style="border-radius: 30px;">
                                        <i class="fa fa-question-circle"></i></button>
                                        <div class="row" id="msgEscondePublico">
                                        <div class="form-group col-md-6">
                                            <span style="color: red;">Selecione ao menos uma representatividade!</span>
                                        </div>
                                    </div>
                                    <?php
                                        geraCheckBox('publicos', 'publico', 'evento_publico', 'col-md-6', 'evento_id', 'publico_id', null);
                                    ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="classificacao">Classificação indicativa *</label>
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#modal-default"><i class="fa fa-info"></i></button>
                                    <select class="form-control" name="classificacao" id="classificacao" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("classificacao_indicativas");
                                        ?>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sinopse">Sinopse *</label><br/>
                                <i>Esse campo deve conter uma breve descrição do que será apresentado no evento.</i>
                                <p align="justify"><span
                                            style="color: gray; "><strong><i>Texto de exemplo:</strong><br/>Ana Cañas faz o show de lançamento do seu quarto disco, “Tô na Vida” (Som Livre/Guela Records). Produzido por Lúcio Maia (Nação Zumbi) em parceria com Ana e mixado por Mario Caldato Jr, é o primeiro disco totalmente autoral da carreira da cantora e traz parcerias com Arnaldo Antunes e Dadi entre outros.</span></i>
                                </p>
                                <textarea name="sinopse" id="sinopse" class="form-control" rows="5"
                                          required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="links">Links *</label><br/>
                                <i>Esse campo deve conter os links relacionados ao espetáculo, ao artista/grupo
                                    que auxiliem na divulgação do evento.</i>
                                <p align="justify"><span
                                            style="color: gray; "><strong><i>Links de exemplo:</i></strong><br/> https://www.facebook.com/anacanasoficial/<br/>https://www.youtube.com/user/anacanasoficial</span></i>
                                </p>
                                <textarea id="links" name="links" class="form-control" rows="5" required></textarea>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" name="cadastra" id="cadastra" class="btn btn-info pull-right">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


<div class="modal fade" id="modalAcoes" role="dialog" aria-labelledby="lblmodalAcoes" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ações (Expressões Artístico-culturais)</h4>
            </div>
            <div class="modal-body" style="text-align: left;">
                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>Ação</th>
                        <th>Descrição</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sqlConsultaAcoes = "SELECT acao, descricao FROM acoes WHERE publicado = '1' ORDER BY 1";
                    foreach ($con->query($sqlConsultaAcoes)->fetch_all(MYSQLI_ASSOC) as $acao) {
                        ?>
                        <tr>
                            <td><?= $acao['acao'] ?></td>
                            <td><?= $acao['descricao'] ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-theme" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPublico" role="dialog" aria-labelledby="lblmodalPublico" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Público (Representatividade e Visibilidade Sócio-cultural)</h4>
            </div>
            <div class="modal-body" style="text-align: left;">
                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>Público</th>
                        <th>Descrição</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sqlConsultaPublico = "SELECT publico, descricao FROM publicos WHERE publicado = '1' ORDER BY 1";
                    foreach ($con->query($sqlConsultaPublico)->fetch_all(MYSQLI_ASSOC) as $publico) {
                        ?>
                        <tr>
                            <td><?= $publico['publico'] ?></td>
                            <td><?= $publico['descricao'] ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-theme" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<?php @include "../perfil/includes/modal_classificacao.php"?>

<script>
    const btnCadastra = $('#cadastra');
    let checkPublico = false;
    let checkedAcao = false;

    function travaBotao() {
        if (checkPublico && checkedAcao)
            btnCadastra.attr("disabled", false);
        else
            btnCadastra.attr("disabled", true);
    }

    // ACOES
    function desabilitaCheckBox(acoes) {
        if (acoes[8].checked) {
            for (let x = 0; x < acoes.length; x++) {
                if (x !== 8) {
                    acoes[x].disabled = true;
                    acoes[x].checked = false;
                }
            }
        }
    }

    function reabilitaCheckBox(acoes) {
        for (let x = 0; x < acoes.length; x++) {
            acoes[x].disabled = false;
        }
    }

    function validaAcoes() {
        var acoes = $('.acoes');
        var msg = $('#msgEscondeAcao');
        checkedAcao = false;

        for (let x = 0; x < acoes.length; x++) {
            if (acoes[x].checked) {
                if (acoes[8].checked) {
                    desabilitaCheckBox(acoes);
                } else {
                    acoes[8].disabled = true;
                }
                checkedAcao = true;
            }
        }

        if (checkedAcao) {
            msg.hide();
            btnCadastra.removeAttr("data-toggle");
            btnCadastra.removeAttr("data-placement");
            btnCadastra.removeAttr("title");
        } else {
            reabilitaCheckBox(acoes);
            msg.show();
            btnCadastra.attr("data-toggle", "tooltip");
            btnCadastra.attr("data-placement", "left");
            btnCadastra.attr("title", "Selecione pelo menos uma Ação");
        }
        travaBotao();
    }

    //PÚBLICO
    function validaPublico() {
        var publicos = $('.publicos');
        var isMsg = $('#msgEscondePublico');
        checkPublico = false;

        for (let x = 0 ; x < publicos.length; x++) {
            if (publicos[x].checked) {
                checkPublico = true;
            }
        }

        if (checkPublico) {
            isMsg.hide();
            btnCadastra.removeAttr("data-toggle");
            btnCadastra.removeAttr("data-placement");
            btnCadastra.removeAttr("title");
        } else {
            isMsg.show();
            btnCadastra.attr("data-toggle", "tooltip");
            btnCadastra.attr("data-placement", "left");
            btnCadastra.attr("title", "Selecione pelo menos uma Representatividade");
        }

        travaBotao();
    }


    //FOMENTO
    function verificaFomento() {
        if ($('#sim').is(':checked')) {
            $('#tipoFomento')
                .attr('disabled', false)
                .attr('required', true)
        } else {
            $('#tipoFomento')
                .attr('disabled', true)
                .attr('required', false)
        }
    }

    //EXECUTA TUDO
    $('.acoes').on('change', validaAcoes);
    $('.publicos').on('change', validaPublico);
    $('.fomento').on('change', verificaFomento);

    $(document).ready(function () {
        validaAcoes();
        validaPublico();
        verificaFomento();
    })
</script>