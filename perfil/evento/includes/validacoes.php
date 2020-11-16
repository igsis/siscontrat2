<?php
$con = bancoMysqli();

$idEvento = $_SESSION['idEvento'];
$evento = recuperaDados('eventos', 'id', $idEvento);
$tipoEvento = $evento['tipo_evento_id'];

$sqlPedidos = "SELECT * FROM pedidos WHERE origem_tipo_id = 1 AND origem_id = '$idEvento' AND publicado = 1";
$pedidos = mysqli_query($con, $sqlPedidos);
$pedido = mysqli_fetch_array($pedidos);
$tipoPessoa = $pedido['pessoa_tipo_id'] ?? null;
$numPedidos = mysqli_num_rows($pedidos);

$errosArqs = [];
$erros = [];
$valorTotalAtracoes = 0;

$musica = false;
$oficina = false;
$artesCenicas = false;
$edital = false;

// CASO SEJA EVENTO ENTRA AQUI NESSA PARADA
if ($evento['tipo_evento_id'] == 1 && $pedidos != NULL) {
    $sqlAtracaos = "SELECT * FROM atracoes WHERE evento_id = '$idEvento' AND publicado = 1";
    $atracoes = mysqli_query($con, $sqlAtracaos);
    $numAtracoes = mysqli_num_rows($atracoes);

    // VERIFICA SE TEM ATRACOES CADASTRADAS
    if ($numAtracoes > 0) {
        $foraPrazo = false;
        while ($atracao = mysqli_fetch_array($atracoes)) {
            $valorTotalAtracoes += $atracao['valor_individual'];
            if (($atracao['produtor_id'] == "") || ($atracao['produtor_id'] == NULL)) {
                array_push($erros, "Produtor não cadastrado na atração <b> " . $atracao['nome_atracao'] . "</b>");
            }


            if ($tipoPessoa == 2) {
                $idPedidoLider = $pedido['id'];
                $idAtracao = $atracao['id'];
                $sqlLider = "SELECT pessoa_fisica_id FROM lideres WHERE pedido_id = '$idPedidoLider' AND atracao_id = '$idAtracao'";
                $queryLider = mysqli_query($con, $sqlLider);

                if (mysqli_num_rows($queryLider) == 0) {
                    array_push($erros, "Líder não cadastrado na atração: <b>" . $atracao['nome_atracao'] . '</b>');
                } else if (mysqli_num_rows($queryLider) > 0) {
                    while ($arrayLider = mysqli_fetch_array($queryLider)) {
                        $consultaDocLider = $con->query("SELECT ld.id, ld.documento, a.arquivo
                                                                FROM lista_documentos ld
                                                                LEFT JOIN (SELECT arq.arquivo, arq.lista_documento_id, list.sigla FROM lista_documentos as list
				                                                           INNER JOIN arquivos as arq ON arq.lista_documento_id = list.id
				                                                           WHERE arq.origem_id = " . $arrayLider['pessoa_fisica_id'] . " AND list.tipo_documento_id = 1
				                                                AND arq.publicado = '1' ORDER BY arq.id) a ON ld.id = a.lista_documento_id
                                                                WHERE ld.tipo_documento_id = 1 AND ld.publicado = 1
                                                                AND ld.sigla IN ('rg','cpf', 'drt', 'currlider')");
                        if ($consultaDocLider->num_rows > 0) {
                            while ($arrayDoc = mysqli_fetch_array($consultaDocLider)) {
                                if ($arrayDoc['arquivo'] == NULL) {
                                    $nomeLider = $con->query("SELECT nome FROM pessoa_fisicas WHERE id = " . $arrayLider['pessoa_fisica_id'])->fetch_array()['nome'];
                                    array_push($errosArqs, $arrayDoc['documento'] . " do Lider ''<strong>$nomeLider</strong>''" . " não enviado");
                                }
                            }
                        } else {
                            $nomeLider = $con->query("SELECT nome FROM pessoa_fisicas WHERE id = " . $arrayLider['pessoa_fisica_id'])->fetch_array()['nome'];
                            array_push($errosArqs, "Todos os documentos do lider ''$nomeLider'' não enviado");
                        }
                    }
                }

            }

            // VERIFICA O TIPO DE ACAO E VE SE TEM ESPECIFICIDADE
            $idAtracao = $atracao['id'];
            $sqlAcao = "SELECT acao_id FROM acao_atracao WHERE atracao_id = '{$atracao['id']}'";
            $acoes = $con->query($sqlAcao)->fetch_all(MYSQLI_ASSOC);
            $possui = true;

            foreach ($acoes as $acao) {
                $idAcao = $acao['acao_id'];
                switch ($idAcao) {
                    case 11: // teatro
                        $tabela = 'teatro';
                        $artesCenicas = true;
                        break;
                    case 2:
                        $artesCenicas = true;
                        $possui = false;
                        break;
                    case 3:
                        $artesCenicas = true;
                        $possui = false;
                    case 7: // música
                        $tabela = 'musica';
                        $musica = true;
                        break;
                    case 5: // exposição (feira)
                        $tabela = 'exposicoes';
                        break;
                    case 8: // oficina
                        $tabela = 'oficinas';
                        $oficina = true;
                        break;
                    default:
                        $possui = false;
                }
            }

            // CASO POSSUA ESPECIFICIDADE REALMENTE CONFERIR SE FOI CADASTRADA ALGUMA
            if ($possui) {
                $sqlEspecificidade = "SELECT * FROM  $tabela WHERE atracao_id = '$idAtracao'";
                $especificidades = mysqli_query($con, $sqlEspecificidade);
                $numEspecificidade = mysqli_num_rows($especificidades);
                if ($numEspecificidade == 0) {
                    array_push($erros, "Não há especificidade cadastrada para a atração <b> " . $atracao['nome_atracao'] . " </b>");
                }
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

                        if ($diff->days < 30 || $hoje > $dataInicio) {
                            $foraPrazo = true;
                            break;
                        }
                    }
                }
            }

            if ($numOcorrencias != 0) {
                foreach ($ocorrencias as $ocorrencia) {
                    $hoje = new DateTime(date("Y-m-d"));
                    if ($foraPrazo) {
                        $mensagem = "Hoje é dia " . $hoje->format('d/m/Y') . ". O seu evento se inicia em " . exibirDataBr($ocorrencia['data_inicio']) . ".<br>
                                        O prazo para contratos é de 30 dias.<br>";
                        $prazo = "Você está <b class='text-red'>fora</b> do prazo de contratos.";
                        $fora = 1;
                    } else {
                        $mensagem = "Hoje é dia " . $hoje->format('d/m/Y') . ". O seu evento se inicia em " . exibirDataBr($ocorrencia['data_inicio']) . ".<br>
                                        O prazo para contratos é de 30 dias.<br>";
                        $prazo = "Você está <b class='text-green'>dentro</b> do prazo de contratos.";
                        $fora = 0;
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

                // VERIFICA O VALOR POR EQUIPAMENTO
                $sql = "SELECT id FROM ocorrencias
                            WHERE tipo_ocorrencia_id = '1'
                            AND origem_ocorrencia_id = '{$evento['id']}'
                            AND publicado = '1'";
                $numOcorrencias = $con->query($sql)->num_rows;

                if ($valorTotalAtracoes > 0 && $numOcorrencias > 1) {
                    $totalCadastrado = 0;
                    $sqlValorPorEquipamentos = "SELECT valor FROM valor_equipamentos WHERE pedido_id = '{$pedido['id']}'";
                    $queryValorPorEquipamento = $con->query($sqlValorPorEquipamentos);
                    if ($queryValorPorEquipamento->num_rows == 0) {
                        array_push($erros, "Não há valores por equipamento cadastrados no pedido");
                    } else {
                        $valoresPorEquipamento = $queryValorPorEquipamento->fetch_all(MYSQLI_ASSOC);
                        foreach ($valoresPorEquipamento as $valores) {
                            $totalCadastrado += $valores['valor'];
                        }
                        if ($totalCadastrado != $valorTotalAtracoes) {
                            array_push($erros, "Valor por Equipamento diferente do valor total cadastrado");
                        }
                    }
                }

                // VERIFICA SE OS ARQUIVOS DE PEDIDO FORAM ENVIADOS
                if ($musica) {
                    $whereAdicional[] = "musica = '1'";
                }
                if ($oficina) {
                    $whereAdicional[] = "oficina = '1'";
                }
                if ($artesCenicas) {
                    $whereAdicional[] = "teatro = '1'";
                }
                if ($edital) {
                    $whereAdicional[] = "edital = '1'";
                }

                if ($musica || $oficina || $artesCenicas) {
                    $sqlAdicional = "AND (" . implode("OR ", $whereAdicional) . ")";
                } else {
                    $sqlAdicional = "";
                }

                $idPedido = $pedido['id'];
                $sqlArqs = "SELECT ld.id, ld.documento, a.arquivo
                                FROM lista_documentos ld
                                LEFT JOIN (SELECT * FROM arquivos 
                                           WHERE publicado = 1 AND origem_id = '$idPedido' AND publicado = 1) a ON ld.id = a.lista_documento_id
                                WHERE ld.tipo_documento_id = 3 AND ld.publicado = 1 $sqlAdicional";

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
    //o null abaixo foi adicionado para impedir erros do php 7.4
    $tipoPessoa = $pedido['pessoa_tipo_id'] ?? NULL;
    $sqlFilme = "SELECT f.id, f.titulo, f.ano_producao, f.genero, f.sinopse, f.duracao, fe.id as 'idFilmeEvento' FROM filme_eventos fe INNER JOIN eventos e on fe.evento_id = e.id INNER JOIN filmes f ON f.id = fe.filme_id WHERE e.id = $idEvento AND e.publicado = 1 AND f.publicado = 1";
    $filmes = mysqli_query($con, $sqlFilme);
    $numFilmes = mysqli_num_rows($filmes);

    if ($numFilmes == 0) {
        array_push($erros, "Não possui filmes cadastrados");
    } else {
        foreach ($filmes as $filme) {
            $idFilme = $filme['id'];
            $sqlOcorrencia = "SELECT * FROM ocorrencias oco INNER JOIN filme_eventos fe ON fe.evento_id = oco.origem_ocorrencia_id WHERE fe.filme_id = '$idFilme' AND oco.publicado = 1 AND evento_id = $idEvento";
            $ocorrencias = mysqli_query($con, $sqlOcorrencia);
            $numOcorrencias = mysqli_num_rows($ocorrencias);
            if ($numOcorrencias < $numFilmes) {
                array_push($erros, "Não há ocorrência cadastrada para o filme <b>" . $filme['titulo'] . "</b>");
                break;
            } else {
                $foraPrazo = false;
                while ($ocorrencia = mysqli_fetch_array($ocorrencias)) {
                    if ($evento['contratacao'] == 1) {
                        // VERIFICA SE ESTA DENTRO DO PRAZO
                        $hoje = new DateTime(date("Y-m-d"));
                        $dataInicio = new DateTime($ocorrencia['data_inicio']);
                        $diff = $hoje->diff($dataInicio);

                        if ($diff->days < 30 || $hoje > $dataInicio) {
                            $mensagem = "Hoje é dia " . $hoje->format('d/m/Y') . ". O seu evento se inicia em " . $dataInicio->format('d/m/Y') . ".<br>
                                    O prazo para contratos é de 30 dias.<br>";
                            $prazo = "Você está <b class='text-red'>fora</b> do prazo de contratos.";
                            $fora = 1;
                            $foraPrazo = true;
                            break;
                        }
                    }
                }

                if ($numOcorrencias != 0) {
                    $hoje = new DateTime(date("Y-m-d"));
                    if ($ocorrencia != null) {
                        $dataInicio = new DateTime($ocorrencia['data_inicio']);
                        if ($foraPrazo) {
                            $mensagem = "Hoje é dia " . $hoje->format('d/m/Y') . ". O seu evento se inicia em " . $dataInicio->format('d/m/Y') . ".<br>
                                        O prazo para contratos é de 30 dias.<br>";
                            $prazo = "Você está <b class='text-red'>fora</b> do prazo de contratos.";
                            $fora = 1;
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


        //PARTE DOS CONTRATOS SE FOR FILME
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
                                WHERE ld.tipo_documento_id = 3 AND ld.publicado = 1 AND (ld.musica = 1 AND ld.teatro = 1 AND ld.oficina = 1  AND ld.documento NOT LIKE '%Pessoa Jurídica%')";

                $queryArqs = mysqli_query($con, $sqlArqs);

                while ($arquivo = mysqli_fetch_array($queryArqs)) {
                    if ($arquivo['arquivo'] == NULL)
                        array_push($errosArqs, $arquivo['documento'] . " não enviado");
                }
            }
        }
    }

} else {
    if ($evento['contratacao'] == 1) {
        array_push($errosArqs, "Sem pedido você não poderá enviar seu evento!");
    }
}

// VERIFICA SE TEM PEDIDO E É CONTRATACAO CASO O CONTRARIO ELE ADICIONA OS ERROS NA PARTE DE FILME JA QUE ELA NAO FICOU TAAAAAAAAO BEM OTIMIZADA
if ($pedidos != NULL && $evento['contratacao'] == 1 && $numPedidos > 0) {
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
    }
} else {
    // CASO SEJA CONTRATACAO E NAO TENHA PEDIDO ELA ADD ERRO PARA EVITAR O ENVIO DO EVENTO
    if ($evento['contratacao'] == 1) {
        array_push($errosArqs, "Sem pedido você não poderá enviar seu evento!");
    }
}

$sqlteste = "SELECT te.tipo_evento, oco.atracao_id, 
i.nome, l.local, 
e.espaco, oco.data_inicio, 
oco.data_fim, oco.segunda, 
oco.terca, oco.quarta, 
oco.quinta, oco.sexta, 
oco.sabado, oco.domingo, 
oco.horario_inicio, oco.horario_fim, 
ri.retirada_ingresso, oco.valor_ingresso, 
oco.observacao, p.periodo, 
sub.subprefeitura, virada, 
oco.libras, oco.audiodescricao, oe.data_excecao 
FROM ocorrencias as oco
INNER JOIN locais as l on l.id = oco.local_id
INNER JOIN tipo_eventos as te on te.id = oco.tipo_ocorrencia_id
INNER JOIN instituicoes as i on i.id = oco.instituicao_id
LEFT JOIN espacos as e on e.id = oco.espaco_id
INNER JOIN retirada_ingressos as ri on ri.id = oco.retirada_ingresso_id
INNER JOIN periodos as p ON p.id = oco.periodo_id
INNER JOIN subprefeituras as sub ON sub.id = oco.subprefeitura_id
LEFT JOIN ocorrencia_excecoes as oe ON oco.id = oe.atracao_id
WHERE oco.origem_ocorrencia_id = '$idEvento' AND oco.publicado = 1";

$queryteste = mysqli_query($con, $sqlteste);
$teste = mysqli_fetch_all($queryteste);
$num = mysqli_num_rows($queryteste);

$ocoDupl = 0;
for ($i = 0; $i < $num; $i++) {
    for ($x = $i + 1; $x < $num; $x++) {
        $cont = 0;
        for ($y = 0; $y < 24; $y += 4) {
            if (($teste[$i][$y] === $teste[$x][$y])
                && ($teste[$i][$y + 1] === $teste[$x][$y + 1])
                && ($teste[$i][$y + 2] === $teste[$x][$y + 2])
                && ($teste[$i][$y + 3] === $teste[$x][$y + 3])) {
                $cont = $cont + 1;
            }
        }
        if ($cont == 6) {
            $ocoDupl = +1;
            break;
        }
    }
    if ($ocoDupl != 0) {
        break;
    }
}


if ($ocoDupl == 1) {
    array_push($erros, "Há ocorrências duplicadas");
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

?>