<?php

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

function retornaEventosComunicacao($idUser, $tabela, $filtro = '', $status = '')
{
    $con = bancoMysqli();

    $spp = '';

    if ($tabela != 'agendoes') {
        $spp = "AND lo.instituicao_id = 
                (SELECT ins.id FROM instituicoes ins 
                        INNER JOIN locais l ON ins.id = l.instituicao_id
                        INNER JOIN local_usuarios lous ON l.id = lous.local_id
                        INNER JOIN usuarios us ON lous.usuario_id = us.id
                    WHERE us.id = '{$idUser}')";
    }


    $sqlSis = "SELECT   eve.id AS idEvento, 
                          eve.nome_evento AS nome_evento, 
                          es.status AS status, 
                          u.nome_completo AS nome_usuario
                FROM {$tabela} as eve
                INNER JOIN usuarios u ON eve.usuario_id = u.id
                INNER JOIN evento_status es on eve.evento_status_id = es.id
                INNER JOIN local_usuarios ls ON eve.usuario_id = ls.usuario_id
                INNER JOIN locais lo ON lo.id = ls.local_id
                WHERE eve.publicado = 1 AND evento_status_id IN  (3,4) ";

    $sqlSis .= $spp;

    return mysqli_query($con, $sqlSis);
}

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

function montarFiltro($status)
{
    switch ($status) {
        case 'editado':
            return '1';
            break;
        case 'revisado':
            return '2';
            break;
        case 'site':
            return '3';
            break;
        case 'impresso':
            return '4';
            break;
        case 'foto':
            return '5';
            break;
    }
}

function verificaPendencia($array, $query)
{

    $con = bancoMysqli();
    foreach ($array as $ar) {
        $sql = $query . " AND comunicacao_status_id = {$ar}";
        $queryE = mysqli_query($con, $sql);
        $comunicacao = mysqli_num_rows($queryE);
        if ($comunicacao > 0) {
            return false;
        }
    }
    return true;
}

function aplicarFiltro($idEvento, $filtro = '')
{
    $con = bancoMysqli();
    $query = "SELECT comunicacao_status_id FROM comunicacoes WHERE publicado = 1  AND eventos_id = {$idEvento}";

    $in = '';
    $not = [];
    if ($filtro) {
        foreach ($filtro as $key => $value) {
            if ($value) {
                if ($in != '') {
                    $in .= ',';
                }
                $in .= montarFiltro($key);
            } else {
                array_push($not, montarFiltro($key));
            }
        }
        if ($in != '' || $not != []) {
            if ($in != '') {
                $in = "AND comunicacao_status_id IN (" . $in . ")";
                $sqlIn = $query . " " . $in;
                $resul = mysqli_query($con, $sqlIn);
                $in = mysqli_num_rows($resul);
            }
            if ($not != []) {
                $not = verificaPendencia($not, $query);
            }

            if ($in == ''){
                $in = 0;
            }
            if ($not == []){
                $not = 0;
            }

            if ($in > 0 && $not) {
                return true;
            } else {
                if ($in > 0 && $not) {
                    return true;
                } else {
                    if ($not && $in != 0) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        } else {
            return false;
        }
    }

    return true;
}