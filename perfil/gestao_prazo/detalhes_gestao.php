<?php
$con = bancoMysqli();
$conn = bancoPDO();

$_SESSION['idEvento'] = $_POST['idEvento'];
$idEvento = $_POST['idEvento'];
$sqlCarregar = "SELECT
                eve.nome_evento AS 'Nome do Evento:',
                te.tipo_evento AS 'Tipo do Evento:',
                rj.relacao_juridica AS 'Tipo de Relação Jurídica:',
                pe.projeto_especial AS 'Projeto Especial:',
                eve.sinopse AS 'Sinopse:',
                fiscal.nome_completo AS 'Fiscal:',
                suplente.nome_completo AS 'Suplente:',
                eve.espaco_publico AS 'Evento Público:',
                eve.fomento AS 'Fomento:'
                
                FROM eventos AS eve
                INNER JOIN tipo_eventos AS te ON eve.tipo_evento_id = te.id
                INNER JOIN relacao_juridicas AS rj ON eve.relacao_juridica_id = rj.id
                INNER JOIN projeto_especiais AS pe ON eve.projeto_especial_id = pe.id
                INNER JOIN usuarios AS fiscal ON eve.fiscal_id = fiscal.id
                INNER JOIN usuarios AS suplente ON eve.suplente_id = suplente_id
                
                WHERE eve.id = '$idEvento'";
$resumoCarregamento = $con->query($sqlCarregar)->fetch_assoc();
$evento = recuperaDados('eventos', 'id', $idEvento);

include "includes/menu_interno.php";
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="box">
            <div>
                <div class="box-header">
                    <h3 class="page-header">Pedido Selecionado</h3><em class="pull-right"><?php if (isset($prazo)) {
                            echo $prazo;
                        };
                        ?></em>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs pull-right">
                        <?php if ($evento['contratacao'] == 1) { ?>
                            <li><a href="#pedido" data-toggle="tab">Pedido de Contratção</a></li>
                        <?php } ?>
                        <li><a href="#ocorrencia" data-toggle="tab">Ocorrência</a></li>
                        <li><a href="#atracao" data-toggle="tab">
                                <?= $evento['tipo_evento_id'] == 1 ? "Atrcação" : "Filme" ?>
                            </a>
                        </li>
                        <li class="active"><a href="#evento" data-toggle="tab">Eventos</a></li>
                        <li class="pull-left header">Confirmação dos Dados Inseridos</li>
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
                                            <?php foreach ($resumoCarregamento as $campo => $dado) { ?>
                                                <tr>
                                                    <th width="30%"><?= $campo ?></th>
                                                    <?php
                                                    if ($campo == "Evento Público") {
                                                        if ($dado == 0) {
                                                            $dado = "Não";
                                                        } else {
                                                            $dado = "Sim";
                                                        }
                                                    }
                                                    if ($campo == "Fomento") {
                                                        if ($dado == 0) {
                                                            $dado = "Não possui";
                                                        } else {
                                                            $fomentoRelacionado = recuperaDados("evento_fomento", "evento_id", $idEvento);
                                                            $fomento = recuperaDados("fomentos", "id", $fomentoRelacionado['fomento_id']);
                                                            $dado = $fomento['fomento'];
                                                        }
                                                    } ?>
                                                    <td><?= $dado ?></td>
                                                </tr>
                                            <?php } ?>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="atracao">
                            <?php
                            include "includes/label_atracao_filme.php";
                            ?>
                        </div>
                        <div class="tab-pane" id="ocorrencia">
                            <?php
                            include "includes/label_ocorrencia_gestao.php";
                            ?>
                        </div>
                        <?php if($evento['contratacao'] == 1) { ?>
                        <div class="tab-pane" id="pedido">
                            <?php
                            include "includes/label_pedido_gestao.php";
                            ?>
                        </div>
                        <?php } ?>

                        <div class="box-footer">
                            <form action="?=perfil=gestao_prazo&p=busca_gestao" method="post">
                                <input type="hidden" name="idEvento" value="<?=$idEvento?>">
                                <button type='button' name='vetar' class='btn btn-block btn-danger' id='excluiEvento'
                                        data-toggle='modal' data-target='#exclusao' name='excluiEvento'
                                ><span class='glyphicon glyphicon-trash'></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
