<?php
include "includes/menu_principal.php";


$con = bancoMysqli();
if (isset($_POST['carregar'])) {
    $idEvento = $_POST['idEvento'];
    $evento = recuperaDados('eventos', 'id', $idEvento);
    $protocolo = recuperaDados('protocolos', 'origem_id', $idEvento);
    $tipo_evento = recuperaDados('tipo_eventos', 'id', $evento['tipo_evento_id']);
    $original = $evento['original'] == 1 ? 'Sim' : 'Não';
    $relacao_juridica = recuperaDados('relacao_juridicas', 'id', $evento['relacao_juridica_id']);
    $projeto_especial = recuperaDados('projeto_especiais', 'id', $evento['projeto_especial_id']);
    $fical = recuperaDados('usuarios', 'id', $evento['fiscal_id']);
    $suplente = recuperaDados('usuarios', 'id', $evento['suplente_id']);
    $usuario = recuperaDados('usuarios', 'id', $evento['usuario_id']);
    $contratacao = $evento['contratacao'] == 1 ? 'Sim' : 'Não';
    $evento_status = recuperaDados('evento_status', 'id', $evento['evento_status_id']);
    $sql_atracao = "SELECT * FROM atracoes WHERE evento_id = '$idEvento' AND publicado = 1";

}
?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START ACCORDION-->
        <h2 class="page-header">Informações do Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= $evento['nome_evento']; ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="box-group" id="accordion">
                            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <div class="box-body">
                                <p>Protocolo: <?= $protocolo['protocolo']; ?></p>
                                <p>Tipo evento: <?= $tipo_evento['tipo_evento']; ?></p>
                                <p>É original? <?= $original ?></p>
                                <p>Relação Juridica: <?= $relacao_juridica['relacao_juridica'] ?></p>
                                <p>Projeto Especial: <?= $projeto_especial['projeto_especial'] ?></p>
                                <p>Sinopse: <?= $evento['sinopse'] ?></p>
                                <p>Fiscal: <?= $fical['nome_completo'] ?></p>
                                <p>Suplente: <?= $suplente['nome_completo'] ?></p>
                                <p>Cadastramento realizado por: <?= $usuario['nome_completo'] ?></p>
                                <p>Haverá contratação? <?= $contratacao ?></p>
                                <p>Status do Evento: <?= $evento_status['status'] ?></p>
                            </div>
                        </div>

                        <?php
                        $query_atracao = mysqli_query($con, $sql_atracao);
                        while ($atracao = mysqli_fetch_array($query_atracao)) {

                            $categoria_atracao = recuperaDados('categoria_atracoes', 'id', $atracao['categoria_atracao_id']);
                            $classificacao_indicativa = recuperaDados('classificacao_indicativas', 'id', $atracao['classificacao_indicativa_id']);

                            $idAtracao = $atracao['id'];
                            $sql_ocorrencia = "SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = '$idAtracao' AND publicado = 1";

                            ?>
                            <div class="box-group" id="accordion">
                                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                <div class="box-body">
                                    <p>Nome da atração: <?= $atracao['nome_atracao']; ?></p>
                                    <p>Categoria da atração: <?= $categoria_atracao['categoria_atracao']; ?></p>
                                    <p>Ficha técnica:<br> <?= $atracao['ficha_tecnica']; ?></p>
                                    <p>Integrantes: <?= $atracao['integrantes']; ?></p>
                                    <p>Classificação
                                        Indicativa: <?= $classificacao_indicativa['classificacao_indicativa']; ?></p>
                                    <p>Release:<br> <?= $atracao['release_comunicacao']; ?></p>

                                    <?php
                                    if ($atracao['links'] != NULL) {
                                        ?>
                                        <p>Links: <?= $atracao['links']; ?></p>
                                    <?php } ?>

                                    <p>Quantidade de Apresentação: <?= $atracao['quantidade_apresentacao']; ?></p>

                                    <?php
                                    if ($atracao['valor_individual'] != NULL) {
                                        ?>
                                        <p>Valor individual: <?= dinheiroParaBr($atracao['valor_individual']); ?></p>
                                    <?php }

                                    if ($atracao['produtor_id'] != NULL) {
                                        $produtor = recuperaDados('produtores', 'id', $atracao['produtor_id']);
                                        ?>
                                        <p>Nome Produtor: <?= $produtor['nome']; ?></p>
                                        <p>Email do Produtor: <?= $produtor['email']; ?></p>
                                        <p>Telefone 1: <?= $produtor['telefone1']; ?></p>
                                        <p>Telefone 2: <?= $produtor['telefone2']; ?></p>
                                        <p>Observação: <?= $produtor['observacao']; ?></p>
                                    <?php } ?>
                                </div>

                            </div>
                            <?php
                            $query_ocorrencia = mysqli_query($con, $sql_ocorrencia);
                            while ($ocorrencia = mysqli_fetch_array($query_ocorrencia)) {

                                $local = recuperaDados('locais', 'id', $ocorrencia['local_id']);

                                ?>

                                <div class="box-group" id="accordion">
                                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                    <div class="box-body">
                                        <h4>Ocorrências</h4>
                                        <p>Local: <?= $local['local']; ?></p>
                                    </div>
                                </div>

                            <?php }
                        }

                        ?>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

    </section>
    <!-- /.content -->
</div>
