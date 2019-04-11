<?php
include "includes/menu_interno.php";

$sqlEvento = "SELECT
               eve.nome_evento AS 'Nome do Evento',
               te.tipo_evento AS 'Tipo do Evento',
               rj.relacao_juridica AS 'Tipo de Relação Jurídica',
               pe.projeto_especial AS 'Projeto Especial',
               eve.sinopse AS 'Sinopse',
               fiscal.nome_completo AS 'Fiscal',
               suplente.nome_completo AS 'Suplente'
                FROM eventos AS eve
                INNER JOIN tipo_eventos AS te ON eve.tipo_evento_id = te.id
                INNER JOIN relacao_juridicas AS rj ON eve.relacao_juridica_id = rj.id
                INNER JOIN projeto_especiais AS pe ON eve.projeto_especial_id = pe.id
                INNER JOIN usuarios AS fiscal ON eve.fiscal_id = fiscal.id
                INNER JOIN usuarios AS suplente ON eve.suplente_id = suplente.id
                WHERE eve.id = '$idEvento'";

$resumoEvento = $con->query($sqlEvento)->fetch_assoc();

include "includes/validacoes.php";

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Pendencias</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <?php if (count($erros) == 0) { ?>
                    <div class="alert alert-success alert-dismissible">
                        <h4><i class="icon fa fa-check"></i> Seu Evento Não Possui Pendencias!</h4>

                        <p>Confirme todos os dados abaixo antes de enviar.</p>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-danger">
                        <h4><i class="icon fa fa-ban"></i> Seu Evento Possui Pendencias!</h4>

                        <ul>
                            <?php foreach ($erros as $erro) {
                                echo "<li>$erro</li>";
                            }
                            ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-6">
                <?php if (count($errosArqs) == 0) { ?>
                    <div class="alert alert-success alert-dismissible">
                        <h4><i class="icon fa fa-check"></i> Todos os Arquivos Foram Enviados!</h4>

                        <p>Confirme todos os dados abaixo antes de enviar.</p>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-danger">
                        <h4><i class="icon fa fa-ban"></i> Alguns Arquivos não Foram Enviados!</h4>

                        <ul>
                            <?php foreach ($errosArqs as $erroArq) {
                                echo "<li>$erroArq</li>";
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
                <?php if ($evento['contratacao'] == 1) { ?>
                    <li><a href="#pedido" data-toggle="tab">Pedido de Contratação</a></li>
                <?php } ?>
                <li><a href="#ocorrencia" data-toggle="tab">Ocorrências</a></li>
                <li>
                    <a href="#atracao" data-toggle="tab">
                        <?= ($evento['tipo_evento_id'] == 1) ? "Atração" : "Filme"?>
                    </a>
                </li>
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
                            <div class="table-responsive">
                                <table class="table">
                                    <?php foreach ($resumoEvento as $campo => $dado) { ?>
                                        <tr>
                                            <th width="30%"><?= $campo ?></th>
                                            <td><?=$dado?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="atracao">
                    <?php if ($numAtracoes == 0) { ?>
                        <div class="alert alert-danger">
                            <h4><i class="icon fa fa-ban"></i>Não há atrações cadastradas</h4>
                        </div>
                    <?php } else {
                        include "label_atracao_filme.php";
                    } ?>
                </div>

                <div class="tab-pane" id="ocorrencia">
                    <?php include "label_ocorrencia.php" ?>
                </div>

                <?php if ($evento['contratacao'] == 1) { ?>
                    <div class="tab-pane" id="pedido">
                        <?php include "label_pedido.php" ?>
                    </div>
                <?php } ?>
            </div>
            <div class="box-footer">
                <form action="?perfil=evento&p=resumo_evento_enviado" method="post">
                    <input type="hidden" name="idEvento" id="idEvento" value="<?=$idEvento?>">
                    <button class="btn btn-success" name="enviar" type="submit" >Enviar Evento</button>
                </form>
            </div>
        </div>
    </section>
</div>