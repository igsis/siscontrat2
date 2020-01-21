<?php
$con = bancoMysqli();
$idEvento = isset($_SESSION['idEvento']) ? $_SESSION['idEvento'] : null;

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $nomeEvento = trim(addslashes($_POST['nomeEvento']));
    $relacao_juridica_id = $_POST['relacaoJuridica'];
    $projeto_especial_id = $_POST['projetoEspecial'];
    $sinopse = trim(addslashes($_POST['sinopse']));
    $tipo = $_POST['tipo'];
    $nomeResponsavel = trim($_POST['nomeResponsavel']);
    $telResponsavel = $_POST['telResponsavel'];
    $fiscal_id = $_POST['fiscal'];
    $suplente_id = $_POST['suplente'];
    $usuario = $_SESSION['idUser'];
    $contratacao = $_POST['contratacao'];
    $eventoStatus = "1";
    $fomento = $_POST['fomento'];
    $tipoLugar = $_POST['tipoLugar'];
    $idFomento = $_POST['tipoFomento'] ?? null;
}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO eventos (nome_evento,
                                 relacao_juridica_id, 
                                 projeto_especial_id, 
                                 tipo_evento_id, 
                                 sinopse,   
                                 nome_responsavel,
                                 tel_responsavel,
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
                                  '$nomeResponsavel',
                                  '$telResponsavel',
                                  '$fiscal_id',
                                  '$suplente_id',
                                  '$usuario',
                                  '$contratacao',
                                  '$eventoStatus',
                                  '$fomento',
                                  '$tipoLugar')";

    if (mysqli_query($con, $sql)) {
        $idEvento = recuperaUltimo("eventos");
        $_SESSION['idEvento'] = $idEvento;

        if ($idFomento != null) {
            $sql = "INSERT INTO evento_fomento  (evento_id, fomento_id) VALUES ('$idEvento', '$idFomento')";
            mysqli_query($con, $sql);
        }

        if (isset($_POST['publico'])) {
            atualizaDadosRelacionamento('evento_publico', $idEvento, $_POST['publico'], 'evento_id', 'publico_id');
        }
        
        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }

    echo $sql;
}

if (isset($_POST['edita'])) {
    $idEvento = $_POST['idEvento'];
    $evento = recuperaDados("eventos", "id", $idEvento);

    if ($evento['fomento'] == $fomento) {
        $ehIgual = true;
    } else {
        $ehIgual = false;
    }

    $sql = "UPDATE eventos SET
                              nome_evento = '$nomeEvento', 
                              relacao_juridica_id = '$relacao_juridica_id', 
                              projeto_especial_id = '$projeto_especial_id', 
                              tipo_evento_id = '$tipo',
                              nome_responsavel = '$nomeResponsavel',
                              tel_responsavel = '$telResponsavel',
                              sinopse = '$sinopse', 
                              fiscal_id = '$fiscal_id', 
                              suplente_id = '$suplente_id', 
                              contratacao = '$contratacao',
                              fomento = '$fomento',
                              espaco_publico = '$tipoLugar'
                              WHERE id = '$idEvento'";
    If (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Gravado com sucesso!");

        if ($idFomento == null) {
            $sql = "DELETE FROM evento_fomento WHERE evento_id = '$idEvento'";

        } else {
            if ($ehIgual) {
                $sql = "UPDATE evento_fomento SET fomento_id = '$idFomento' WHERE evento_id = '$idEvento'";
            } else {
                $sql = "INSERT INTO evento_fomento VALUES ('$idEvento', '$idFomento')";
            }
        }

        mysqli_query($con, $sql);

        if (isset($_POST['publico'])) {
            atualizaDadosRelacionamento('evento_publico', $idEvento, $_POST['publico'], 'evento_id', 'publico_id');
        }
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}
if (isset($_POST['carregar'])) {
    $idEvento = $_POST['idEvento'];
    $_SESSION['idEvento'] = $idEvento;
}

$evento = recuperaDados("eventos", "id", $idEvento);
$nomeEvento = $evento['nome_evento'];

include "includes/menu_interno.php";

$fomento = recuperaDados("evento_fomento", "evento_id", $idEvento);
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Evento</h2>

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

                    <form method="POST" action="?perfil=evento&p=evento_edita" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="contratacao">Haverá contratação?</label> <br>
                                    <label><input type="radio" name="contratacao"
                                                  value="1" <?= $evento['contratacao'] == 1 ? 'checked' : NULL ?>> Sim
                                    </label>
                                    <label><input type="radio" name="contratacao"
                                                  value="0" <?= $evento['contratacao'] == 0 ? 'checked' : NULL ?>> Não
                                    </label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="contratacao">Espaço em que será realizado o evento é público?</label>
                                    <br>
                                    <label><input type="radio" name="tipoLugar" value="1" checked> Sim </label>&nbsp;&nbsp;
                                    <label><input type="radio" name="tipoLugar" value="0"> Não </label>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="tipo">Este evento é cinema?</label> <br>
                                    <label><input type="radio" name="tipo"
                                                  value="2" <?= $evento['tipo_evento_id'] == 2 ? 'checked' : NULL ?>>
                                        Sim </label>&nbsp;&nbsp;
                                    <label><input type="radio" name="tipo"
                                                  value="1" <?= $evento['tipo_evento_id'] == 1 ? 'checked' : NULL ?>>
                                        Não </label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="fomento">É fomento/programa?</label> <br>
                                    <label><input type="radio" class="fomento" name="fomento" value="1"
                                                  id="sim" <?= $evento['fomento'] == 1 ? 'checked' : NULL ?>> Sim
                                    </label>&nbsp;&nbsp;
                                    <label><input type="radio" class="fomento" name="fomento" value="0"
                                                  id="nao" <?= $evento['fomento'] == 0 ? 'checked' : NULL ?>> Não
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
                                       maxlength="240" required value="">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="relacaoJuridica">Tipo de relação jurídica *</label>
                                    <select class="form-control" name="relacaoJuridica" id="relacaoJuridica" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("relacao_juridicas", $evento['relacao_juridica_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="projetoEspecial">Projeto Especial *</label>
                                    <select class="form-control" id="projetoEspecial" name="projetoEspecial" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcaoPublicado("projeto_especiais", $evento['projeto_especial_id']);
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
                                    geraCheckBox('publicos', 'publico', 'evento_publico', 'col-md-6', 'evento_id', 'publico_id', $idEvento);
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
                                          required><?= $evento['sinopse'] ?></textarea>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-6">
                                    <label for="fiscal">Nome do Responsável Interno *</label>
                                    <input class="form-control" type="text" name="nomeResponsavel" value="<?= $evento['nome_responsavel'] ?>" required pattern="[a-zA-ZàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇáéíóúýÁÉÍÓÚÝ ]{1,120}" title="Apenas letras">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="fiscal">Telefone do Responsável Interno *</label>
                                    <input class="form-control" type="text" name="telResponsavel" maxlength="15" value="<?= $evento['tel_responsavel'] ?>" data-mask="(00) 00000-0000" onkeyup="mascara( this, mtel );" required>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-6">
                                    <label for="fiscal">Fiscal *</label>
                                    <select class="form-control" id="fiscal" name="fiscal" required>
                                        <option value="">Selecione um fiscal...</option>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, $evento['fiscal_id']);
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="suplente">Suplente</label>
                                    <select class="form-control" id="suplente" name="suplente">
                                        <option value="">Selecione um suplente...</option>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, $evento['suplente_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                            <button type="submit" name="edita" id="edita" class="btn btn-info pull-right">Gravar
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

<script>
    const btnEdita = $('#edita');
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
            btnEdita.attr("disabled", false);
            btnEdita.removeAttr("data-toggle");
            btnEdita.removeAttr("data-placement");
            btnEdita.removeAttr("title");
        } else {
            isMsg.show();
            btnEdita.attr("disabled", true);
            btnEdita.attr("data-toggle", "tooltip");
            btnEdita.attr("data-placement", "left");
            btnEdita.attr("title", "Selecione pelo menos uma Representatividade");
        }
    }

    //EXECUTA TUDO
    publicos.on('change', validaPublico);
    $('.fomento').on('change', verificaFomento);

    $(document).ready(function () {
        validaPublico();
        verificaFomento();
    })

    const nomeEvento = `<?=$nomeEvento?>`;
    $('#nomeEvento').val(nomeEvento);
</script>