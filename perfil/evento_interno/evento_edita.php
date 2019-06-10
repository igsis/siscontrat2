<?php
$con = bancoMysqli();
$idEvento = isset($_SESSION['idEvento']) ?? null;

if (isset($_POST['cadastra']) || isset($_POST['edita'])){
    $nomeEvento =  addslashes($_POST['nomeEvento']);
    $relacao_juridica_id = 1;
    $projeto_especial_id = $_POST['projetoEspecial'];
    $sinopse =  addslashes($_POST['sinopse']);
    $tipo = 1;
    $fiscal_id = $_POST['fiscal'];
    $suplente_id = $fiscal_id;
    $usuario = $_SESSION['idUser'];
    $original = 0;
    $contratacao = 0;
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
                                 fiscal_id, 
                                 suplente_id, 
                                 usuario_id, 
                                 contratacao, 
                                 original, 
                                 evento_status_id,
                                 evento_interno,
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
                                  '$original',
                                  '$eventoStatus',
                                   1,
                                  '$fomento',
                                  '$tipoLugar')";

    if(mysqli_query($con, $sql))
    {
        $idEvento = recuperaUltimo("eventos");
        $_SESSION['idEvento'] = $idEvento;

        if($idFomento != null)
        {
            $sql = "INSERT INTO evento_fomento  (evento_id, fomento_id) VALUES ('$idEvento', '$idFomento')";
            mysqli_query($con, $sql);
        }

        if(isset($_POST['acao'])){
            atualizaRelacionamentoEvento('acao_evento', $idEvento, $_POST['acao']);
        }

        if(isset($_POST['publico'])){
            atualizaRelacionamentoEvento('evento_publico', $idEvento, $_POST['publico']);
        }

        $mensagem = mensagem("success","Cadastrado com sucesso!");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if(isset($_POST['edita'])){
    $idEvento = $_POST['idEvento'];
    $evento = recuperaDados("eventos","id",$idEvento);

    if($evento['fomento'] == $fomento){
        $ehIgual = true;
    }else{
        $ehIgual = false;
    }

    $sql = "UPDATE eventos SET
                              nome_evento = '$nomeEvento', 
                              relacao_juridica_id = '$relacao_juridica_id', 
                              projeto_especial_id = '$projeto_especial_id', 
                              tipo_evento_id = '$tipo', 
                              sinopse = '$sinopse', 
                              fiscal_id = '$fiscal_id', 
                              suplente_id = '$suplente_id', 
                              contratacao = '$contratacao', 
                              original = '$original',
                              fomento = '$fomento',
                              espaco_publico = '$tipoLugar'
                              WHERE id = '$idEvento'";

    If(mysqli_query($con,$sql)){
        $mensagem = mensagem("success","Gravado com sucesso!");

        if($idFomento == null)
        {
            $sql = "DELETE FROM evento_fomento WHERE evento_id = '$idEvento'";

        }else{
            if($ehIgual){
                $sql = "UPDATE evento_fomento SET fomento_id = '$idFomento' WHERE evento_id = '$idEvento'";
            }
            else{
                $sql = "INSERT INTO evento_fomento VALUES ('$idEvento', '$idFomento')";
            }
        }

        mysqli_query($con, $sql);

        if(isset($_POST['acao'])){
            atualizaRelacionamentoEvento('acao_evento', $idEvento, $_POST['acao']);
        }
        if(isset($_POST['publico'])){
            atualizaRelacionamentoEvento('evento_publico', $idEvento, $_POST['publico']);
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

$evento = recuperaDados("eventos","id",$idEvento);
$fomento = recuperaDados("evento_fomento", "evento_id", $idEvento);

include "includes/menu_interno.php";
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
                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
                    </div>

                    <form method="POST" action="?perfil=evento_interno&p=evento_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="contratacao">Espaço em que será realizado o evento é público?</label> <br>
                                    <label><input type="radio" name="tipoLugar" value="1"> Sim </label>&nbsp;&nbsp;
                                    <label><input type="radio" name="tipoLugar" value="0" checked> Não </label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="fomento">É fomento/programa?</label> <br>
                                    <label><input type="radio" class="fomento" name="fomento" value="1" id="sim"  <?= $evento['fomento'] == 1 ? 'checked' : NULL ?>> Sim </label>&nbsp;&nbsp;
                                    <label><input type="radio" class="fomento" name="fomento" value="0" id="nao"  <?= $evento['fomento'] == 0 ? 'checked' : NULL ?>> Não </label>
                                </div>
                                <div class="form-group col-md-4">
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
                                <div class="form-group col-md-12">
                                    <label for="nomeEvento">Nome do evento *</label>
                                    <input type="text" class="form-control" id="nomeEvento" name="nomeEvento"
                                           placeholder="Digite o nome do evento" maxlength="240" required value="<?= $evento['nome_evento'] ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="projetoEspecial">Projeto Especial *</label>
                                    <select class="form-control" id="projetoEspecial" name="projetoEspecial" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcaoPublicado("projeto_especiais", $evento['projeto_especial_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="fiscal">Fiscal *</label>
                                    <select class="form-control" id="fiscal" name="fiscal" required>
                                        <option value="">Selecione um fiscal...</option>
                                        <?php
                                        geraOpcaoUsuario("usuarios", 1, $evento['fiscal_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="acao">Ações (Expressões Artístico-culturais) * <i>(multipla escolha) </i></label>
                                    <button class='btn btn-default' type='button' data-toggle='modal'
                                            data-target='#modalAcoes' style="border-radius: 30px;">
                                        <i class="fa fa-question-circle"></i></button>
                                    <?php
                                    geraCheckboxEvento('acoes', 'acao', 'acao_evento', $idEvento);
                                    ?>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="acao">Público (Representatividade e Visibilidade Sócio-cultural)* <i>(multipla
                                            escolha) </i></label>
                                    <button class='btn btn-default' type='button' data-toggle='modal'
                                            data-target='#modalPublico' style="border-radius: 30px;">
                                        <i class="fa fa-question-circle"></i></button>
                                    <?php
                                    geraCheckboxEvento('publicos', 'publico', 'evento_publico', $idEvento);
                                    ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sinopse">Sinopse *</label><br/>
                                <i>Esse campo deve conter uma breve descrição do que será apresentado no evento.</i>
                                <p align="justify"><span style="color: gray; "><strong><i>Texto de exemplo:</strong><br/>Ana Cañas faz o show de lançamento do seu quarto disco, “Tô na Vida” (Som Livre/Guela Records). Produzido por Lúcio Maia (Nação Zumbi) em parceria com Ana e mixado por Mario Caldato Jr, é o primeiro disco totalmente autoral da carreira da cantora e traz parcerias com Arnaldo Antunes e Dadi entre outros.</span></i></p>
                                <textarea name="sinopse" id="sinopse" class="form-control" rows="5" required><?= $evento['sinopse'] ?></textarea>
                            </div>
                        </div>

                        <div class="box-footer">
                            <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                            <button type="submit" name="edita" class="btn btn-info pull-right">Gravar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
</div>

<script>
    var fomento = $('.fomento');
    fomento.on("change", verificaFomento);
    $(document).ready(verificaFomento());

    function verificaFomento () {
        if ($('#sim').is(':checked')) {
            $('#tipoFomento')
                .attr('disabled', false)
                .attr('required',true)
        } else {
            $('#tipoFomento')
                .attr('disabled', true)
                .attr('required',false)
        }
    }
</script>