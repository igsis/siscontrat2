<?php
/**
 * Conteúdo da label "#pedido" do arquivo "finalizar.php"
 */

$pedido = $con->query("SELECT * FROM pedidos WHERE origem_tipo_id = '1' AND origem_id = '$idEvento' AND publicado = '1'")->fetch_assoc();
$verba = recuperaDados('verbas', 'id', $pedido['verba_id'])['verba'];

$dadosPedido = [
    'Verba' => $verba,
    'Valor Total' => dinheiroParaBr($pedido['valor_total']),
    'Número de Parcelas' => $pedido['numero_parcelas'],
    'Data Kit Pagamento' => exibirDataBr($pedido['data_kit_pagamento']),
    'Forma de Pagamento' => $pedido['forma_pagamento'],
    'Observação' => $pedido['observacao']
];

switch ($pedido['pessoa_tipo_id']) {
    case 1:
        $proponente = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);
        $nascionalidade = recuperaDados('nacionalidades', 'id', $proponente['nacionalidade_id']);
        $dadosProponente = [
            'Nome' => $proponente['nome'],
            'Nome Artístico' => $proponente['nome_artistico'],
            'RG' => $proponente['rg'],
            'Passaporte' => $proponente['passaporte'],
            'CPF' => $proponente['cpf'],
            'CCM' => $proponente['ccm'],
            'Data de Nascimento' => exibirDataBr($proponente['data_nascimento']),
            'E-mail' => $proponente['email']
        ];
        break;

    case 2:
        $proponente = recuperaDados('pessoa_juridicas', 'id', $pedido['pessoa_juridica_id']);
        $dadosProponente = [
            'Razão Social' => $proponente['razao_social'],
            'CNPJ' => $proponente['cnpj'],
            'CCM' => $proponente['ccm'],
        ];
        break;

    default:
        break;
}

$parcelado = false;

?>

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Dados do Pedido</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <?php foreach ($dadosPedido as $campo => $dado) { ?>
                            <tr>
                                <th width="40%"><?=$campo?>:</th>
                                <td><?=$dado?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Dados do Proponente</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <?php foreach ($dadosProponente as $campo => $dado) {
                            if (($campo == "Passaporte") && ($dado == "")) {
                                continue;
                            } elseif (($campo == "CPF") && ($dado == "")) {
                                continue;
                            }
                        ?>
                            <tr>
                                <th width="40%"><?=$campo?>:</th>
                                <td><?=$dado?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>