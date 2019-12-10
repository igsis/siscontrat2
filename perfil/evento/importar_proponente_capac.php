<?php
include "includes/menu_principal.php";

$idUser = $_SESSION['idUser'];

$conSis = bancoMysqli();
$conCpc = bancoCapac();

if (isset($_POST['importarEventoCpc'])) {
    $idCapac = $_POST['idCapac'];

    $nomeEvento = addslashes($_POST['nomeEvento']);
    $relacao_juridica_id = $_POST['relacaoJuridica'];
    $projeto_especial_id = $_POST['projetoEspecial'];
    $sinopse = addslashes($_POST['sinopse']);
    $tipo = $_POST['tipo'];
    $fiscal_id = $_POST['fiscal'];
    $suplente_id = $_POST['suplente'];
    $usuario = $_SESSION['idUser'];
    $contratacao = $_POST['contratacao'];
    $eventoStatus = "1";
    $fomento = $_POST['fomento'];
    $tipoLugar = $_POST['tipoLugar'];
    $idFomento = $_POST['tipoFomento'] ?? null;

    $sqlInsertSis = "INSERT INTO siscontrat.eventos (nome_evento,
                                 relacao_juridica_id, 
                                 projeto_especial_id, 
                                 tipo_evento_id, 
                                 sinopse, 
                                 fiscal_id, 
                                 suplente_id, 
                                 usuario_id, 
                                 contratacao, 
                                 evento_status_id,
                                 fomento, 
                                 espaco_publico) 
                          VALUES ('$nomeEvento',
                                  '$relacao_juridica_id',
                                  '$projeto_especial_id',
                                  '$tipo',
                                  '$sinopse',
                                  '$fiscal_id',
                                  '$suplente_id',
                                  '$usuario',
                                  '$contratacao',
                                  '$eventoStatus',
                                  '$fomento',
                                  '$tipoLugar')";

    if(mysqli_query($conSis, $sqlInsertSis)) {
        $idEvento = $conSis->insert_id;

        if ($idFomento != null) {
            $sqlFomentoCpc = "INSERT INTO siscontrat.evento_fomento  (evento_id, fomento_id) VALUES ('$idEvento', '$idFomento')";
            mysqli_query($conSis, $sqlFomentoCpc);
        }

        if (isset($_POST['publico'])) {
            atualizaDadosRelacionamento('siscontrat.evento_publico', $idEvento, $_POST['publico'], 'evento_id', 'publico_id');
        }

        /* IMPORTANDO AS ATRAÇÕES */
        $sqlAtracoesCpc = "SELECT * FROM capac_new.atracoes WHERE evento_id = '$idCapac' AND publicado = 1";
        $atracoes = $conCpc->query($sqlAtracoesCpc)->fetch_all(MYSQLI_ASSOC);

        foreach ($atracoes as $atracao) {
            $sqlAcoesCpc = "SELECT acao_id FROM capac_new.acao_atracao WHERE atracao_id = {$atracao['id']}";
            $acoes = $conCpc->query($sqlAcoesCpc)->fetch_all(MYSQLI_ASSOC);

            /* INSERINDO PRODUTOR */
            $sqlInsertProdutor = "INSERT INTO siscontrat.produtores (nome, email, telefone1, telefone2, observacao)
                              SELECT nome, email, telefone1, telefone2, observacao FROM capac_new.produtores WHERE id = {$atracao['produtor_id']}";
            if(mysqli_query($conSis, $sqlInsertProdutor)) {
                $idProdutor = $conSis->insert_id;
                $sqlInsertAtracao = "INSERT INTO siscontrat.atracoes (evento_id, nome_atracao, ficha_tecnica, integrantes, classificacao_indicativa_id, release_comunicacao, links, quantidade_apresentacao, valor_individual, produtor_id)
                                 SELECT '$idEvento', cpca.nome_atracao, cpca.ficha_tecnica, cpca.integrantes, cpca.classificacao_indicativa_id, cpca.release_comunicacao, cpca.links, cpca.quantidade_apresentacao, cpca.valor_individual, '$idProdutor' FROM capac_new.atracoes AS cpca WHERE id = {$atracao['id']}";

                if(mysqli_query($conSis, $sqlInsertAtracao)) {
                    $idAtracao = $conSis->insert_id;
                    foreach ($acoes as $acao) {
                        $sqlInsertAcao = "INSERT INTO siscontrat.acao_atracao (acao_id, atracao_id) VALUES ('{$acao['acao_id']}', '$idAtracao')";
                    }
                }
            }
        }

        $sqlInsertImportado = "INSERT INTO siscontrat.eventos_importados (eventos_id, eventos_capac_id) VALUES ('$idEvento', '$idCapac')";
        mysqli_query($conSis, $sqlInsertImportado);
    } else {
        echo "<script>window.location.href = 'index.php?perfil=evento&p=importar_evento_capac&error=1&idCpc=".$idCapac."</script>";
    }
} else {
    echo "<script>window.location.href = 'index.php?perfil=evento&p=buscar_capac'</script>";
}

$sqlEventoCpc = "SELECT * FROM capac_new.eventos WHERE id = '$idCapac' AND publicado = 2";
$eventoCpc = $conCpc->query($sqlEventoCpc)->fetch_assoc();

?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Importação de Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações Gerais</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>

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
                </div>
            </div>
        </div>

    </section>
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
                    foreach ($conSis->query($sqlConsultaPublico)->fetch_all(MYSQLI_ASSOC) as $publico) {
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
Aeoo
<script>
    const btnCadastra = $('#cadastra');
    let publicos = $('.publicos');

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

    function validaPublico() {
        var isMsg = $('#msgEsconde');
        var checked = false;

        for (let x = 0 ; x < publicos.length; x++) {
            if (publicos[x].checked) {
                checked = true;
            }
        }

        if (checked) {
            isMsg.hide();
            btnCadastra.attr("disabled", false);
            btnCadastra.removeAttr("data-toggle");
            btnCadastra.removeAttr("data-placement");
            btnCadastra.removeAttr("title");
        } else {
            isMsg.show();
            btnCadastra.attr("disabled", true);
            btnCadastra.attr("data-toggle", "tooltip");
            btnCadastra.attr("data-placement", "left");
            btnCadastra.attr("title", "Selecione pelo menos uma Representatividade");
        }
    }

    //EXECUTA TUDO
    publicos.on('change', validaPublico);
    $('.fomento').on('change', verificaFomento);

    $(document).ready(function () {
        validaPublico();
        verificaFomento();
    })
</script>