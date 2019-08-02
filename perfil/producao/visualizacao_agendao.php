<?php
include "includes/menu_interno.php";

$con = bancoMysqli();

$_SESSION['idEvento'] = $_POST['idEvento'];
$idUser = $_SESSION['idUser'];

$idEvento = $_SESSION['idEvento'];

$sqlAgendao = "SELECT
    a.nome_evento,
    pe.projeto_especial,
    a.ficha_tecnica AS 'artistas',
    a.espaco_publico,
    a.quantidade_apresentacao,
    a.fomento,
    ci.classificacao_indicativa,
    a.sinopse,
    a.links,
    a.visualizado
    FROM agendoes AS a
    INNER JOIN projeto_especiais AS pe ON a.projeto_especial_id = pe.id
    INNER JOIN classificacao_indicativas ci on a.classificacao_indicativa_id = ci.id
    WHERE a.id = '$idEvento'";

$agendao = $con->query($sqlAgendao)->fetch_assoc();
$view = recuperaDados('producao_agendoes', 'id', $idEvento);
?>

<div class="content-wrapper">
    <section class="content-header">
        <div>
            <div class="box">
                <div class="box-header">
                    <h3 class="page-header">
                        Agendão Selecionado
                    </h3>
                </div>
            </div>
            <div class="box">
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs pull-right">
                            <li><a href="#ocorrencia" data-toggle="tab">Ocorrências </a></li>
                            <li><a href="#produtor" data-toggle="tab">Produtor</a></li>
                            <li class="active"><a href="#evento" data-toggle="tab">Evento</a></li>
                            <li class="pull-left header">Confirmação dos Dados Inseridos</li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="evento">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"> Dados do Evento</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="nomeEvento">Nome do Evento:</label> <?=$agendao['nome_evento']?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="projetoEspecial">Projeto Especial:</label> <?=$agendao['projeto_especial']?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <div>
                                                    <label for="fichaTecnica">Artistas:</label><?=$agendao['artistas']?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label for="contratacao">Espaco público?</label>
                                                <?php
                                                    if($agendao['espaco_publico'] == 1){
                                                        echo "Sim";
                                                    }else{
                                                        echo "Não";
                                                    }
                                                ?>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="qtdApresentacao">Quantidade de Apresentações:</label> <?=$agendao['quantidade_apresentacao']?>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="fomento">É fomento/programa?</label>
                                                <?php
                                                    if($agendao['fomento'] == 1){
                                                        $age_fom = recuperaDados('agendao_fomento', 'evento_id',$idEvento);
                                                        $fomento = recuperaDados('fomentos', 'id', $age_fom['fomento_id']);
                                                        echo "Sim ". $fomento['fomento'];
                                                    }else{
                                                        echo "Não";
                                                    }
                                                ?>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="classificacao">Classificação indicativa:</label><?=$agendao['classificacao_indicativa']?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="acao">Ações (Expressões Artístico-culturais):</label>
                                                <?php
                                                    $age_acao = $con->query("SELECT * FROM acao_agendao WHERE evento_id = '$idEvento'");
                                                    while($row = mysqli_fetch_array($age_acao)){
                                                        $acao = recuperaDados('acoes', 'id', $row['acao_id']);
                                                        echo $acao['acao'] . "; ";
                                                    }
                                                ?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="publico">Público(Representatividade e Visibilidade Sócio-cultural):</label>
                                                <?php
                                                    $age_pub = $con->query("SELECT * FROM agendao_publico WHERE evento_id = '$idEvento'");
                                                    while ($row = mysqli_fetch_array($age_pub)){
                                                        $pub = recuperaDados('publicos', 'id', $row['publico_id']);
                                                        echo $pub['publico'] . "; ";
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="sinopse">Sinopse:</label> <?=$agendao['sinopse']?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="links">Links:</label> <?=$agendao['links']?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="visualizado">Visualizado:</label>
                                                <?php
                                                if($view['visualizado'] == 1){
                                                    echo "Sim";
                                                }else{
                                                    echo "Não";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="produtor">
                                <?php include "includes/label_produtor_agendao.php"?>
                            </div>
                            <div class="tab-pane" id="ocorrencia">
                                <?php include "includes/label_ocorrencia_agendao.php";?>
                            </div>
                        </div>
                        <div class="box-footer">
                            <form action="?perfil=producao&p=agendoes_visualizados_producao" method="post">
                                <input type="hidden" name="idEvento" id="idEvento" value="<?= $idEvento ?>">
                                <?php

                                if($view['visualizado'] == 0){
                                ?>
                                <button type="submit" name="checarAgendao" class="btn btn-success"> Checar visualização
                                    <?php
                                    }else {
                                    ?>
                                    <button type="submit" name="voltar" class="btn btn-success"> Voltar
                                        <?php
                                        }

                                        ?>
                                    </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>