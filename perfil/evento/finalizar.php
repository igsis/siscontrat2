<?php
include "includes/menu_interno.php";


/*$ocorrencias_rept = false;*/

$sqlEvento = "SELECT
               eve.nome_evento AS 'Nome do Evento',
               te.tipo_evento AS 'Tipo do Evento',
               rj.relacao_juridica AS 'Tipo de Relação Jurídica',
               pe.projeto_especial AS 'Projeto Especial',
               eve.sinopse AS 'Sinopse',
               fiscal.nome_completo AS 'Fiscal',
               suplente.nome_completo AS 'Suplente',
               usuario.nome_completo AS 'Usuário que cadastrou',
               eve.espaco_publico AS 'Evento Público',
               eve.fomento AS 'Fomento'
               
                FROM eventos AS eve
                INNER JOIN  tipo_eventos AS te ON eve.tipo_evento_id = te.id
                INNER JOIN relacao_juridicas AS rj ON eve.relacao_juridica_id = rj.id
                INNER JOIN projeto_especiais AS pe ON eve.projeto_especial_id = pe.id
                INNER JOIN usuarios AS fiscal ON eve.fiscal_id = fiscal.id
                INNER JOIN usuarios AS suplente ON eve.suplente_id = suplente.id
                INNER JOIN usuarios AS usuario ON eve.usuario_id = usuario.id
                
                WHERE eve.id = '$idEvento'";

$resumoEvento = $con->query($sqlEvento)->fetch_assoc();

if ($evento['tipo_evento_id'] == 1 ) {
    $ocorrencia_atracao = "SELECT  	o.tipo_ocorrencia_id,
			o.origem_ocorrencia_id,
			o.instituicao_id,local_id,
			o.espaco_id,
			o.data_inicio,
			o.data_fim,
			o.segunda,o.terca
			,o.quarta,
			o.quinta,
			o.sexta,
			o.sabado,
			o.domingo,
			o.horario_inicio, 
			o.horario_fim, 
			o.retirada_ingresso_id,
			o.valor_ingresso,
			o.observacao,
			o.periodo_id,
			o.subprefeitura_id,
			o.virada,
			o.libras,
			o.audiodescricao
FROM ocorrencias AS o 
INNER JOIN atracoes AS a ON o.atracao_id = a.id
INNER JOIN eventos AS e ON e.id = a.evento_id
WHERE e.id = '$idEvento' AND e.publicado = 1 AND o.publicado = 1";

    $result = mysqli_fetch_all(mysqli_query($con, $ocorrencia_atracao));

} else {
    $ocorrencia_filmes = "SELECT  	o.tipo_ocorrencia_id,
			o.origem_ocorrencia_id,
			o.instituicao_id,local_id,
			o.espaco_id,
			o.data_inicio,
			o.data_fim,
			o.segunda,o.terca
			,o.quarta,
			o.quinta,
			o.sexta,
			o.sabado,
			o.domingo,
			o.horario_inicio, 
			o.horario_fim, 
			o.retirada_ingresso_id,
			o.valor_ingresso,
			o.observacao,
			o.periodo_id,
			o.subprefeitura_id,
			o.virada,
			o.libras,
			o.audiodescricao
FROM ocorrencias AS o 
INNER JOIN filme_eventos AS fe ON fe.id = o.atracao_id 
INNER JOIN eventos AS e ON fe.evento_id = e.id 
WHERE e.id = '$idEvento' AND e.publicado = 1 AND o.publicado = 1";


    $result = mysqli_fetch_all(mysqli_query($con, $ocorrencia_filmes));
}
$quant = count($result);

/*$contad = 0;
for ($i = 0; $i < $quant; $i++) {
    for ($j = 1; $j < $quant; $j++) {
        for ($k = 0; $k < $quant; $k++) {
            if ($result[$i][$k] == $result[$j][$k]) {
                $contad += 1;
                if ($contad == 6) {
                    $ocorrencias_rept = true;
                    $contad = 0;
                }
            }
        }
    }
}*/


include "includes/validacoes.php";

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Pendências </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <?php if (count($erros) == 0) { ?>
                    <div class="alert alert-success alert-dismissible">
                        <h4><i class="icon fa fa-check"></i> Seu Evento Não Possui Pendências !</h4>

                        <p>Confirme todos os dados abaixo antes de enviar.</p>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-danger">
                        <h4><i class="icon fa fa-ban"></i> Seu Evento Possui Pendências !</h4>

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
                <?php
                if ($evento['contratacao'] == 1) {
                    if (count($errosArqs) == 0) { ?>
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
                    <?php }
                } else {
                    $errosArqs = [];
                }
                ?>
            </div>
        </div>

        <h2 class="page-header">Finalizar <em class="pull-right"><?php if (isset($prazo)) {
                    echo $prazo;
                }; ?></em></h2>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <?php if ($evento['contratacao'] == 1) { ?>
                    <li><a href="#pedido" data-toggle="tab">Pedido de Contratação</a></li>
                <?php } ?>
                <li><a href="#ocorrencia" data-toggle="tab">Ocorrências</a></li>
                <li>
                    <a href="#atracao" data-toggle="tab">
                        <?= ($evento['tipo_evento_id'] == 1) ? "Atração" : "Filme" ?>
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
                                            <th width="30%"><?= $campo ?>:</th>
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
                                            }
                                            ?>
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

                    $mostra = true;

                    if ((isset($numAtracoes) && $numAtracoes == 0) && $evento['tipo_evento_id'] == 1) { ?>
                        <div class="alert alert-danger">
                            <h4><i class="icon fa fa-ban"></i>Não há atrações cadastradas</h4>
                        </div>
                        <?php
                        $mostra = false;
                    } else {
                        if ((isset($numFilmes) && $numFilmes == 0) && $evento['tipo_evento_id'] == 2) {
                            ?>
                            <div class="alert alert-danger">
                                <h4><i class="icon fa fa-ban"></i>Não há filmes cadastrados</h4>
                            </div>
                            <?php
                            $mostra = false;
                        }
                        if ($mostra) {
                            include "label_atracao_filme.php";
                        }
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
                    <input type="hidden" name="idEvento" id="idEvento" value="<?= $idEvento ?>">
                    <input type="hidden" name="fora" value="<?= $fora ?? 0 ?>">
                    <?php 
                        


                        if(count($erros) != 0 || count($errosArqs) != 0/* || $ocorrencias_rept == true*/){
                            $disabled =  "disabled";
                        }else{
                            $disabled = "";
                        }
                    ?>
                    
                    <?php
                    if ($evento['tipo_evento_id'] == 1) {
                        ?>
                        <button class="btn btn-success" name="enviar" type="submit" <?=$disabled?>>                                
                            Enviar Evento
                        </button>
                        <?php
                    } else {
                        ?>
                        <button class="btn btn-success" name="enviar" type="submit" <?=$disabled?>>
                            Enviar Evento
                        </button>
                        <?php
                    }
                    ?>
                </form>
            </div>
        </div>
    </section>
</div>