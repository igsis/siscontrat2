<?php
$con = bancoMysqli();
$idUsuario = $_SESSION['idUser'];
$idEvento = isset($_SESSION['idEvento']) ?? null;

if (isset($_POST['cadastra']) || isset($_POST['edita'])){
    $nomeEvento =  addslashes($_POST['nomeEvento']);
    $tipoLugar = $_POST['tipoLugar'];
    $projeto_especial_id = $_POST['projetoEspecial'];
    $artistas = addslashes($_POST['ficha_tecnica']);
    $sinopse =  addslashes($_POST['sinopse']);
    $usuario = $_SESSION['idUser'];
    $fomento = $_POST['fomento'];
    $idFomento = $_POST['tipoFomento'] ?? null;
    $oficina = $_POST['oficina'];
    $qtdApresentacao = $_POST['qtdApresentacao'];
    $links = addslashes($_POST['links']);
    $classificacao = $_POST['classificacao'];
}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO agendoes (nome_evento, espaco_publico, projeto_especial_id, classificacao_indicativa_id, links, ficha_tecnica, sinopse, quantidade_apresentacao, fomento, tipo_evento_id, oficina, usuario_id)
                          VALUES ('$nomeEvento', '$tipoLugar', '$projeto_especial_id', '$classificacao', '$links', '$artistas', '$sinopse', '$qtdApresentacao', '$fomento', 3, '$oficina', '$idUsuario')";

    if(mysqli_query($con, $sql))
    {
        $idEvento = recuperaUltimo("agendoes");
        $_SESSION['idEvento'] = $idEvento;

        if($idFomento != null)
        {
            $sql = "INSERT INTO agendao_fomento  (evento_id, fomento_id) VALUES ('$idEvento', '$idFomento')";
            mysqli_query($con, $sql);
        }

        if(isset($_POST['acao'])){
            atualizaDadosRelacionamento('acao_agendao', $idEvento, $_POST['acao'], 'evento_id', 'acao_id');
        }

        if(isset($_POST['publico'])){
            atualizaDadosRelacionamento('agendao_publico', $idEvento, $_POST['publico'], 'evento_id', 'publico_id');
        }

        $mensagem = mensagem("success","Cadastrado com sucesso!");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if(isset($_POST['edita'])){
    $idEvento = $_SESSION['idEvento'];
    $evento = recuperaDados("agendoes", "id", $idEvento);

    if($evento['fomento'] == $fomento){
        $ehIgual = true;
    }else{
        $ehIgual = false;
    }

    $sql = "UPDATE agendoes SET nome_evento = '$nomeEvento', espaco_publico = '$tipoLugar', projeto_especial_id = '$projeto_especial_id', classificacao_indicativa_id = '$classificacao', links = '$links', ficha_tecnica = '$artistas', sinopse = '$sinopse', quantidade_apresentacao = '$qtdApresentacao', fomento = '$fomento', oficina = '$oficina'";

    if(mysqli_query($con,$sql)){
        $mensagem = mensagem("success","Gravado com sucesso!");

        if($idFomento == null)
        {
            $sql = "DELETE FROM agendao_fomento WHERE evento_id = '$idEvento'";

        }else{
            if($ehIgual){
                $sql = "UPDATE agendao_fomento SET fomento_id = '$idFomento' WHERE evento_id = '$idEvento'";
            }
            else{
                $sql = "INSERT INTO agendao_fomento VALUES ('$idEvento', '$idFomento')";
            }
        }

        mysqli_query($con, $sql);

        if(isset($_POST['acao'])){
            atualizaDadosRelacionamento('acao_agendao', $idEvento, $_POST['acao'], 'evento_id', 'acao_id');
        }
        if(isset($_POST['publico'])){
            atualizaDadosRelacionamento('agendao_publico', $idEvento, $_POST['publico'], 'evento_id', 'publico_id');
        }
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}
if(isset($_POST['carregar'])){
    $idEvento = $_POST['idEvento'];
    $_SESSION['idEvento'] = $idEvento;
}

$evento = recuperaDados("agendoes", "id", $idEvento);
$fomento = recuperaDados("agendao_fomento", "evento_id", $idEvento);

include "includes/menu_interno.php";
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
                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>
                    <div class="row" align="center">
                        <?php if(isset($mensagem2)){echo $mensagem2;};?>
                    </div>
                    <form method="POST" action="?perfil=agendao&p=evento_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="nomeEvento">Nome do evento *</label>
                                    <input type="text" class="form-control" id="nomeEvento" name="nomeEvento"
                                           placeholder="Digite o nome do evento" maxlength="240" required value="<?= $evento['nome_evento']; ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="projetoEspecial">Projeto Especial *</label>
                                    <select class="form-control" id="projetoEspecial" name="projetoEspecial"
                                            required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcaoPublicado("projeto_especiais", $evento['projeto_especial_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-12">
                                    <label for="ficha_tecnica">Artistas</label><br/>
                                    <textarea id="ficha_tecnica" name="ficha_tecnica" class="form-control"
                                              rows="8"><?= $evento['ficha_tecnica'] ?></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="contratacao">Espaço público?</label>
                                    <br>
                                    <label><input type="radio" name="tipoLugar" value="1" <?= $evento['espaco_publico'] == 1 ? 'checked' : NULL ?>> Sim </label>&nbsp;&nbsp;
                                    <label><input type="radio" name="tipoLugar" value="0" <?= $evento['espaco_publico'] == 0 ? 'checked' : NULL ?>> Não </label>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="qtdApresentacao">Quantidade de apresentação: *</label>
                                    <input type="number" min="1" class="form-control" name="qtdApresentacao" id="qtdApresentacao" value="<?= $evento['quantidade_apresentacao'] ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="fomento">É fomento/programa?</label> <br>
                                    <label><input type="radio" class="fomento" name="fomento" value="1" id="sim" <?= $evento['fomento'] == 1 ? 'checked' : NULL ?>> Sim
                                    </label>&nbsp;&nbsp;
                                    <label><input type="radio" class="fomento" name="fomento" value="0" id="nao"
                                            <?= $evento['fomento'] == 0 ? 'checked' : NULL ?>> Não </label>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="tipoFomento">Fomento/Programa</label> <br>
                                    <select class="form-control" name="tipoFomento" id="tipoFomento">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                            geraOpcao("fomentos", $fomento['fomento_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="tipo">Este evento é oficina?</label> <br>
                                    <label><input type="radio" name="oficina" value="1" id="simOficina" <?= $evento['oficina'] == 1 ? 'checked' : NULL ?>> Sim </label>&nbsp;&nbsp;
                                    <label><input type="radio" name="oficina" value="0" <?= $evento['oficina'] == 0 ? 'checked' : NULL ?>> Não </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="acao">Ações (Expressões Artístico-culturais) * <i>(multipla
                                            escolha) </i></label>
                                    <button class='btn btn-default' type='button' data-toggle='modal'
                                            data-target='#modalAcoes' style="border-radius: 30px;">
                                        <i class="fa fa-question-circle"></i></button>
                                    <?php
                                        geraCheckBox('acoes', 'acao', 'acao_agendao', 'col-md-6', 'evento_id', 'acao_id', $evento['id']);
                                    ?>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="acao">Público (Representatividade e Visibilidade Sócio-cultural)* <i>(multipla
                                            escolha) </i></label>
                                    <button class='btn btn-default' type='button' data-toggle='modal'
                                            data-target='#modalPublico' style="border-radius: 30px;">
                                        <i class="fa fa-question-circle"></i></button>
                                    <?php
                                        geraCheckBox('publicos', 'publico', 'evento_publico', 'col-md-6', 'evento_id', 'publico_id', $evento['id']);
                                    ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="classificacao">Classificação indicativa *</label>
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#modal-default"><i class="fa fa-info"></i></button>
                                    <select class="form-control" name="classificacao" id="classificacao">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("classificacao_indicativas", $evento['classificacao_indicativa_id']);
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
                                          required><?= $evento['sinopse'] ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="links">Links</label><br/>
                                <i>Esse campo deve conter os links relacionados ao espetáculo, ao artista/grupo
                                    que auxiliem na divulgação do evento.</i>
                                <p align="justify"><span
                                            style="color: gray; "><strong><i>Links de exemplo:</i></strong><br/> https://www.facebook.com/anacanasoficial/<br/>https://www.youtube.com/user/anacanasoficial</span></i>
                                </p>
                                <textarea id="links" name="links" class="form-control" rows="5"><?= $evento['links'] ?></textarea>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" name="edita" class="btn btn-info pull-right">Gravar</button>
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
                <input type="hidden" id="idEvento" name="idEvento" value="<?= $evento['id'] ?>">
                <button type="button" class="btn btn-theme" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


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

<script>
    let fomento = $('.fomento');
    let acao = $("input[name='acao[]']");
    const oficinaId = "Oficinas e Formação Cultural";
    let oficinaRadio = $("input[name='oficina']");
    var oficinaOficial = acao[8];

    function verificaOficina() {
        if ($('#simOficina').is(':checked')) {
            checaCampos(oficinaOficial);
        } else {
            checaCampos("");
        }
    }

    function checaCampos(obj) {
        if (obj.id == oficinaId && obj.value == '8') {

            for (i = 0; i < acao.size(); i++) {
                if (!(acao[i] == obj)) {
                    let acoes = acao[i].id;

                    document.getElementById(acoes).disabled = true;
                    document.getElementById(acoes).checked = false;
                    document.getElementById(oficinaId).checked = true;
                    document.getElementById(oficinaId).disabled = false;

                    document.getElementById(oficinaId).readonly = true;

                }
            }
        } else {
            for (i = 0; i < acao.size(); i++) {

                if (!(acao[i] == acao[8])) {
                    let acoes = acao[i].id;

                    document.getElementById(acoes).disabled = false;
                    document.getElementById(oficinaId).checked = false;
                    document.getElementById(oficinaId).disabled = true;

                    document.getElementById(oficinaId).readonly = false;
                }
            }

        }
    }

    fomento.on("change", verificaFomento);
    oficinaRadio.on("change", verificaOficina);

    $(document).ready(
        verificaFomento(),
        verificaOficina()
    );

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
</script>
