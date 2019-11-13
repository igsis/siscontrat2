<?php
$idCapac = $_POST['idCapac'];

$sql = "INSERT INTO siscontrat.eventos(nome_evento, espaco_publico, fomento, sinopse) 
        SELECT  FROM capac_new.eventos WHERE id = '$idCapac'";

$sql = "SELECT * FROM capac_new.eventos WHERE id = '$idCapac'";
$query = mysqli_query($conn, $sql);

while($eventoCapac = mysqli_fetch_array($query)){
    $nomeEvento = $eventoCapac['nome_evento'];
    $espacoPublico = $evento['espaco_publico'];
    $fomento = $evento['fomento'];
    $sinopse = $evento['sinopse'];
    $dataCadastro = $evento['data_cadastro'];

    if($fomento == 1){
        $sqlFomento = "SELECT f.id, f.fomento FROM capac_new.evento_fomento ef 
                       INNER JOIN capac_new.fomentos f ON f.id = ef.fomento_id 
                       WHERE ef.evento_id = '$idCapac'";

        $queryFomento = mysqli_query($conn, $sqlFomento);
        while ($fomentoBalde = mysqli_fetch_array($queryFomento)){
            $idFomento = $fomentoBalde['id'];
            $nomeFomento = $fomentoBalde['fomento'];
        }
    }else{
        $idFomento = null;
        $nomeFomento = null;
    }
}