<?php
include "includes/menu_interno.php";
$con = bancoMysqli();

$idEvento = $_SESSION['idEvento'];

$sqlEvento = "
    SELECT
    eve.nome_evento,
    pe.projeto_especial,
    eve.ficha_tecnica AS 'artistas',
    eve.espaco_publico,
    eve.quantidade_apresentacao,
    eve.fomento,
    ci.classificacao_indicativa,
    eve.sinopse,
    eve.links
    FROM agendoes AS eve
    INNER JOIN projeto_especiais AS pe ON eve.projeto_especial_id = pe.id
    INNER JOIN classificacao_indicativas ci on eve.classificacao_indicativa_id = ci.id
    WHERE eve.id = '$idEvento'";

$agendao = $con->query($sqlEvento)->fetch_assoc();

include "includes/validacoes.php";
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Pendências</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <?php if (count($erros) == 0) { ?>
                    <div class="alert alert-success alert-dismissible">
                        <h4><i class="icon fa fa-check"></i> Seu evento não possui pendências!</h4>
                        <p>Confirme todos os dados abaixo antes de enviar.</p>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-danger">
                        <h4><i class="icon fa fa-ban"></i> Seu evento possui pendências!</h4>
                        <ul>
                            <?php foreach ($erros as $erro) {
                                echo "<li>$erro</li>";
                            }
                            ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>

        <h2 class="page-header">Finalizar</h2>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li><a href="#ocorrencia" data-toggle="tab">Ocorrências</a></li>
                <li><a href="#produtor" data-toggle="tab">Produtor</a></li>
                <li class="active"><a href="#evento" data-toggle="tab">Evento</a></li>
                <li class="pull-left header">Confirmação dos Dados Inseridos</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="evento">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Dados do Evento</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nomeEvento">Nome do evento:</label> <?= $agendao['nome_evento'] ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="projetoEspecial">Projeto Especial:</label> <?= $agendao['projeto_especial'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="ficha_tecnica">Artistas:</label> <?= $agendao['artistas'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="contratacao">Espaço público?</label>
                                    <?php
                                    if($agendao['espaco_publico'] == 1){
                                        echo "Sim";
                                    } else{
                                       echo "Não";
                                    }
                                    ?>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="qtdApresentacao">Quantidade de apresentação:</label> <?= $agendao['quantidade_apresentacao'] ?>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="fomento">É fomento/programa?</label>
                                    <?php
                                    if($agendao['fomento'] == 1){
                                        $age_fom = recuperaDados("agendao_fomento", "evento_id", $idEvento);
                                        $fomento = recuperaDados("fomentos","id",$age_fom['fomento_id']);
                                        echo "Sim: ".$fomento['fomento'];
                                    } else{
                                        echo "Não";
                                    }
                                    ?>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="classificacao">Classificação indicativa:</label> <?= $agendao['classificacao_indicativa'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6"><label for="acao">Ações (Expressões Artístico-culturais):</label>
                                    <?php
                                    $age_acoes = $con->query("SELECT * FROM acao_agendao WHERE evento_id = $idEvento");
                                    while ($row = mysqli_fetch_array($age_acoes)){
                                        $acao = recuperaDados("acoes","id",$row['acao_id']);
                                        echo $acao['acao']."; ";
                                    }
                                    ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="acao">Público (Representatividade e Visibilidade Sócio-cultural):</label>
                                    <?php
                                    $age_pub = $con->query("SELECT * FROM agendao_publico WHERE evento_id = $idEvento");
                                    while ($row = mysqli_fetch_array($age_pub)){
                                        $publico = recuperaDados("publicos","id",$row['publico_id']);
                                        echo $publico['publico']."; ";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="sinopse">Sinopse:</label> <?= $agendao['sinopse'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="links">Links:</label> <?= $agendao['links'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="produtor">
                    <?php include "label_produtor.php" ?>
                </div>
                <div class="tab-pane" id="ocorrencia">
                    <?php include "label_ocorrencia.php" ?>
                </div>

            </div>
            <div class="box-footer">
                <form action="?perfil=agendao&p=resumo_evento_enviado" method="post">
                    <input type="hidden" name="idEvento" id="idEvento" value="<?=$idEvento?>">
                    <button class="btn btn-success" name="enviar" type="submit" <?= (count($erros) != 0) ? "disabled" : "" ?>>Enviar Evento</button>
                </form>
            </div>
        </div>
    </section>
</div>