<?php


function geraLegendas($idEvento, $tabela, $tabelaComunicacao)
{

    $con = bancoMysqli();

    $sqlStatus = "SELECT co.id as id
                                                            FROM " . $tabelaComunicacao . " AS c
                                                            INNER JOIN " . $tabela . " AS e ON c.eventos_id = e.id
                                                            INNER JOIN comunicacao_status AS co ON c.comunicacao_status_id = co.id
                                                            WHERE c.publicado = 1 AND e.id = '$idEvento' ";
    $queryS = mysqli_query($con, $sqlStatus);
    $status = mysqli_fetch_all($queryS, MYSQLI_ASSOC);
    if ($status != null) {
        foreach ($status as $st) {
            switch ($st['id']) {

                case 1:
                    echo "<div class=\"quadr bg-aqua\" data-toggle=\"popover\" data-trigger=\"hover\"
                                                         data-content=\"Editado\"></div>";
                    break;

                case 2:
                    echo "<div class=\"quadr bg-fuchsia\" data-toggle=\"popover\" data-trigger=\"hover\"
                                                         data-content=\"Revisado\"></div>";
                    break;
                case 3:
                    echo "<div class=\"quadr bg-green\" data-toggle=\"popover\" data-trigger=\"hover\"
                                                         data-content=\"Site\"></div>";
                    break;
                case 4:
                    echo "<div class=\"quadr bg-yellow\" data-toggle=\"popover\" data-trigger=\"hover\"
                                                         data-content=\"Impresso\"></div>";
                    break;
                case 5:
                    echo "<div class=\"quadr bg-red\" data-toggle=\"popover\" data-trigger=\"hover\"
                                                         data-content=\"Foto\"></div>";
                    break;
                default:
                    echo "";
                    break;
            }

        }

    }
}


function gravaStatus($status, $tabela, $idEvento)
{
    $Valida = true;
    $con = bancoMysqli();
    for ($i = 0; $i < 5; $i++) {
        try {
            $verifica = "SELECT * FROM " . $tabela . "  WHERE eventos_id = '$idEvento' AND comunicacao_status_id = '" . ($i + 1) . "'";
            $resultado = mysqli_query($con, $verifica);
            $resultado = mysqli_fetch_array($resultado);
            if (empty($resultado) && $status[$i] != 0) {
                $insert = "INSERT INTO " . $tabela . "(eventos_id, comunicacao_status_id,publicado) VALUES ('$idEvento','$status[$i]',1)";
                mysqli_query($con, $insert);
            } elseif (!empty($resultado)) {
                if ($status[$i] == NULL) {
                    $update = "UPDATE " . $tabela . " SET publicado = 0 WHERE id='" . $resultado[0] . "'";
                    mysqli_query($con, $update);
                } else {
                    $update = "UPDATE " . $tabela . " SET publicado = 1 WHERE id='$resultado[0]";
                    mysqli_query($con, $update);
                }
            }
        } catch (Exception $e) {
            $Valida = false;
        }
    }

    return $Valida;
}

function retornaEventosComunicacao($idUser, $tabela, $confirmados = null, $pendentes = null)
{
    $con = bancoMysqli();

    $spp = '';

    if ($tabela[0] != 'agendoes') {
        $spp = "AND (suplente_id = '$idUser' OR fiscal_id = '$idUser' OR usuario_id = '$idUser') ";
    }
    $sqlSis = "SELECT       eve.id AS idEvento, 
                        eve.nome_evento AS nome_evento, 
                        es.status AS status, 
                        u.nome_completo AS nome_usuario
        FROM {$tabela[0]} as eve
        LEFT JOIN usuarios u ON eve.usuario_id = u.id
        INNER JOIN evento_status es on eve.evento_status_id = es.id
        WHERE eve.publicado = 1 AND evento_status_id between 3 AND 4 ";

    $sqlSis .= $spp;

    if ($confirmados != null || $pendentes != null) {
        $sqlSis .= filtro($tabela[1], $confirmados, $pendentes);
    }

    return mysqli_query($con, $sqlSis);
}

function montaIN($dados, $tipo = 0)
{
    $str = '';
    $tamanho = sizeof($dados);
    if ($tamanho) {
        if ($tipo) {
            $str = 'AND comunicacao_status_id IN (';
        } else {
            $str = 'AND comunicacao_status_id NOT IN (';
        }

        for ($i = 0; $i < $tamanho; $i++) {
            $str .= "{$dados[$i]}";
            if ($i + 1 < $tamanho) {
                $str .= ',';
            } else {
                $str .= ')';
            }
        }
    }
    return $str;
}

function filtro($tabela, $confirmado, $pendentes)
{
    $fConfirmado = montaIN($confirmado, 1);
    $fPendente = montaIN($pendentes);

    return "AND eve.id IN (SELECT eventos_id FROM $tabela WHERE publicado = 1 $fConfirmado $fPendente)";

}

