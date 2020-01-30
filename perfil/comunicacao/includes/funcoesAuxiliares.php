<?php



function geraLegendas($idEvento,$tabela,$tabelaComunicacao){

    $con = bancoMysqli();

    $sqlStatus = "SELECT co.id as id
                                                            FROM ". $tabelaComunicacao ." AS c
                                                            INNER JOIN ". $tabela ." AS e ON c.eventos_id = e.id
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


function gravaStatus($status,$tabela,$idEvento){
    $con = bancoMysqli();
    for($i=0;$i<5;$i++){
        $verifica = "SELECT * FROM ".$tabela."  WHERE eventos_id = '$idEvento' AND comunicacao_status_id = '$status[$i]' AND publicado = '1'";
        $resultado = mysqli_query($con,$verifica);
        $resultado = mysqli_fetch_array($resultado);
        if (empty($resultado) && $status[$i] != null){
            $insert = "INSERT INTO ".$tabela."(eventos_id, comunicacao_status_id,publicado) VALUES ('$idEvento','$status[$i]',1)";
            mysqli_query($con, $insert);
        }elseif(!empty($resultado)){
            if ($status[$i] == null){
                $update = "UPDATE ".$tabela." SET publicado = 0 WHERE id='$resultado[0]'";
                mysqli_query($con, $update);
            }else{
                $update = "UPDATE ".$tabela." SET publicado = 1 WHERE id='$resultado[0]";
                mysqli_query($con, $update);
            }
        }
    }
}
