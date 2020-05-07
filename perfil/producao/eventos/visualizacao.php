<?php
$con = bancoMysqli();

$_SESSION['idEvento'] = $_POST['idEvento'];
$idUser = $_SESSION['usuario_id_s'];

$idEvento = $_SESSION['idEvento'];

$sqlEvento = "SELECT
                eve.nome_evento AS 'Nome do Evento:',
                te.tipo_evento AS 'Tipo do Evento:',
                rj.relacao_juridica AS 'Tipo de Relação Jurídica:',
                pe.projeto_especial AS 'Projeto Especial:',
                eve.sinopse AS 'Sinopse:',
                fiscal.nome_completo AS 'Fiscal:',
                suplente.nome_completo AS 'Suplente:',
                eve.espaco_publico AS 'Evento Público:',
                eve.fomento AS 'Fomento:',
                env.visualizado AS 'Visualizado:'
                FROM eventos AS eve
                INNER JOIN tipo_eventos AS te ON eve.tipo_evento_id = te.id
                INNER JOIN relacao_juridicas AS rj ON eve.relacao_juridica_id = rj.id
                INNER JOIN projeto_especiais AS pe ON eve.projeto_especial_id = pe.id
                INNER JOIN usuarios AS fiscal ON eve.fiscal_id = fiscal.id
                INNER JOIN usuarios AS suplente ON eve.suplente_id = suplente.id
                INNER JOIN producao_eventos AS env ON env.evento_id = eve.id 
                WHERE eve.id = '$idEvento'";

$resumoEvento = $con->query($sqlEvento)->fetch_assoc();
$evento = recuperaDados('eventos', 'id', $idEvento);
$pedido = recuperaDados('pedidos','origem_id',$idEvento);
$idPedido = $pedido['origem_id'];
$view = recuperaDados('producao_eventos', 'evento_id', $idEvento);

?>

<div class="content-wrapper">
    <section class="content-header">

        <div class="box">
            <div class="box-header">
                <h3 class="page-header"> Evento selecionado </h3>
            </div>

        </div>
        <div class="box-body">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                   
                    <li><a href="#ocorrencia" data-toggle="tab"> Ocorrência </a></li>
                    <li>
                        <a href="#atracao" data-toggle="tab">
                            <?= $evento['tipo_evento_id'] == 1 ? "Atração" : "Filme" ?>
                        </a>
                    </li>
                    <li class="active"><a href="#evento" data-toggle="tab"> Eventos </a></li>
                    <li class="pull-left header"> Confirmação dos Dados Inseridos</li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="evento">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Dados do Evento</h3>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <?php foreach ($resumoEvento as $campo => $dado) { ?>
                                            <tr>
                                                <th width="30%"><?= $campo ?>  </th>
                                                <?php
                                                if ($campo == "Evento Público:") {
                                                    if ($dado == 0) {
                                                        $dado = "Não";
                                                    } else {
                                                        $dado = "Sim";
                                                    }
                                                }
                                                if ($campo == "Fomento:") {
                                                    if ($dado == 0) {
                                                        $dado = "Não possui";
                                                    } else {
                                                        $fomentoRelacionado = recuperaDados("evento_fomento", "evento_id", $idEvento);
                                                        $fomento = recuperaDados("fomentos", "id", $fomentoRelacionado['fomento_id']);
                                                        $dado = $fomento['fomento'];
                                                    }
                                                }
                                                if ($campo == "Visualizado:") {
                                                    if ($dado == 0) {
                                                        $dado = "Não";
                                                    } else {
                                                        $dado = "Sim";
                                                    }
                                                }
                                                ?>
                                                <td><?= $dado ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Anexos de Comunicação e Produção</h3>
                            </div>
                            <div class="box-body">
                                <?php
                                $sql = "SELECT *
                                            FROM lista_documentos as list
                                            INNER JOIN arquivos as arq ON arq.lista_documento_id = list.id
                                            WHERE arq.origem_id = '$idPedido' AND list.tipo_documento_id = 8
                                            AND arq.publicado = '1' ORDER BY arq.id";
                                $query = mysqli_query($con, $sql);
                                $linhas = mysqli_num_rows($query);

                                if ($linhas > 0):
                                ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr class='bg-info text-bold'>
                                            <td>Tipo de arquivo</td>
                                            <td>Nome do documento</td>
                                            <td>Data de envio</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        while ($arquivo = mysqli_fetch_array($query)) {
                                            ?>
                                            <tr>
                                                <td class='list_description'><?= $arquivo['documento'] ?></td>
                                                <td class='list_description'><a href='../uploadsdocs/<?= $arquivo['arquivo'] ?>'
                                                                                target='_blank'>
                                                        <?= mb_strimwidth($arquivo['arquivo'], 15, 25, "...") ?></a>
                                                </td>
                                                <td class='list_description'>(<?= exibirDataBr($arquivo['data']) ?>)</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="atracao">
                        <?php
                        include "../perfil/producao/includes/label_atracao_filme.php"
                        ?>
                    </div>

                    <div class="tab-pane" id="ocorrencia">
                        <?php include "../perfil/producao/includes/label_ocorrencia_producao.php"; ?>
                    </div>

                    <?php if ($evento['contratacao'] == 1) { ?>
                        <div class="tab-pane" id="pedido">
                            <?php include "../perfil/producao/includes/label_pedido_producao.php"; ?>
                        </div>
                    <?php } ?>

                    <div class="box-footer">
                        <form action="?perfil=producao&p=eventos&sp=verificados" method="post">
                            <input type="hidden" name="idEvento" id="idEvento" value="<?= $idEvento ?>">
                            <?php

                            if ($view['visualizado'] == 0){
                            ?>
                            <button type="submit" name="checarEvento" class="btn btn-success"> Checar
                                visualização
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
    </section>
</div>


