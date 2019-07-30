<?php
include "includes/menu_principal.php";
$con = bancoMysqli();

if (isset($_POST['idEvento'])) {
    $idEvento = $_POST['idEvento'];
} else {
    echo "<script>window.location = '?perfil=evento';</script>";
}

$evento = recuperaDados('eventos', 'id', $idEvento);

if (isset($_POST['enviar'])) {
    $fora = $_POST['fora'];

    if ($evento['tipo_evento_id'] == 1) {
        $protocolo = geraProtocolo($idEvento) . "-E";
    } else if ($evento['tipo_evento_id'] == 2) {
        $protocolo = geraProtocolo($idEvento) . "-C";
    }

    if($evento['contratacao'] == 1){
        if ($fora == 1) {
            $sqlPedido = "UPDATE pedidos SET status_pedido_id = 1 WHERE origem_tipo_id = 1 AND origem_id = '$idEvento'";
            $sqlEvento = "UPDATE eventos SET evento_status_id = 2 WHERE id = '$idEvento'";
            if (mysqli_query($con, $sqlPedido)) {
                mysqli_query($con, $sqlEvento);
                $mensagemPedido = mensagem("warning", "Seu pedido está aguardando aprovação!");
            }
        } else {
            $sqlPedido = "UPDATE pedidos SET status_pedido_id = 2 WHERE origem_tipo_id = 1 AND origem_id = '$idEvento'";
            if (mysqli_query($con, $sqlPedido)) {
                $mensagemPedido = mensagem("success", "Pedido aprovado!");

                $sqlEnviaEvento = "UPDATE eventos SET protocolo = '$protocolo', evento_status_id = 3 WHERE id = '$idEvento'";
                mysqli_query($con, $sqlEnviaEvento);

                $data = date("Y-m-d H:i:s",strtotime("now"));
                $sqlEnvia = "INSERT INTO evento_envios (evento_id, data_envio) VALUES ('$idEvento', '$data') ";
                $queryEnvia = mysqli_query($con, $sqlEnvia);
                $mensagem = mensagem("success", "Evento enviado com sucesso!");
            }
        }

    }else{
        $sqlEnviaEvento = "UPDATE eventos SET protocolo = '$protocolo', evento_status_id = 3 WHERE id = '$idEvento'";
        mysqli_query($con, $sqlEnviaEvento);
        $mensagem = mensagem("success", "Evento enviado com sucesso!");
    }
}

$evento = recuperaDados('eventos', 'id', $idEvento);
$tipo_evento = recuperaDados('tipo_eventos', 'id', $evento['tipo_evento_id']);
$original = $evento['original'] == 1 ? 'Sim' : 'Não';
$relacao_juridica = recuperaDados('relacao_juridicas', 'id', $evento['relacao_juridica_id']);
$projeto_especial = recuperaDados('projeto_especiais', 'id', $evento['projeto_especial_id']);
$fiscal = recuperaDados('usuarios', 'id', $evento['fiscal_id']);
$suplente = recuperaDados('usuarios', 'id', $evento['suplente_id']);
$usuario = recuperaDados('usuarios', 'id', $evento['usuario_id']);
$contratacao = $evento['contratacao'] == 1 ? 'Sim' : 'Não';
$evento_status = recuperaDados('evento_status', 'id', $evento['evento_status_id']);
$sql_atracao = "SELECT * FROM atracoes WHERE evento_id = '$idEvento' AND publicado = 1";
$sql_filme = "SELECT f.id, f.titulo, f.ano_producao, f.genero, f.sinopse, f.duracao FROM filme_eventos fe INNER JOIN eventos e on fe.evento_id = e.id INNER JOIN filmes f ON f.id = fe.filme_id WHERE e.id = $idEvento AND e.publicado = 1 AND f.publicado = 1";
?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START ACCORDION-->
        <h2 class="page-header">Informações do Evento</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><strong><?= $evento['nome_evento']; ?></strong></h3>
                        <hr>
                        <div class="row" align="center">
                            <?php if (isset($mensagem)) {
                                echo $mensagem;
                            }; ?>
                        </div>
                        <div class="row" align="center">
                            <?php if (isset($mensagemPedido)) {
                                echo $mensagemPedido;
                            }; ?>
                        </div>
                        <div class="box-body">
                            <div class="box-group" id="accordion">
                                <div class="row">
                                    <div class="box-body">
                                        <div class="form-group col-md-12">
                                            <div align="center">
                                                <h3>Informações sobre o evento</h3>
                                                <hr>
                                            </div>
                                            <strong>Protocolo: </strong><?= $evento['protocolo'] ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Nome do Evento: </strong><?= $evento ['nome_evento']; ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Período: </strong><?= retornaPeriodoNovo($idEvento, 'ocorrencias'); ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Tipo evento: </strong><?= $tipo_evento['tipo_evento']; ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>É original? </strong><?= $original ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Relação
                                                Juridica: </strong><?= $relacao_juridica['relacao_juridica']; ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Projeto
                                                Especial: </strong><?= $projeto_especial['projeto_especial']; ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Sinopse: </strong><?= $evento['sinopse']; ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div align="center">
                                                <h3>Informações sobre cadastramento</h3>
                                                <hr>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Fiscal: </strong><?= $fiscal['nome_completo'] ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Suplente: </strong><?= $suplente['nome_completo']; ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Cadastramento realizado
                                                por: </strong><?= $usuario['nome_completo'] ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Haverá contratação? </strong><?= $contratacao ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <strong>Status do Evento: </strong><?= $evento_status['status']; ?>
                                        </div>
                                        <hr>
                                        <?php
                                        if ($evento['tipo_evento_id'] == 1) {
                                            $query_atracao = mysqli_query($con, $sql_atracao);
                                            while ($atracao = mysqli_fetch_array($query_atracao)) {

                                                $classificacao_indicativa = recuperaDados('classificacao_indicativas', 'id', $atracao['classificacao_indicativa_id']);

                                                $idAtracao = $atracao['id'];
                                                $sql_ocorrencia = "SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = '$idEvento' AND publicado = 1";

                                                ?>
                                                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                                <div class="form-group col-md-12">
                                                    <div align="center">
                                                        <h3>Informações sobre a atração</h3>
                                                        <hr>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <strong>Nome da atração: </strong><?= $atracao['nome_atracao']; ?>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <strong>Ação: </strong>
                                                    <?php

                                                    $sqlAcao = "SELECT a.acao FROM acao_atracao at INNER JOIN acoes a on at.acao_id = a.id WHERE atracao_id = '$idAtracao'";
                                                    $queryAcao = mysqli_query($con, $sqlAcao);

                                                    $acaoArray[] = '';

                                                    while ($acao = mysqli_fetch_array($queryAcao)) {
                                                        echo $acao['acao'] . '; ';
                                                    }
                                                    ?>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <strong>Ficha técnica: </strong><?= $atracao['ficha_tecnica']; ?>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <strong>Integrantes: </strong><?= $atracao['integrantes']; ?>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <strong>Classificação
                                                        Indicativa: </strong><?= $classificacao_indicativa['classificacao_indicativa']; ?>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <strong>Quantidade de
                                                        Apresentação: </strong><?= $atracao['quantidade_apresentacao']; ?>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <strong>Release: </strong><?= $atracao['release_comunicacao']; ?>
                                                </div>
                                                <?php
                                                if ($atracao['links'] != NULL) {
                                                    ?>
                                                    <div class="form-group col-md-12">
                                                        <strong>Links: </strong><?= $atracao['links']; ?>
                                                    </div>
                                                <?php } ?>

                                                <?php
                                                if ($atracao['valor_individual'] != NULL) {
                                                    ?>
                                                    <div class="form-group col-md-12">
                                                        <strong> Valor
                                                            individual: </strong>
                                                        R$ <?= dinheiroParaBr($atracao['valor_individual']); ?>
                                                    </div>
                                                <?php }

                                                if ($atracao['produtor_id'] != NULL) {
                                                    $produtor = recuperaDados('produtores', 'id', $atracao['produtor_id']);
                                                    ?>
                                                    <div class="form-group col-md-12">
                                                        <div align="center">
                                                            <h3>Informações sobre a produção</h3>
                                                            <hr>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <strong>Nome Produtor: </strong><?= $produtor['nome']; ?>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <strong>Email do Produtor: </strong><?= $produtor['email']; ?>
                                                    </div>
                                                    <?php
                                                    if ($produtor['telefone1'] != '') {
                                                        ?>
                                                        <div class="form-group col-md-12">
                                                            <strong>Telefone 1: </strong><?= $produtor['telefone1']; ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($produtor['telefone2'] != '') {
                                                        ?>
                                                        <div class="form-group col-md-12">
                                                            <strong>Telefone 2: </strong><?= $produtor['telefone2']; ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <hr>
                                                    <div class="form-group col-md-12">
                                                        <strong>Observação:</strong><?= $produtor['observacao']; ?>
                                                    </div>
                                                <?php } ?>
                                                <?php
                                                $query_ocorrencia = mysqli_query($con, $sql_ocorrencia);
                                                while ($ocorrencia = mysqli_fetch_array($query_ocorrencia)) {

                                                    $local = recuperaDados('locais', 'id', $ocorrencia['local_id']);
                                                    $retirada_ingresso = recuperaDados('retirada_ingressos', 'id', $ocorrencia['retirada_ingresso_id']);

                                                    ?>


                                                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->


                                                    <div class="form-group col-md-12">
                                                        <div align="center">
                                                            <h3>Ocorrências</h3>
                                                            <hr>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <strong>Local: </strong><?= $local['local']; ?>
                                                    </div>
                                                    <?php
                                                    if ($ocorrencia['horario_inicio'] != NULL) {
                                                        ?>
                                                        <div class="form-group col-md-12">
                                                            <strong>Horário
                                                                Inicial: </strong><?= exibirHora($ocorrencia['horario_inicio']); ?>
                                                        </div>
                                                    <?php } ?>
                                                    <?php
                                                    if ($ocorrencia['horario_fim'] != NULL) {
                                                        ?>
                                                        <div class="form-group col-md-12">
                                                            <strong>Horário
                                                                Final: </strong><?= exibirHora($ocorrencia['horario_fim']); ?>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="form-group col-md-12">
                                                        <strong>Retirada de
                                                            Ingresso:</strong> <?= $retirada_ingresso['retirada_ingresso'] ?>
                                                    </div>
                                                    <?php
                                                    if ($ocorrencia['virada'] != NULL) {
                                                        ?>
                                                        <div class="form-group col-md-12">
                                                            <strong>Virada:</strong> <?= $ocorrencia['virada'] = 1 ? "Sim" : "Não"; ?>
                                                        </div>
                                                    <?php } ?>
                                                <?php }
                                            }
                                        } else {
                                            $query_filme = mysqli_query($con, $sql_filme);
                                            $contador = 1;
                                            while ($filme = mysqli_fetch_array($query_filme)) {

                                                $idFilme = $filme['id'];
                                                $sql_ocorrencia = "SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = '$idEvento' AND publicado = 1";
                                                ?>
                                                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                                <div class="form-group col-md-12">
                                                    <div align="center">
                                                        <h3>Informações sobre a atração</h3>
                                                        <hr>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <strong>Nome do filme: </strong><?= $filme['titulo']; ?>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <strong>Ano de Produção: </strong><?= $filme['ano_producao']; ?>
                                                </div>


                                                <div class="form-group col-md-12">
                                                    <strong>Gênero: </strong><?= $filme['genero']; ?>
                                                </div>


                                                <div class="form-group col-md-12">
                                                    <strong>Sinopse: </strong><?= $filme['sinopse']; ?>
                                                </div>


                                                <div class="form-group col-md-12">
                                                    <strong>Duração: </strong><?= $filme['duracao']; ?> min
                                                </div>
                                                <?php
                                                $query_ocorrencia = mysqli_query($con, $sql_ocorrencia);
                                                while ($ocorrencia = mysqli_fetch_array($query_ocorrencia)) {

                                                    $local = recuperaDados('locais', 'id', $ocorrencia['local_id']);
                                                    $retirada_ingresso = recuperaDados('retirada_ingressos', 'id', $ocorrencia['retirada_ingresso_id']);
                                                    ?>
                                                    <div class="form-group col-md-12">
                                                        <div align="center">
                                                            <h3>Ocorrência #<?= $contador ?></h3>
                                                            <hr>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <strong>Local: </strong><?= $local['local']; ?>
                                                    </div>
                                                    <?php
                                                    if ($ocorrencia['horario_inicio'] != NULL) {
                                                        ?>
                                                        <div class="form-group col-md-12">
                                                            <strong>Horário
                                                                Inicial: </strong><?= exibirHora($ocorrencia['horario_inicio']); ?>
                                                        </div>
                                                    <?php } ?>
                                                    <?php
                                                    if ($ocorrencia['horario_fim'] != NULL) {
                                                        ?>
                                                        <div class="form-group col-md-12">
                                                            <strong>Horário
                                                                Final: </strong><?= exibirHora($ocorrencia['horario_fim']); ?>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="form-group col-md-12">
                                                        <strong>Retirada de
                                                            Ingresso:</strong> <?= $retirada_ingresso['retirada_ingresso'] ?>
                                                    </div>
                                                    <?php
                                                    if ($ocorrencia['virada'] != NULL) {
                                                        ?>
                                                        <div class="form-group col-md-12">
                                                            <strong>Virada:</strong> <?= $ocorrencia['virada'] == 1 ? "Sim" : "Não"; ?>
                                                        </div>
                                                    <?php }
                                                    $contador++;
                                                    ?>

                                                <?php }
                                            }
                                        }

                                        ?>
                                        <div class="box-footer" align="center">
                                            <a href="?perfil=evento">
                                                <button type="button" class="btn btn-default">Voltar</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
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
