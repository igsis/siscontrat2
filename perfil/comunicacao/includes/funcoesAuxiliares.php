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
    foreach ($status as $st){
        if ($st != null){
            $saveStatus = "INSERT INTO ".$tabela." (eventos_id,comunicacao_status_id,publicado)
                                                    VALUES('$idEvento','$st')";
            if (mysqli_query($saveStatus)){
                return mensagem("success", "Atualizado com sucesso!");
            }else{
                return mensagem("danger", "Erro ao gravar! Tente novamente.");
            }
        }
    }

}
