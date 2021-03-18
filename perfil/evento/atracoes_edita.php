<?php
$con = bancoMysqli();

$idEvento = $_SESSION['idEvento'];

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $nome_atracao = trim(addslashes($_POST['nome_atracao']));
    $classificacao_indicativa_id = $_POST['classificacao_indicativa_id'];
    $release_comunicacao = trim(addslashes($_POST['release_comunicacao']));
    $links = trim(addslashes($_POST['links']));
    $quantidade_apresentacao = $_POST['quantidade_apresentacao'];
    $valor_individual = dinheiroDeBr($_POST['valor_individual']);
}
if (isset($_POST['cadastra'])) {
    $sql_atracoes = "INSERT INTO atracoes(evento_id, nome_atracao, classificacao_indicativa_id, release_comunicacao, links, quantidade_apresentacao, valor_individual, publicado) VALUES ('$idEvento','$nome_atracao', '$classificacao_indicativa_id', '$release_comunicacao', '$links', '$quantidade_apresentacao', '$valor_individual', '1')";
    if (mysqli_query($con, $sql_atracoes)) {
        $idAtracao = recuperaUltimo("atracoes");
        $mensagem = mensagem("success", "Cadastrado com sucesso! Retornando a listagem de atrações");

        $sql = "SELECT * FROM pedidos WHERE origem_id = '$idEvento' AND origem_tipo_id = 1 AND publicado = 1";
        $query = mysqli_query($con, $sql);
        $numRow = mysqli_num_rows($query);

        if ($numRow > 0) {
            $sql = "SELECT sum(valor_individual) AS 'valores'  FROM atracoes WHERE publicado = 1 AND evento_id = '$idEvento'";

            $query = mysqli_query($con, $sql);
            $valor = mysqli_fetch_array($query)['valores'];

            $sql = "UPDATE pedidos SET valor_total = '$valor' WHERE origem_id = '$idEvento' AND origem_tipo_id = 1 and publicado = 1";
            if (mysqli_query($con, $sql)) {
                $mensagem3 = mensagem("warning", "Lembre-se de ajustar o valor das parcelas e do equipamento!");
            }
        }

        if (isset($_POST['acao'])) {
            atualizaDadosRelacionamento('acao_atracao', $idAtracao, $_POST['acao'], 'atracao_id', 'acao_id');
        }

        $dataAtual = date("Y-m-d", strtotime("-3 hours"));
        $sqlOcorrencia = "SELECT atr.valor_individual, ocr.data_inicio 
                          FROM ocorrencias ocr 
                          INNER JOIN atracoes atr 
                          ON ocr.origem_ocorrencia_id = atr.id 
                          WHERE atr.nome_atracao LIKE '%$nome_atracao%'";

        $queryOcorrencia = mysqli_query($con, $sqlOcorrencia);

        if (mysqli_num_rows($queryOcorrencia) > 0) {
            $ocrAtracao = mysqli_fetch_assoc($queryOcorrencia);
            $dataInicio = $ocrAtracao['data_inicio'];
            $valorIndividual = $ocrAtracao['valor_individual'];

            if (($dataInicio < $dataAtual) && ($valorIndividual < $valor_individual)) {
                $mensagem2 = mensagem("warning", "Atração atual tem valor acima de outras atrações com os mesmos nomes realizados anteriormente!");
            }
        }

        echo "<meta http-equiv='refresh' content='3;url=?perfil=evento&p=atracoes_lista' />";
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.") . $sql_atracoes;
    }
}

if (isset($_POST['edita'])) {
    $idAtracao = $_POST['idAtracao'];
    $sql_atracoes = "UPDATE atracoes SET nome_atracao = '$nome_atracao', classificacao_indicativa_id = '$classificacao_indicativa_id', release_comunicacao = '$release_comunicacao', links = '$links', quantidade_apresentacao = '$quantidade_apresentacao', valor_individual = '$valor_individual' WHERE id = '$idAtracao'";
    if (mysqli_query($con, $sql_atracoes)) {

        $sql = "SELECT * FROM pedidos WHERE origem_tipo_id = 1 AND origem_id = '$idEvento' AND publicado = 1";
        $query = mysqli_query($con, $sql);
        $numRow = mysqli_num_rows($query);

        if ($numRow > 0) {
            $sql = "SELECT sum(valor_individual) as 'valores' FROM atracoes WHERE publicado = 1 AND evento_id = '$idEvento'";
            $query = mysqli_query($con, $sql);
            $valor = mysqli_fetch_array($query)['valores'];

            $sql = "UPDATE pedidos SET valor_total = '$valor' WHERE origem_id = '$idEvento' AND origem_tipo_id = 1 AND publicado = 1";
            if (mysqli_query($con, $sql)) {
                $mensagem3 = mensagem("warning", "Lembre-se de ajustar o valor das parcelas e do equipamento!");
            }
        }

        if (isset($_POST['acao'])) {
            atualizaDadosRelacionamento('acao_atracao', $idAtracao, $_POST['acao'], 'atracao_id', 'acao_id');
        }
        $mensagem = mensagem("success", "Atualizado com sucesso!");
    } else {
        $mensagem = mensagem("danger", "Erro ao atualizar! Tente novamente.");
    }
}

if (isset($_POST['carregar'])) {
    $idAtracao = $_POST['idAtracao'];
}

$atracao = recuperaDados("atracoes", "id", $idAtracao);

include "includes/menu_interno.php";
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Edição de Atração</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atração</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem2)) {
                            echo $mensagem2;
                        }; ?>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem3)) {
                            echo $mensagem3;
                        }; ?>
                    </div>

                    <form method="POST" action="?perfil=evento&p=atracoes_edita" role="form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nome_atracao">Nome da atração *</label>
                                <input type="text" id="nome_atracao" name="nome_atracao" class="form-control"
                                       maxlength="100" required value="<?= $atracao['nome_atracao'] ?>">
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="acao">Ações (Expressões Artístico-culturais) * <i>(multipla
                                            escolha) </i></label>
                                    <button class='btn btn-default' type='button' data-toggle='modal'
                                            data-target='#modalAcoes' style="border-radius: 30px;">
                                        <i class="fa fa-question-circle"></i></button>
                                    <div class="row" id="msgEsconde">
                                        <div class="form-group col-md-12">
                                            <span style="color: red;">Selecione ao menos uma expressões artístico-culturais!</span>
                                        </div>
                                    </div>
                                    <?php
                                    geraCheckBox('acoes', 'acao', 'acao_atracao', 'col-md-6', 'atracao_id', 'acao_id', $idAtracao);
                                    ?>
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
                                    geraOpcao("classificacao_indicativas", $atracao['classificacao_indicativa_id'])
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="release_comunicacao">Release *</label><br/>
                                <i>Esse campo deve abordar informações relacionadas ao artista, abordando breves marcos
                                    na carreira e ações realizadas anteriormente.</i>
                                <p align="justify"><span
                                            style="color: gray; "><strong><i>Texto de exemplo:</strong><br/>A cantora e compositora paulistana lançou, em 2007, o seu primeiro disco, "Amor e Caos". Dois anos depois, lançou "Hein?", disco produzido por Liminha e que contou com "Esconderijo", canção composta por Ana, eleita entre as melhores do ano pela revista Rolling Stone e que alcançou repercussão nacional por integrar a trilha sonora da novela "Viver a Vida" de Manoel Carlos, na Rede Globo. Ainda em 2009, grava, a convite do cantor e compositor Nando Reis, a bela canção "Pra Você Guardei o Amor". Em 2012, Ana lança o terceiro disco de inéditas, "Volta", com versões para Led Zeppelin ("Rock'n'Roll") e Edith Piaf ("La Vie en Rose"), além das inéditas autorais "Urubu Rei" (que ganhou clipe dirigido por Vera Egito) e "Será Que Você Me Ama?". Em 2013, veio o primeiro DVD, "Coração Inevitável", registrando o show que contou com a direção e iluminação de Ney Matogrosso.</span></i>
                                </p>
                                <textarea id="release_comunicacao" name="release_comunicacao" class="form-control"
                                          rows="5" required><?= $atracao['release_comunicacao'] ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="links">Links</label><br/>
                                <i>Esse campo deve conter os links relacionados ao espetáculo, ao artista/grupo que
                                    auxiliem na divulgação do evento.</i>
                                <p align="justify"><span style="color: gray; "><strong><i>Links de exemplo:</i></strong><br/> https://www.facebook.com/anacanasoficial/<br/>https://www.youtube.com/user/anacanasoficial</span></i>
                                </p>
                                <textarea id="links" name="links" class="form-control"
                                          rows="5"><?= $atracao['links'] ?></textarea>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="quantidade_apresentacao">Quantidade de Apresentação *</label>
                                    <input type="number" class="form-control" id="quantidade_apresentacao"
                                           name="quantidade_apresentacao" maxlength="2" required min="1"
                                           value="<?= $atracao['quantidade_apresentacao'] ?>">
                                </div>
                                <?php
                                $_SESSION['idEvento'] = $idEvento;
                                $evento = $con->query("SELECT contratacao FROM eventos WHERE id ='$idEvento'")->fetch_array();
                                if ($evento['contratacao'] == 1) {
                                    ?>
                                    <div class="form-group col-md-6">
                                        <label for="valor_individual">Valor *</label> <i>Preencher 0,00 quando não houver valor</i>
                                        <input type="text" id="valor_individual" name="valor_individual" class="form-control" required value="<?= dinheiroParaBr($atracao['valor_individual']) ?>">
                                    </div>
                                    <?php
                                }
                                else{
                                    ?>
                                    <input type="hidden" name="valor_individual" value="0,00">
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="row" id="msg">
                                <div class="col-md-12">
                                    <span style="color: red;" class="pull-right"><b>Valor máximo permitido 999.999,99</b></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="box-footer">
                            <input type="hidden" name="idAtracao" value="<?= $atracao['id'] ?>">
                            <button type="submit" name="edita" id="cadastra" class="btn btn-info pull-right">Gravar</button>
                            <a href="index.php?perfil=evento&p=atracoes_lista" class="btn btn-default pull-left">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php @include "../perfil/includes/modal_classificacao.php" ?>

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