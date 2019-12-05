<?php
$con = bancoMysqli();

$idEvento = $_SESSION['idEvento'];
$evento = recuperaDados('eventos', 'id', $idEvento);
$tipoEvento = $evento['tipo_evento_id'];

$sqlPedidos = "SELECT * FROM pedidos WHERE origem_tipo_id = '$tipoEvento' AND origem_id = '$idEvento' AND publicado = 1";
$pedidos = mysqli_query($con, $sqlPedidos);
$pedido = mysqli_fetch_array($pedidos);
$numPedidos = mysqli_num_rows($pedidos);

$errosArqs = [];
$erros = [];

// CASO SEJA EVENTO ENTRA AQUI NESSA PARADA
if ($evento['tipo_evento_id'] == 1 && $pedidos != NULL) {
    $tipoPessoa = $pedido['pessoa_tipo_id'];

    $sqlAtracaos = "SELECT * FROM atracoes WHERE evento_id = '$idEvento' AND publicado = 1";
    $atracoes = mysqli_query($con, $sqlAtracaos);
    $numAtracoes = mysqli_num_rows($atracoes);

    // VERIFICA SE TEM ATRACOES CADASTRADAS
    if ($numAtracoes > 0) {
        while ($atracao = mysqli_fetch_array($atracoes)) {
            if (($atracao['produtor_id'] == "") || ($atracao['produtor_id'] == NULL))
                array_push($erros, "Produtor não cadastrado na atração <b> " . $atracao['nome_atracao'] . "</b>");

            // VERIFICA SE TEM OS ARQUIVOS DE PESSOA FISICA ENVIADOS
            if ($tipoPessoa == 1) {
                $idPessoa = $pedido['pessoa_fisica_id'];
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
                // VERIFICA SE TEM OS ARQUIVOS DE PESSOA JURIDICA ENVIADOS
                $idPessoa = $pedido['pessoa_juridica_id'];
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

                $idPedidoLider = $pedido['id'];
                $idAtracao = $atracao['id'];
                $sqlLider = "SELECT pessoa_fisica_id FROM lideres WHERE pedido_id = '$idPedidoLider' AND atracao_id = '$idAtracao'";
                $queryLider = mysqli_query($con, $sqlLider);

                if (mysqli_num_rows($queryLider) == 0)
                    array_push($erros, "Líder não cadastrado na atração: <b>" . $atracao['nome_atracao'] . '</b>');

            }

            // VERIFICA O TIPO DE ACAO E VE SE TEM ESPECIFICIDADE
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

            // CASO POSSUA ESPECIFICIDADE REALMENTE CONFERIR SE FOI CADASTRADA ALGUMA
            if ($possui) {
                $sqlEspecificidade = "SELECT * FROM  $tabela WHERE atracao_id = '$idAtracao'";
                $especificidades = mysqli_query($con, $sqlEspecificidade);
                $numEspecificidade = mysqli_num_rows($especificidades);
                if ($numEspecificidade == 0)
                    array_push($erros, "Não há especificidade cadastrada para a atração <b> " . $atracao['nome_atracao'] . " </b>");
            }

            // VERIFICA SE TEM OCORRENCIAS CADASTRADAS
            $sqlOcorrencia = "SELECT * FROM ocorrencias WHERE tipo_ocorrencia_id = $tipoEvento AND atracao_id = '$idAtracao' AND publicado = '1'";
            $ocorrencias = mysqli_query($con, $sqlOcorrencia);
            $numOcorrencias = mysqli_num_rows($ocorrencias);
            if ($numOcorrencias == 0) {
                array_push($erros, "Não há ocorrência cadastrada para a atração <b>" . $atracao['nome_atracao'] . "</b>");
            } else {
                while ($ocorrencia = mysqli_fetch_array($ocorrencias)) {
                    if ($evento['contratacao'] == 1) {
                        // VERIFICA SE ESTA DENTRO DO PRAZO
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

        //PARTE DOS CONTRATOS SE FOR EVENTO
        if ($evento['contratacao'] == 1) {
            if ($numPedidos == 0) {
                array_push($erros, "Não há pedido inserido neste evento");
            } else {
                // VE SE É PESSOA JURIDICA PARA VER SE TEM REPRESENTANTE LEGAL CADASTRADO
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

                // VERIFICA SE OS ARQUIVOS DE PEDIDO FORAM ENVIADOS
                $idPedido = $pedido['id'];
                $sqlArqs = "SELECT ld.id, ld.documento, a.arquivo
                            FROM lista_documentos ld
                            LEFT JOIN (SELECT * FROM arquivos 
                                       WHERE publicado = 1 AND origem_id = '$idPedido' AND publicado = 1) a ON ld.id = a.lista_documento_id
                            WHERE ld.tipo_documento_id = 3 AND ld.publicado = 1";

                $queryArqs = mysqli_query($con, $sqlArqs);
                while ($arquivo = mysqli_fetch_array($queryArqs)) {
                    if ($arquivo['arquivo'] == NULL)
                        array_push($errosArqs, $arquivo['documento'] . " não enviado");
                }
            }
        }
    } else {
        array_push($erros, "Não possui atrações cadastradas!");
    }

} else if ($evento['tipo_evento_id'] == 2 && $pedidos != NULL) {
    // FALTA FAZER O FILTRO DE FILME AGORA (FHODEU)


} else {
    if ($evento['contratacao'] == 1) {
        array_push($errosArqs, "Sem pedido você não poderá enviar seu evento!");
    }
}

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