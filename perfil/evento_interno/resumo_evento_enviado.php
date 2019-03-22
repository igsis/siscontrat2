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
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><strong><?= $evento['nome_evento']; ?></strong></h3>
                    </div>
                    <div class="box-body">
                        <div class="box-group" id="accordion">
                            <div class="row">
                                <div class="box-body">
                                    <div class="form-group col-md-4">
                                        <label for="protocolo">Protocolo:</label>
                                        <input type="text" class="form-control" id="protocolo" name="nomeProtocolo"
                                               maxlength="250" required readonly
                                               value="<?= $protocolo['protocolo'] ?>">
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label for="nomeEvento">Nome do Evento:</label>
                                        <input type="text" class="form-control" id="evento" name="nomeEvento"
                                               maxlength="250" required readonly
                                               value="<?= $evento ['nome_evento']; ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="Periodo">Período:</label>
                                        <input type="text" class="form-control" id="periodo" name="periodo"
                                               maxlength="250"
                                               required readonly value=" <?= retornaPeriodoNovo($idEvento); ?>">
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label for="tipoEvento">Tipo evento:</label>
                                        <input type="text" class="form-control" id="relacaoJuridica"
                                               name="relacaoJuridica"
                                               maxlength="250" required readonly
                                               value="<?= $tipo_evento['tipo_evento']; ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="original"><strong>É original?</strong></label>
                                        <input type="text" class="form-control" id="original" name="original"
                                               maxlength="250" required readonly value="<?= $evento['original']; ?>">
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="relacaoJuridica">Relação Juridica:</label>
                                        <input type="text" class="form-control" id="relacaoJuridica"
                                               name="relacaoJuridica" maxlength="250"
                                               readonly value="<?= $relacao_juridica['relacao_juridica']; ?>">
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="projetoEspecial">Projeto Especial:</label>
                                        <input type="text" class="form-control" id="projetoEspecial"
                                               name="projetoEspecial" maxlength="250" required
                                               readonly value="<?= $projeto_especial['projeto_especial']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="sinopse">Sinopse:</label>
                                        <textarea name="sinopse" id="sinopse" class="form-control" rows="5"
                                                  required readonly><?= $evento['sinopse']; ?></textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="fiscal">Fiscal:</label>
                                        <input type="text" class="form-control" id="fiscal"
                                               name="fiscal" required readonly
                                               value="<?= $fical['nome_completo'] ?> ">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="suplente">Suplente:</label>
                                        <input type="text" class="form-control" id="suplente"
                                               name="suplente" maxlength="250" required readonly
                                               value=<?= $suplente['nome_completo']; ?>>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="cadastramento">Cadastramento realizado por:</label>
                                        <input type="text" class="form-control" id="cadastramento"
                                               name="cadastramento" maxlength="250" required readonly
                                               value=<?= $usuario['nome_completo'] ?>>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="contratacao">Haverá contratação?</label>
                                        <input type="text" class="form-control" id="contratacao"
                                               name="contratacao" maxlength="250" required readonly
                                               value=<?= $contratacao ?>>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="status">Status do Evento:</label>
                                        <input type="text" class="form-control" id="status"
                                               name="status" maxlength="250" required
                                               readonly value=<?= $evento_status['status'];?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <hr>
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
                                    <p>Categoria da
                                        atração: <?= $categoria_atracao['categoria_atracao']; ?></p>
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

                                    <p>Quantidade de
                                        Apresentação: <?= $atracao['quantidade_apresentacao']; ?></p>

                                    <?php
                                    if ($atracao['valor_individual'] != NULL) {
                                        ?>
                                        <p>Valor
                                            individual: <?= dinheiroParaBr($atracao['valor_individual']); ?></p>
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
                                $retirada_ingresso = recuperaDados('retirada_ingressos', 'id', $ocorrencia['retirada_ingresso_id']);

                                ?>

                                <div class="box-group" id="accordion">
                                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                    <div class="box-body">
                                        <h4>Ocorrências</h4>
                                        <p>Local: <?= $local['local']; ?></p>
                                        <?php
                                        if ($ocorrencia['horario_inicio'] != NULL) {
                                            ?>
                                            <p>Horário
                                                Início: <?= exibirHora($ocorrencia['horario_inicio']); ?></p>
                                        <?php } ?>
                                        <?php
                                        if ($ocorrencia['horario_fim'] != NULL) {
                                            ?>
                                            <p>Horário
                                                Fim: <?= exibirHora($ocorrencia['horario_fim']); ?></p>
                                        <?php } ?>
                                        <p>Retirada de
                                            Ingresso:<?= $retirada_ingresso['retirada_ingresso'] ?></p>
                                        <?php
                                        if ($ocorrencia['virada'] != NULL) {
                                            ?>
                                            <p>Virada: <?= $ocorrencia['virada']; ?></p>
                                        <?php } ?>
                                        <?php
                                        if ($ocorrencia['publicado'] != NULL) {
                                            ?>
                                            <p>Publicado: <?= $ocorrencia['publicado']; ?></p>
                                        <?php } ?>
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
