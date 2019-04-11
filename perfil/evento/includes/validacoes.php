<?php
$con = bancoMysqli();

$idEvento = $_SESSION['idEvento'];

$evento = recuperaDados('eventos', 'id', $idEvento);

$sqlPedidos = "SELECT * FROM pedidos WHERE origem_tipo_id = 1 AND origem_id = '$idEvento' AND publicado = 1";
$queryPedidos = mysqli_query($con, $sqlPedidos);

$errosArqs = [];

while ($pedidos = mysqli_fetch_array($queryPedidos)) {
    $tipoPessoa = $pedidos['pessoa_tipo_id'];

    if ($pedidos['pessoa_tipo_id'] == 1){
        $idPessoa = $pedidos['pessoa_fisica_id'];
        $pf = recuperaDados("pessoa_fisicas", "id", $idPessoa);

        $sqlArqs = "SELECT * FROM arquivos WHERE lista_documento_id = 2 OR lista_documento_id = 3";
        $queryArqs = mysqli_query($con, $sqlArqs);
        if (mysqli_num_rows($queryArqs) < 2) {
            $arqs = mysqli_fetch_array($queryArqs);
            $idDoc = $arqs['lista_documento_id'];
            if ($idDoc == 2) {
                //  array_push($erros,"Produtor não cadastrado na atração <b>".$atracao['nome_atracao']."</b>");
                array_push($errosArqs, "Copia do CPF nao anexada na pessoa fisica <b>" . $pf['nome'] ."</b>");
            } elseif ($idDoc == 3) {

            }

        } elseif (mysqli_num_rows($queryArqs) == 0) {
            echo "teste";
            array_push($errosArqs, "Copias de documentos nao anexadas na pessoa fisica");
        }


    } else {
        $idPessoa = $pedidos['pessoa_juridica_id'];
        $pj = recuperaDados("pessoa_juridicas", "id", $idPessoa);
        $sqlArqs = "SELECT * FROM arquivos WHERE lista_documento_id = 22";
        $queryArqs = mysqli_query($con, $sqlArqs);
        if (mysqli_num_rows($queryArqs) == 0) {
            array_push($errosArqs, "Copia de CNPJ nao anexada na pessoa juridica <b>" . $pj['razao_social'] . "</b>");
        }
    }
}

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
        $pedido = $pedidos->fetch_assoc();
        if ($numPedidos == 0) {
            array_push($erros, "Não há pedido inserido neste evento");
        } else {
            if ($pedido['pessoa_tipo_id'] == 2) {
                $pj = recuperaDados('pessoa_juridicas', 'id', $pedido['pessoa_juridica_id']);

                if (($pj['representante_legal1_id'] == null) && ($pj['representante_legal2_id'] == null)) {
                    array_push($erros, "Não há Representante Legal cadastrado no proponente <b>".$pj['razao_social']."</b>");
                }
            }
        }
    }
}