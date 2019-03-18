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

$erros = [];

if ($evento['tipo_evento_id'] == 1) {
    $semEspecificidade = ['1', '6', '8', '9', '12', '13', '14', '16', '18', '19', '20', '21', '22', '25', '26', '27'];
    if ($nAtracoes == 0) {
        array_push($erros, "Não possui atrações cadastradas");
    } else {
        foreach ($queryAtracoes as $atracao) {
            if (($atracao['produtor_id'] == "") || ($atracao['produtor_id'] == null)) {
                array_push($erros,"Produtor não cadastradado na atração <b>".$atracao['nome_atracao']."</b>");
            }

            if (!(in_array($atracao['categoria_atracao_id'],$semEspecificidade))) {
                $idAtracao = $atracao['id'];
                /*@TODO: Consultar todas tabelas de especificidades antes de printar o erro*/
                $numEspecificidades = $con->query("SELECT * FROM especificidades WHERE atracao_id = '$idAtracao'")->num_rows;
                if ($numEspecificidades == 0) {
                    array_push($erros, "Não há especificidade cadastrada para a atração <b>" . $atracao['nome_atracao'] . "</b>");
                }
            }
        }
    }
}

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Pendencias</h1>
    </section>

    <section class="content">
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

        <h2 class="page-header">Finalizar</h2>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li>
                    <a href="#atracao" data-toggle="tab">
                        <?= ($evento['tipo_evento_id'] == 1) ? "Atração" : "Filme"?>
                    </a>
                </li>
                <li class="active"><a href="#evento" data-toggle="tab">Evento</a></li>
                <li class="pull-left header">Resumo do Evento</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="evento">
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
                <div class="tab-pane" id="atracao">
                    Atrações
                </div>
            </div>
            <div class="box-footer">
                <button class="btn btn-success" type="submit" <?= (count($erros) != 0) ? "disabled" : "" ?>>Enviar Evento</button>
            </div>
        </div>
    </section>
</div>
