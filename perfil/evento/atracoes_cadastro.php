<?php
include "includes/menu_interno.php";

/**
 * @var int $idEvento
 * @var mysqli $con
 */

$_SESSION['idEvento'] = $idEvento;
$evento = $con->query("SELECT contratacao FROM eventos WHERE id ='$idEvento'")->fetch_array();

$sqlConsultaAcoes = "SELECT acao, descricao FROM acoes WHERE publicado = '1' ORDER BY 1";
$acoes = $con->query($sqlConsultaAcoes)->fetch_all(MYSQLI_ASSOC);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastro de Atração</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atração</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=atracoes_edita" role="form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nome_atracao">Nome da atração *</label>
                                <input type="text" id="nome_atracao" name="nome_atracao" class="form-control"
                                       maxlength="100" required>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="acao">Ações (Expressões Artístico-culturais) * <i>(multipla escolha) </i></label>
                                    <button class='btn btn-default' type='button' data-toggle='modal' data-target='#modalAcoes' style="border-radius: 30px;">
                                        <i class="fa fa-question-circle"></i></button>
                                    <div class="row" id="msgEsconde">
                                        <div class="form-group col-md-12">
                                            <span style="color: red;">Selecione ao menos uma expressões artístico-culturais!</span>
                                        </div>
                                    </div>
                                    <?php
                                        geraCheckBox('acoes', 'acao', 'acao_atracao', 'col-md-6', 'atracao_id', 'acao_id', null);
                                    ?>
                                </div>
                            </div>
                            <div class="row" id="row-ficha">
                                <div class="form-group col-md-12">
                                    <label for="ficha_tecnica">Ficha técnica completa *</label><br/>
                                    <i>Esse campo deve conter a listagem de pessoas envolvidas no espetáculo, como
                                        elenco, técnicos, e outros profissionais envolvidos na realização do
                                        mesmo.</i>
                                    <p align="justify">
                                        <span style="color: gray; ">
                                            <strong><i>Elenco de exemplo:</strong><br/>Lúcio Silva (guitarra e vocal)<br/>Fabio Sá (baixo)<br/>Marco da Costa (bateria)<br/>Eloá Faria (figurinista)<br/>Leonardo Kuero (técnico de som)</span></i>
                                    </p>
                                    <textarea id="ficha_tecnica" name="ficha_tecnica" class="form-control"
                                              rows="8" required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
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

                            <div class="form-group">
                                <label for="release_comunicacao">Release *</label><br/>
                                <i>Esse campo deve abordar informações relacionadas ao artista, abordando breves
                                    marcos na carreira e ações realizadas anteriormente.</i>
                                <p align="justify"><span style="color: gray; "><strong><i>Texto de exemplo:</strong><br/>A cantora e compositora paulistana lançou, em 2007, o seu primeiro disco, "Amor e Caos". Dois anos depois, lançou "Hein?", disco produzido por Liminha e que contou com "Esconderijo", canção composta por Ana, eleita entre as melhores do ano pela revista Rolling Stone e que alcançou repercussão nacional por integrar a trilha sonora da novela "Viver a Vida" de Manoel Carlos, na Rede Globo. Ainda em 2009, grava, a convite do cantor e compositor Nando Reis, a bela canção "Pra Você Guardei o Amor". Em 2012, Ana lança o terceiro disco de inéditas, "Volta", com versões para Led Zeppelin ("Rock'n'Roll") e Edith Piaf ("La Vie en Rose"), além das inéditas autorais "Urubu Rei" (que ganhou clipe dirigido por Vera Egito) e "Será Que Você Me Ama?". Em 2013, veio o primeiro DVD, "Coração Inevitável", registrando o show que contou com a direção e iluminação de Ney Matogrosso.</span></i>
                                </p>
                                <textarea id="release_comunicacao" name="release_comunicacao" class="form-control"
                                          rows="5" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="links">Links</label><br/>
                                <i>Esse campo deve conter os links relacionados ao espetáculo, ao artista/grupo que
                                    auxiliem na divulgação do evento.</i>
                                <p align="justify"><span
                                            style="color: gray; "><strong><i>Links de exemplo:</i></strong><br/> https://www.facebook.com/anacanasoficial/<br/>https://www.youtube.com/user/anacanasoficial</span></i>
                                </p>
                                <textarea id="links" name="links" class="form-control" rows="5"></textarea>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-6">
                                    <label for="quantidade_apresentacao">Quantidade de Apresentação *</label>
                                    <input type="number" class="form-control" min="1" id="quantidade_apresentacao"
                                           name="quantidade_apresentacao" max="100" maxlength="2" required>
                                </div>
                                <?php if ($evento['contratacao'] == 1): ?>
                                    <div class="form-group col-md-6">
                                        <label for="valor_individual">Valor *</label> <i>Preencher 0,00 quando não
                                            houver valor</i>
                                        <input type="text" id="valor_individual" name="valor_individual"
                                               class="form-control" required>
                                    </div>
                                <?php else: ?>
                                    <input type="hidden" name="valor_individual" value="0,00">
                                <?php endif; ?>
                            </div>
                            <div class="row" id="msg">
                                <div class="col-md-12">
                                    <span style="color: red;" class="pull-right"><b>Valor máximo permitido 999.999,99</b></span>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" name="cadastra" id="cadastra" class="btn btn-info pull-right">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php @include "../perfil/includes/modal_classificacao.php"?>

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
                    <?php foreach ($acoes as $acao): ?>
                        <tr>
                            <td><?= $acao['acao'] ?></td>
                            <td><?= $acao['descricao'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-theme" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
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
        var msg = $('#msgEsconde');
        var checked = false;
        var btnCadastra = $('#cadastra');
        let fichaTecnica = $('#row-ficha');


        for (let x = 0; x < acoes.length; x++) {
            if (acoes[x].checked) {
                if (acoes[8].checked) {
                    desabilitaCheckBox(acoes);
                    fichaTecnica.hide()
                } else if (!acoes[8].checked) {
                    acoes[8].disabled = true;
                    fichaTecnica.show()
                }
                checked = true;
            }
        }

        if (checked) {
            msg.hide();
            btnCadastra.attr("disabled", false);
            btnCadastra.removeAttr("data-toggle");
            btnCadastra.removeAttr("data-placement");
            btnCadastra.removeAttr("title");
        } else {
            reabilitaCheckBox(acoes);
            msg.show();
            btnCadastra.attr("disabled", true);
            btnCadastra.attr("data-toggle", "tooltip");
            btnCadastra.attr("data-placement", "left");
            btnCadastra.attr("title", "Selecione pelo menos uma Ação");
        }
    }

    $('.acoes').on('change', validaAcoes);

    $('#msg').hide();

    function limitaValor(){
        var msg = $('#msg');
        var maxLength = $('#valor_individual').val().length;
        var btn = $('#cadastra');
        btn.attr('disabled', true);
        if (maxLength > 10) {
            msg.show();
            btn.attr('disabled', true);
        }else{
            msg.hide();
            validaAcoes();
        }
    }

    $('#valor_individual').keyup(limitaValor);

    $(document).ready(function () {
        validaAcoes();

        $('#valor_individual').mask('00.000,00',{reverse: true})
    })

</script>