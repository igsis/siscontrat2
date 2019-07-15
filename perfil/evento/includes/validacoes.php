<?php
$con = bancoMysqli();
$conn = bancoPDO();

$idEvento = $_SESSION['idEvento'];

$evento = recuperaDados('eventos', 'id', $idEvento);

$sqlPedidos = "SELECT * FROM pedidos WHERE origem_tipo_id = 1 AND origem_id = '$idEvento' AND publicado = 1";
$pedidos = $con->query($sqlPedidos)->fetch_assoc();

$atracoes = $con->query("SELECT * FROM atracoes WHERE evento_id = '$idEvento' AND publicado = '1'");

$errosArqs = [];

if ($pedidos != null) {
    while ($atracao = mysqli_fetch_array($atracoes)) {
        $tipoPessoa = $pedidos['pessoa_tipo_id'];

        if ($pedidos['pessoa_tipo_id'] == 1) {
            $idPessoa = $pedidos['pessoa_fisica_id'];
            $pf = recuperaDados("pessoa_fisicas", "id", $idPessoa);

            $sqlArqs = "SELECT * FROM arquivos WHERE lista_documento_id = 2 OR lista_documento_id = 3";
            $queryArqs = mysqli_query($con, $sqlArqs);
            if (mysqli_num_rows($queryArqs) < 2 AND mysqli_num_rows($queryArqs) != 0) {
                $arqs = mysqli_fetch_array($queryArqs);
                $idDoc = $arqs['lista_documento_id'];
                if ($idDoc == 2) {
                    //  array_push($erros,"Produtor não cadastrado na atração <b>".$atracao['nome_atracao']."</b>");
                    array_push($errosArqs, "Cópia do CPF não anexada na pessoa física <b>" . $pf['nome'] . "</b>");
                } elseif ($idDoc == 3) {
                    array_push($errosArqs, "Cópia do RG não anexada na pessoa física <b>" . $pf['nome'] . "</b>");
                }

            } elseif (mysqli_num_rows($queryArqs) == 0) {
                array_push($errosArqs, "Cópias de RG e CPF não anexadas na pessoa fisica ");
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
} else {
    array_push($errosArqs, "Sem pedido você não poderá enviar seu evento!");
}

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
function in_array_key($needle, $haystack)
{
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
    if ($numAtracoes == 0) {
        array_push($erros, "Não possui atrações cadastradas");
    } else {
        foreach ($atracoes as $atracao) {
            if (($atracao['produtor_id'] == "") || ($atracao['produtor_id'] == null)) {
                array_push($erros, "Produtor não cadastrado na atração <b>" . $atracao['nome_atracao'] . "</b>");
            }

            $idAtracao = $atracao['id'];
            $acoes = recuperaDados('acao_atracao', 'atracao_id', $idAtracao);
            $idAcao = $acoes['acao_id'];
            $possui = true;
            switch ($idAcao) {
                case 11 : // teatro
                    $tabela = 'teatro';
                    break;
                case 7 : // música
                    $tabela = 'musica';
                    break;
                case 5 : // exposição (feira)
                    $tabela = 'exposicoes';
                    break;
                case 8 : // oficina
                    $tabela = 'oficinas';
                    break;
                default :
                    $possui = false;
            }

            if ($possui) {
                $numEspecificidades = $con->query("SELECT * FROM $tabela WHERE atracao_id = '$idAtracao'")->num_rows;
                if($numEspecificidades == 0){
                    array_push($erros, "Não há especificidade cadastrada para a atração <b>" . $atracao['nome_atracao'] . "</b>");
                }
            }

            $ocorrencias = $con->query("SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = '$idAtracao' AND publicado = '1'");
            $ocorrenciasAssocs = $ocorrencias->fetch_assoc();
            $numOcorrencias = $ocorrencias->num_rows;
            if ($numOcorrencias == 0) {
                array_push($erros, "Não há ocorrência cadastrada para a atração <b>" . $atracao['nome_atracao'] . "</b>");
            } else {
                $hoje = new DateTime(date("Y-m-d"));
                $dataInicio = new DateTime($ocorrenciasAssocs['data_inicio']);
                $diff = $hoje->diff($dataInicio);

                if ($diff->d < 30) {
                    $mensagem = "Hoje é dia " . $hoje->format('d/m/Y') . ". O seu evento se inicia em " . $dataInicio->format('d/m/Y') . ".<br>
                    O prazo para contratos é de 30 dias.<br>";
                    $prazo = "Você está <b class='text-red'>fora</b> do prazo de contratos.";
                    $fora = 1;
                } else {
                    $mensagem = "Hoje é dia " . $hoje->format('d/m/Y') . ". O seu evento se inicia em " . $dataInicio->format('d/m/Y') . ".<br>
                    O prazo para contratos é de 30 dias.<br>";
                    $prazo = "Você está <b class='text-green'>dentro</b> do prazo de contratos.";
                }

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
                    array_push($erros, "Não há Representante Legal cadastrado no proponente <b>" . $pj['razao_social'] . "</b>");
                }
            }
        }
    }
}