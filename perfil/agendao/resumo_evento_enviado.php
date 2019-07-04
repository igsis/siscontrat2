<?php
include "includes/menu_principal.php";
$con = bancoMysqli();

if(isset($_POST['enviar'])){
    $idEvento = $_POST['idEvento'];
    $now = date('Y-m-d H:i:s');
    $sql_cadastra = $con->query("UPDATE agendoes SET evento_status_id = 3, data_envio = '$now' WHERE id = '$idEvento'");
    if($sql_cadastra){
        $mensagem = mensagem("success","Evento enviado com sucesso!");
    }
    else{
        $mensagem = mensagem("danger", "Erro ao enviar evento. Tente novamente!");
    }
}

$sqlEvento = "SELECT * FROM agendoes AS eve
    INNER JOIN projeto_especiais AS pe ON eve.projeto_especial_id = pe.id
    INNER JOIN classificacao_indicativas ci on eve.classificacao_indicativa_id = ci.id
    WHERE eve.id = '$idEvento'";
$agendao = $con->query($sqlEvento)->fetch_array();

$idProdutor = $agendao['produtor_id'];
$produtor = $con->query("SELECT * FROM produtores WHERE id = '$idProdutor'")->fetch_array();

$ocorrencias = $con->query("SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = '$idEvento'  AND tipo_ocorrencia_id = 3 AND publicado = '1'");

?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START ACCORDION-->
        <h2 class="page-header">Informações do Evento</h2>
        <?= $mensagem ?? $mensagem ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><strong><?= $agendao['nome_evento']; ?></strong></h3><hr>
                        <div class="box-body">
                            <div class="form-group col-md-12">
                                <p><b>Projeto Especial:</b> <?= $agendao['projeto_especial'] ?></p>
                                <p><b>Artistas:</b> <?= $agendao['ficha_tecnica'] ?></p>
                                <p><b>Espaço público?</b>
                                    <?php
                                    if($agendao['espaco_publico'] == 1){
                                        echo "Sim";
                                    } else{
                                        echo "Não";
                                    }
                                    ?>
                                </p>
                                <p><b>Quantidade de apresentação:</b> <?= $agendao['quantidade_apresentacao'] ?></p>
                                <p><b>É fomento/programa?</b>
                                    <?php
                                    if($agendao['fomento'] == 1){
                                        $age_fom = recuperaDados("agendao_fomento", "evento_id", $idEvento);
                                        $fomento = recuperaDados("fomentos","id",$age_fom['fomento_id']);
                                        echo "Sim: ".$fomento['fomento'];
                                    } else{
                                        echo "Não";
                                    }
                                    ?>
                                </p>
                                <p><b>Classificação indicativa:</b> <?= $agendao['classificacao_indicativa'] ?></p>
                                <p><b>Ações (Expressões Artístico-culturais):</b>
                                    <?php
                                    $age_acoes = $con->query("SELECT * FROM acao_agendao WHERE evento_id = $idEvento");
                                    while ($row = mysqli_fetch_array($age_acoes)){
                                        $acao = recuperaDados("acoes","id",$row['acao_id']);
                                        echo $acao['acao']."; ";
                                    }
                                    ?>
                                </p>
                                <p><b>Público (Representatividade e Visibilidade Sócio-cultural):</b>
                                    <?php
                                    $age_pub = $con->query("SELECT * FROM agendao_publico WHERE evento_id = $idEvento");
                                    while ($row = mysqli_fetch_array($age_pub)){
                                        $publico = recuperaDados("publicos","id",$row['publico_id']);
                                        echo $publico['publico']."; ";
                                    }
                                    ?>
                                </p>
                                <p><b>Sinopse:</b> <?= $agendao['sinopse'] ?></p>
                                <p><b>Links:</b> <?= $agendao['links'] ?></p>
                                <hr/>
                            </div>

                            <div class="form-group col-md-12"></div>

                            <div class="form-group col-md-12">
                                <h3 class="box-title"><b>Produtor</b></h3><br><br>
                                <p><b>Nome do produtor:</b> <?= $produtor['nome'] ?></p>
                                <p><b>Email:</b> <?= $produtor['email'] ?></p>
                                <p><b>Telefone:</b> <?= $produtor['telefone1'] . " " . $produtor['telefone1'] ?></p>
                                <p><b>Observação do produtor:</b> <?= $produtor['observacao'] ?></p>
                                <hr/>
                            </div>

                            <div class="form-group col-md-12">
                                <h3 class="box-title"><b>Ocorrências</b></h3><br><br>
                                <?php
                                foreach ($ocorrencias as $ocorrencia) {
                                    ?>
                                    <p><b>Data:</b> <?= exibirDataBr($ocorrencia['data_inicio']) ?> - <?= $ocorrencia['data_fim'] == null ? exibirDataBr($ocorrencia['data_fim']) : "Data única" ?></p>
                                    <p><b>Horário:</b> <?= date("H:i", strtotime($ocorrencia['horario_inicio'])) ?> às <?= date("H:i", strtotime($ocorrencia['horario_fim'])) ?></p>
                                    <p><b></b></p>
                                    <p><b></b></p>
                                    <?php
                                }
                                ?>
                            </div>

                    </div>
                </div>
            </div>
            <!-- /.box-body -->

        <!-- /.box -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
<!-- END ACCORDION & CAROUSEL-->

</section>
<!-- /.content -->
</div>
