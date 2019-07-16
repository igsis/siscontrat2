<?php
/**
 * Conteúdo da label "#pedido" do arquivo "vizualizacao_evento.php"
 */

$pedido = $con->query("SELECT * FROM pedidos WHERE origem_tipo_id = '1' AND origem_id = '$idEvento' publicado = '1'");
$verba = recuperaDados('verbas', 'id', $pedido['verba_id']) ['verba'];

if ($pedido != null) {
    $dadosPedido = [
        'Verba' => $verba,
        'Valor Total' => "R$" . dinheiroParaBr($pedido['valor_total']),
        'Número de Parcelas' => $pedido['numero_parcelas'],
        'Data Kit Pagamento' => exibirDataBr($pedido['data_kit_pagamento']),
        'Forma de Pagamento' => $pedido['forma_pagamento'],
        'Observação' => $pedido['observacao']
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
        $sqlTelefones = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '" . $pedido['pessoa_fisica_id'] . "' AND publicado = '1'";
        $telefones = $con->query($sqlTelefones)->fetch_all();
        $proponente = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);
        $nacionalidade = recuperaDados('nacionalidades', 'id', $proponente['nacionalidade_id'])['nacionalidade'];
        $endereco = recuperaDados('pf_enderecos', 'pessoa_fisica_id', $pedido['pessoa_fisica_id']);
        $pfBancos = recuperaDados('pf_bancos', 'pessoa_fisica_id', $pedido['pessoa_fisica_id']);
        $banco = recuperaDados('bancos', 'id', $pedido['banco_id']) ['banco'];
        $dadosPreponente = [
            'Nome' => $proponente['nome'],
            'Nome Artístico' => $proponente['nome_artistico'],
            'Nacionalidade' => $nacionalidade,
            'RG' => $proponente['rg'],
            'Passaporte' => $proponente['passaporte'],
            'CPF' => $proponente['cpf'],
            'CCM' => $proponente['ccm'],
            'Data de Nascimento' => exibirDataBr($proponente['data_nascimento']),
            'E-Mail' => $proponente['email'],
            'Telfone #1' => $telefones [0] [0] ?? "Não Cadastrado",
            'Telfone #2' => $telefones [1] [0] ?? "Não Cadastrado",
            'Telfone #3' => $telefones [2] [0] ?? null,
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
            'Agência' => $pfBancos['agencia'],
            'Conta' => $pfBancos['conta']
        ];
        break;

    case 2:

        break;
}

?>
