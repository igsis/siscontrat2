<?php
$idCapac = $_POST['idCapac'];
$idUser = $_SESSION['usuario_id_s'];

$con = bancoMysqli();
$conn = bancoCapac();

$sql = "INSERT INTO siscontrat.eventos(nome_evento, espaco_publico, fomento, sinopse) 
        SELECT  FROM capac_new.eventos WHERE id = '$idCapac'";

$sql = "SELECT * FROM capac_new.eventos WHERE id = '$idCapac'";
$query = mysqli_query($conn, $sql);

while ($eventoCapac = mysqli_fetch_array($query)) {
    $nomeEvento = $eventoCapac['nome_evento'];
    $espacoPublico = $eventoCapac['espaco_publico'];
    $fomento = $eventoCapac['fomento'];
    $sinopse = $eventoCapac['sinopse'];
    $dataCadastro = $eventoCapac['data_cadastro'];
}

$sql = "INSERT INTO siscontrat.eventos (nome_evento, espaco_publico, fomento, 
                                        sinopse, usuario_id, fiscal_id, suplente_id, 
                                        relacao_juridica_id, projeto_especial_id, evento_status_id, tipo_evento_id) 
        VALUES ('$nomeEvento', '$espacoPublico', '$fomento', '$sinopse', '$idUser', '$idUser', '$idUser', '1', '1', '1', '1')";
if(mysqli_query($con, $sql)){
    $mensagem = 'DEU CERTO';
    gravarLog($sql);
    $idEvento = recuperaUltimo('eventos');
    if ($fomento == 1) {
        $sqlFomento = "SELECT f.id, f.fomento FROM capac_new.evento_fomento ef 
                       INNER JOIN capac_new.fomentos f ON f.id = ef.fomento_id 
                       WHERE ef.evento_id = '$idCapac'";

        $queryFomento = mysqli_query($conn, $sqlFomento);
        while ($fomentoBalde = mysqli_fetch_array($queryFomento)) {
            $idFomento = $fomentoBalde['id'];
            $nomeFomento = $fomentoBalde['fomento'];
        }

        $sql = "INSERT INTO siscontrat.evento_fomento (evento_id, fomento_id) VALUE ('$idEvento', '$idFomento')";
        mysqli_query($con, $sql);
        gravarLog($sql);
    }

    $sql = "SELECT evento_id, publico_id FROM capac_new.evento_publico WHERE evento_id = '$idCapac'";
    $query = mysqli_query($conn, $sql);
    while($publico = mysqli_fetch_array($query)){
        $idPublico = $publico['publico_id'];
        $sql = "INSERT INTO siscontrat.evento_publico (evento_id, publico_id) VALUE ('$idEvento', '$idPublico')";
        mysqli_query($con, $sql);
        gravarLog($sql);
    }
}else{
    $mensagem = 'DEU ERRO';
}
