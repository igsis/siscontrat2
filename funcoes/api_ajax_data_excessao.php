<?php

require_once 'funcoesConecta.php';
require_once 'funcoesGerais.php';
$con = bancoMysqli();
$conn = bancoPDO();

if (isset($_POST['datas'])) {
    $id = $_POST['id'];
    $datas = $_POST['datas'];
    $cont = 0;
    if (count($datas) != 0){
        $sqlIds = "SELECT id FROM ocorrencia_excecoes WHERE atracao_id = '$id'";
        if ($arrayId = mysqli_query($con, $sqlIds)) {
            $idOcorrencia = mysqli_fetch_all($arrayId,MYSQLI_ASSOC);
            $sql = "INSERT INTO ocorrencia_excecoes (atracao_id, data_excecao) VALUES ";
            foreach ($datas as $data){
                if ($cont != 0){
                    $sql.=", ('$id','$data')";
                }else{
                    $sql .="('$id','$data')";
                    $cont++;
                }
            }
            if (mysqli_query($con,$sql)){
                foreach ($idOcorrencia as $ocorrencia){
                    $sqlDelete = "DELETE FROM ocorrencia_excecoes WHERE id = '{$ocorrencia['id']}'";
                    if (mysqli_query($con, $sqlDelete))
                        gravarLog($sqlDelete);
                }
                echo http_response_code(200);
            }else{
                echo http_response_code(403);
            }
        }
        else{
            echo http_response_code(500);
        }
    }else{
        $sqlDelete = "DELETE FROM ocorrencia_excecoes WHERE atrcao_id = '{$id}'";
        if (mysqli_query($con, $sqlDelete))
            gravarLog($sqlDelete);

        echo "Datas de excessão apagadas com sucesso";
    }
}

if (isset($_POST['idOcorrencia'])){
    $id = $_POST['idOcorrencia'];
    $datas = $conn->query("SELECT `data_excecao` FROM `ocorrencia_excecoes` WHERE atracao_id= {$id}")->fetchAll();

    echo json_encode($datas);
}