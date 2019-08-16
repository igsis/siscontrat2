<?php
/**
 * Conteúdo da label "#pedido" do arquivo "vizualizacao_evento.php"
 */

$pedido = $con->query("SELECT * FROM pedidos WHERE origem_tipo_id = '1' AND origem_id = '$idEvento' AND publicado = '1' ")->fetch_array();
$verba = recuperaDados('verbas', 'id', $pedido['verba_id'])['verba'];

if ($pedido != null) {
    $dadosPedido = [
        'Verba:' => $verba,
        'Valor total:' => "R$ " . dinheiroParaBr($pedido['valor_total']),
        'Número de Parcelas:' => $pedido['numero_parcelas'],
        'Data Kit Pagamento:' =>  exibirDataBr($pedido['data_kit_pagamento']),
        'Forma Pagamento:' => $pedido['forma_pagamento'],
        'Observação:' => $pedido['observacao']
    ];
    $idPedido = $pedido['id'];
    $equipamentoValor = "SELECT local.local, valor.valor FROM valor_equipamentos valor
                         INNER JOIN locais local ON local.id = valor.local_id WHERE pedido_id = '$idPedido'";
    $queryEquipamentos = mysqli_query($con, $equipamentoValor);
    $numRow = mysqli_num_rows($queryEquipamentos);
    if ($numRow > 0) {
        while ($equipamento = mysqli_fetch_array($queryEquipamentos)) {
            $dadosPedido += [
                $equipamento['local'] => 'R$' . dinheiroParaBr($equipamento['valor'])
            ];
        }
    }
} else {
    $dadosPedido = null;
}
switch ($pedido['pessoa_tipo_id']) {
    case 1:
        $tipo = "Pessoa Física";
        $sqlTelefones = "SELECT telefone FROM pf_telefones WHERE pessoa_fisica_id = '" . $pedido['pessoa_fisica_id'] . "' AND publicado = '1'";
        $telefones = $con->query($sqlTelefones)->fetch_all();
        $proponente = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);
        $nacionalidade = recuperaDados('nacionalidades', 'id', $proponente['nacionalidade_id'])['nacionalidade'];
        $endereco = recuperaDados('pf_enderecos', 'pessoa_fisica_id', $pedido['pessoa_fisica_id']);
        $pfBancos = recuperaDados('pf_bancos', 'pessoa_fisica_id', $pedido['pessoa_fisica_id']);
        $banco = recuperaDados('bancos', 'id', $pfBancos['banco_id'])['banco'];
        $dadosPreponente = [
            'Nome' => $proponente['nome'],
            'Nome Artístico' => $proponente['nome_artistico'],
            'Nacionalidade' => $nacionalidade,
            'RG' => $proponente['rg'] ?? "Não Cadastrado",
            'Passaporte' => $proponente['passaporte'] ? : "Não Cadastrado",
            'CPF' => $proponente['cpf'],
            'CCM' => $proponente['ccm'],
            'Data de Nascimento' => exibirDataBr($proponente['data_nascimento']),
            'E-Mail' => $proponente['email'],
            'Telefone #1' => $telefones[0][0] ? : "Não Cadastrado",
            'Telefone #2' => $telefones[1][0] ? : "Não Cadastrado",
            'Telefone #3' => $telefones[2][0] ? : "Não Cadastrado",
        ];
        $dadosEndereco = [
            'CEP' => $endereco['cep'],
            'Logradouro' => $endereco['logradouro'],
            'Complemento' => $endereco['complemento'] ? : "Não possui",
            'Bairro' => $endereco['bairro'],
            'Cidade' => $endereco['cidade'],
            'Estado' => $endereco['uf']
        ];
        $dadosBancarios = [
            'Banco' => $banco,
            'Agência' => $pfBancos['agencia'],
            'Conta' => $pfBancos['conta']
        ];
        break;

    case 2:
        $tipo = "Pessoa Jurídica";
        $proponente = recuperaDados('pessoa_juridica', 'id', $pedido['pessoa_juridica_id']);
        $endereco = recuperaDados('pj_enderecos', 'pessoa_juridica_id', $pedido['pessoa_juridica_id']);
        $pjBancos = recuperaDados('pj_bancos', 'pessoa_juridica_id', $pedido['pessoa_juridica_id']);
        $banco = recuperaDados('bancos', 'id', $pjBancos['banco_id'])['banco'];
        $idRepresentante1 = $pedido['representante_legal1_id'] ?? "";
        $idRepresentante2 = $pedido['representante_legal2_id'] ?? "";
        $representante1 = recuperaDados('representante_legais', 'id', $idRepresentante1);
        $representante2 = recuperaDados('representante_legais', 'id', $idRepresentante2);

        $dadosPreponente = [
            'Razão Social' => $proponente['razao_social'],
            'CNPJ' => $proponente['cnpj'],
            'CCM' => $proponente['ccm']
        ];

        $dadosEndereco = [
            'CEP' => $endereco['cep'],
            'Logradouro' => $endereco['logradouro'],
            'Complemento' => $endereco['complemento'],
            'Bairro' => $endereco['bairro'],
            'Cidade' => $endereco['cidade'],
            'Estado' => $endereco['uf']
        ];

        $dadosBancarios = [
            'Banco' => $banco,
            'Agência' => $pjBancos['agencia'],
            'Conta' => $pjBancos['conta']
        ];

        $dadosRepresentante1 = [
            'Nome' => $representante1['nome'],
            'RG' => $representante1['rg'],
            'CPF' => $representante1['cpf']
        ];

        $dadosRepresentante2 = [
            'Nome' => $representante2['nome'],
            'RG' => $representante2['rg'],
            'CPF' => $representante2['cpf']
        ];
        break;

    default:
        $tipo = "";
        $dadosPreponente = ["Não há Dados Cadastrados" => ""];
        $dadosEndereco = ["Não há Dados Cadastrados" => ""];
        $dadosBancarios = ["Não há Dados Cadastrados" => ""];
        break;
}

$parcelado = false;
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"> Dados do Pedido:</h3>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table">
                <?php
                if ($dadosPedido != null) {
                    foreach ($dadosPedido as $campo => $dado) {
                        ?>
                        <tr>
                            <th width="40%"><?= $campo ?></th>
                            <td><?= $dado ?></td>
                        </tr>
                    <?php }
                }
                ?>
            </table>
        </div>
    </div>
</div>

<h2 class="page-header">Proponente
    <small><?= $tipo ?></small>
</h2>
<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Dados do Preponente</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <?php
                        foreach ($dadosPreponente as $campo => $dado) {
                            if (($campo == "Passaporte") && ($dado == "")) {
                                continue;
                            } elseif (($campo == "CPF") && ($dado == "")) {
                                continue;
                            } ?>
                            <tr>
                                <th width="40%"><?= $campo ?>:</th>
                                <td><?= $dado ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <?php if ($pedido['pessoa_tipo_id'] == 2) { ?>
            <div class="box-group" id="accordionRepresentate">
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordionRepresentante"
                               href="#collapseRepresentante1">
                                Representante Legal #1
                            </a>
                        </h4>
                    </div>
                    <div id="collapseRepresentante1" class="panel-collapse collapse">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <?php foreach ($dadosRepresentante1 as $campo => $dado) { ?>
                                        <tr>
                                            <th width="40%"><?= $campo ?>:</th>
                                            <td><?= $dado ?></td>
                                        </tr>
                                    <?php }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordionRepresentante"
                               href="#collapseRepresentante2">
                                Representante Legal #2
                            </a>
                        </h4>
                    </div>
                    <div id="collapseRepresentante2" class="panel-collapse collapse">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <?php foreach ($dadosRepresentante2

                                                   as $campo => $dado) ?>
                                    <tr>
                                        <th width="40%"><?= $campo ?>:</th>
                                        <td><?= $dado ?></td>
                                    </tr>
                                    <?php ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Endereço do Preponente </h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <?php foreach($dadosEndereco as $campo => $dado) {?>
                            <tr>
                                <th width="40%"><?=$campo?>:</th>
                                <td><?=$dado?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="box-header with-border">
            <h3 class="box-title">
               Dados Bancarios</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table">
                    <?php foreach($dadosBancarios as $campo => $dado) { ?>
                        <tr>
                            <th width="40%"><?=$campo?>:</th>
                            <td><?=$dado?></td>
                        </tr>
                    <?php }?>
                </table>
            </div>
        </div>
    </div>
</div>

