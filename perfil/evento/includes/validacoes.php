<?php
$con = bancoMysqli();

$idEvento = $_SESSION['idEvento'];

$evento = recuperaDados('eventos', 'id', $idEvento);

$atracoes = $con->query("SELECT * FROM atracoes WHERE evento_id = '$idEvento' AND publicado = '1'");
$numAtracoes = $atracoes->num_rows;

/**
 * <p>Recebe um array multidimensional para ser utilizado na função in_array()</p>
 *
 * @param string|int $needle <p>
 * Valor a ser procurado </p>
 * @param array $haystack <p>
 * Array multidimensional onde deve procurar
 * @return array <p>
 * Retorna um array contendo os indices: <br>
 * 'bool' - false ou true <br>
 * 'especificidade' - indice do array multidimencional onde foi encontrado o valor </p>
 */
function in_array_key($needle, $haystack) {
    $return = [
        'bool' => false,
        'especificidade' => null
    ];

    foreach ($haystack as $key => $array) {
        if (in_array($needle, $array)) {
            $return = [
                'bool' => true,
                'especificidade' => $key
            ];
            return $return;
        }
    }
    return $return;
}

$erros = [];

if ($evento['tipo_evento_id'] == 1) {
    $especificidades = [
        'teatro' => ['3', '7', '23', '24'],
        'musica' => ['10', '11', '15', '17'],
        'exposicoes' => ['2'],
        'oficinas' => ['4', '5']
    ];

    if ($numAtracoes == 0) {
        array_push($erros, "Não possui atrações cadastradas");
    } else {
        foreach ($atracoes as $atracao) {
            if (($atracao['produtor_id'] == "") || ($atracao['produtor_id'] == null)) {
                array_push($erros,"Produtor não cadastrado na atração <b>".$atracao['nome_atracao']."</b>");
            }

            $especificidade = in_array_key($atracao['categoria_atracao_id'], $especificidades);
            $idAtracao = $atracao['id'];
            if ($especificidade['bool']) {
                $tabela = $especificidade['especificidade'];
                $numEspecificidades = $con->query("SELECT * FROM $tabela WHERE atracao_id = '$idAtracao'")->num_rows;
                if ($numEspecificidades == 0) {
                    array_push($erros, "Não há especificidade cadastrada para a atração <b>" . $atracao['nome_atracao'] . "</b>");
                }
            }

            $ocorrencias = $con->query("SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = '$idAtracao' AND publicado = '1'");
            $numOcorrencias = $ocorrencias->num_rows;
            if ($numOcorrencias == 0) {
                array_push($erros, "Não há ocorrência cadastrada para a atração <b>" .$atracao['nome_atracao']. "</b>");
            }
        }
    }

    if ($evento['contratacao'] == 1) {
        $pedidos = $con->query("SELECT * FROM pedidos WHERE origem_tipo_id = '1' AND origem_id = '$idEvento' AND publicado = '1'");
        $numPedidos = $pedidos->num_rows;
        if ($numPedidos == 0) {
            array_push($erros, "Não há pedido inserido neste evento");
        }
    }
}