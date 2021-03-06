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

function retornaEventosComunicacao($idUser, $tabela = '')
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
                FROM eventos as eve
                LEFT JOIN usuarios u ON eve.usuario_id = u.id
                LEFT JOIN evento_status es on eve.evento_status_id = es.id
                LEFT JOIN local_usuarios ls ON eve.usuario_id = ls.usuario_id
                INNER JOIN locais lo ON lo.id = ls.local_id
                WHERE eve.publicado = 1 AND (eve.evento_status_id = 3 OR eve.evento_status_id = 4) ";

    $sqlSis .= $spp;

    return mysqli_query($con, $sqlSis);
}

function limparArray($array)
{
    $lenght = count($array);
    $array2 = [];
    for ($i = 0; $i < $lenght; $i++) {
        array_push($array2, $array[$i][0]);
    }
    return $array2;
}

function aplicarFiltro($idEvento, $filtro, $tabela)
{
    $con = bancoMysqli();
    if ($filtro != null) {
        $sql = "SELECT comunicacao_status_id FROM {$tabela} WHERE publicado = '1' AND eventos_id = '{$idEvento}'";
        $queryComu = mysqli_query($con, $sql);
        $status = mysqli_fetch_all($queryComu, MYSQLI_NUM);

        $resu = 0;

        if (count($status)) {
            $array = limparArray($status);
            foreach ($filtro as $key => $value) {
                switch ($key) {
                    case 'editado':
                        if ($value) {
                            if (in_array("1", $array)) {
                                $valid = true;
                            } else {
                                $valid = false;
                            }
                        } else {
                            if (in_array("1", $array)) {
                                $valid = false;
                            } else {
                                $valid = true;
                            }
                        }
                        break;
                    case 'revisado':
                        if ($value) {
                            if (!in_array("2", $array)) {
                                $valid = false;
                            } else {
                                $valid = true;
                            }
                        } else {
                            if (in_array("2", $array)) {
                                $valid = false;
                            } else {
                                $valid = true;
                            }
                        }
                        break;
                    case 'site':
                        if ($value) {
                            if (!in_array("3", $array)) {
                                $valid = false;
                            } else {
                                $valid = true;
                            }
                        } else {
                            if (in_array("3", $array)) {
                                $valid = false;
                            } else {
                                $valid = true;
                            }
                        }
                        break;
                    case 'impresso':
                        if ($value) {
                            if (!in_array("4", $array)) {
                                $valid = false;
                            } else {
                                $valid = true;
                            }
                        } else {
                            if (in_array("4", $array)) {
                                $valid = false;
                            } else {
                                $valid = true;
                            }
                        }
                        break;
                    case 'foto':
                        if ($value) {
                            if (!in_array("5", $array)) {
                                $valid = false;
                            } else {
                                $valid = true;
                            }
                        } else {
                            if (in_array("5", $array)) {
                                $valid = false;
                            } else {
                                $valid = true;
                            }
                        }
                        break;
                }

                if ($valid) {
                    $resu++;
                    break;
                }
            }

            if ($resu) {
                return true;
            } else {
                return false;
            }

        } elseif (in_array(0, $filtro)) {
            return true;
        } else {
            return false;
        }
    } elseif ($filtro == null) {
        return true;
    }

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