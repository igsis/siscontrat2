<?php
$con = bancoMysqli();
$conn = bancoPDO();

$idEvento = $_SESSION['idEvento'];

$evento = recuperaDados('eventos', 'id', $idEvento);

$tipoEvento = $evento['tipo_evento_id'];

$sqlPedidos = "SELECT * FROM pedidos WHERE origem_tipo_id = $tipoEvento AND origem_id = '$idEvento' AND publicado = 1";
$pedidos = $con->query($sqlPedidos)->fetch_assoc();

$atracoes = $con->query("SELECT * FROM atracoes WHERE evento_id = '$idEvento' AND publicado = '1'");

$errosArqs = [];
$erros = [];
if ($pedidos != null) {
    while ($atracao = mysqli_fetch_array($atracoes)) {
        $tipoPessoa = $pedidos['pessoa_tipo_id'];

        if ($tipoPessoa == 1) {
            $idPessoa = $pedidos['pessoa_fisica_id'];
            $pf = recuperaDados("pessoa_fisicas", "id", $idPessoa);

            $sqlArqs = "SELECT ld.id, ld.documento, a.arquivo 
                            FROM lista_documentos ld
                            LEFT JOIN (SELECT * FROM arquivos 
                                        WHERE publicado = 1 AND origem_id = '$idPessoa') a ON ld.id = a.lista_documento_id
                            WHERE ld.tipo_documento_id = '$tipoPessoa' AND ld.publicado = 1";
            $queryArqs = mysqli_query($con, $sqlArqs);

            while ($arquivo = mysqli_fetch_array($queryArqs)) {
                if ($arquivo['arquivo'] == NULL) {
                    array_push($errosArqs, $arquivo['documento'] . " não enviado");
                }
            }

        } else {
            $idPessoa = $pedidos['pessoa_juridica_id'];
            $pj = recuperaDados("pessoa_juridicas", "id", $idPessoa);
            $sqlArqs = "SELECT ld.id, ld.documento, a.arquivo 
                            FROM lista_documentos ld
                            LEFT JOIN (SELECT * FROM arquivos 
                                        WHERE publicado = 1 AND origem_id = '$idPessoa') a ON ld.id = a.lista_documento_id
                            WHERE ld.tipo_documento_id = '$tipoPessoa' AND ld.publicado = 1";
            $queryArqs = mysqli_query($con, $sqlArqs);
            while ($arquivo = mysqli_fetch_array($queryArqs)) {

                if ($pj['representante_legal1_id'] == NULL && ($arquivo['id'] == 23 || $arquivo['id'] == 24))
                    continue;

                if ($pj['representante_legal2_id'] == null && ($arquivo['id'] == 85 || $arquivo['id'] == 86))
                    continue;

                if ($arquivo['arquivo'] == NULL)
                    array_push($errosArqs, $arquivo['documento'] . " não enviado");
            }

            $idPedidoLider = $pedidos['id'];
            $idAtracao = $atracao['id'];
            $sqlLider = "SELECT pessoa_fisica_id FROM lideres WHERE pedido_id = '$idPedidoLider' AND atracao_id = '$idAtracao'";
            $queryLider = mysqli_query($con, $sqlLider);

            if (mysqli_num_rows($queryLider) == 0)
                array_push($erros, "Líder não cadastrado na atração: <b>" . $atracao['nome_atracao'] . '</b>');

        }
    }
} else {
    if ($evento['contratacao'] == 1) {
        array_push($errosArqs, "Sem pedido você não poderá enviar seu evento!");
    }
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
                if ($numEspecificidades == 0) {
                    array_push($erros, "Não há especificidade cadastrada para a atração <b>" . $atracao['nome_atracao'] . "</b>");
                }
            }

            $sqlOcorrencia = "SELECT * FROM ocorrencias WHERE tipo_ocorrencia_id = $tipoEvento AND atracao_id = '$idAtracao' AND publicado = '1'";
            $ocorrencias = mysqli_query($con, $sqlOcorrencia);
            $numOcorrencias = mysqli_num_rows($ocorrencias);
            if ($numOcorrencias == 0) {
                array_push($erros, "Não há ocorrência cadastrada para a atração <b>" . $atracao['nome_atracao'] . "</b>");
            } else {
                while ($ocorrencia = mysqli_fetch_array($ocorrencias)) {
                    if ($evento['contratacao'] == 1) {
                        $hoje = new DateTime(date("Y-m-d"));
                        $dataInicio = new DateTime($ocorrencia['data_inicio']);
                        $diff = $hoje->diff($dataInicio);

                        if ($diff->days < 30) {
                            $mensagem = "Hoje é dia " . $hoje->format('d/m/Y') . ". O seu evento se inicia em " . $dataInicio->format('d/m/Y') . ".<br>
                                O prazo para contratos é de 30 dias.<br>";
                            $prazo = "Você está <b class='text-red'>fora</b> do prazo de contratos.";
                            $fora = 1;
                            break;
                        } else {
                            $mensagem = "Hoje é dia " . $hoje->format('d/m/Y') . ". O seu evento se inicia em " . $dataInicio->format('d/m/Y') . ".<br>
                                O prazo para contratos é de 30 dias.<br>";
                            $prazo = "Você está <b class='text-green'>dentro</b> do prazo de contratos.";
                            $fora = 0;
                        }
                    }
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

            if ($pedido['verba_id'] == null)
                array_push($erros, "Não há verba cadastrada no pedido");

            if ($pedido['numero_parcelas'] == null)
                array_push($erros, "Não há número de parcelas cadastrada no pedido");

            if ($pedido['justificativa'] == null)
                array_push($erros, "Não há justificativa cadastrada no pedido");

            if ($pedido['forma_pagamento'] == null)
                array_push($erros, "Não há forma de pagamento cadastrada no pedido");

            $idPedido = $pedido['id'];

            $sqlArqs = "SELECT ld.id, ld.documento, a.arquivo FROM lista_documentos ld
                        LEFT JOIN (SELECT * FROM arquivos WHERE publicado = 1 AND origem_id = '$idPedido') a ON a.lista_documento_id = ld.id
                        WHERE ld.publicado = 1 AND ld.tipo_documento_id = 3";

            $queryArqs = mysqli_query($con, $sqlArqs);

            while ($arquivo = mysqli_fetch_array($queryArqs)) {
                if ($arquivo['arquivo'] == NULL)
                    array_push($errosArqs, $arquivo['documento'] . " não enviado");
            }
        }
    }
}

if ($evento['tipo_evento_id'] == 2) {
    $filme = "SELECT f.id, f.titulo, f.ano_producao, f.genero, f.sinopse, f.duracao FROM filme_eventos fe INNER JOIN eventos e on fe.evento_id = e.id INNER JOIN filmes f ON f.id = fe.filme_id WHERE e.id = $idEvento AND e.publicado = 1 AND f.publicado = 1";
    $filmes = mysqli_query($con, $filme);
    $numFilmes = mysqli_num_rows($filmes);

    if ($numFilmes == 0) {
        array_push($erros, "Não possui filmes cadastrados");
    } else {
        foreach ($filmes as $filme) {

            $idAtracao = $filme['id'];
            $ocorrencias = $con->query("SELECT * FROM ocorrencias WHERE atracao_id = '$idAtracao'");
            $ocorrenciasAssocs = $ocorrencias->fetch_assoc();
            $numOcorrencias = $ocorrencias->num_rows;

            if ($numOcorrencias == 0) {
                array_push($erros, "Não há ocorrência cadastrada para o filme <b>" . $filme['titulo'] . "</b>");
            }
        }
    }
}