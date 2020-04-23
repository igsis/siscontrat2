<?php

require_once 'funcoesConecta.php';
require_once 'funcoesGerais.php';
$con = bancoMysqli();

if (isset($_POST)) {
    $id = $_POST['id'];
    $datas = $_POST['datas'];
    $cont = 0;
    $sqlLimpar = "DELETE FROM ocorrencia_excecoes WHERE atracao_id = $id";
    if (mysqli_query($sqlLimpar)) {
        gravarLog($sqlLimpar);
        foreach ($datas as $data){
            $sql = "INSERT INTO ocorrencia_excecoes (atracao_id, data_excessao) VALUES ('$id','$data')";
            if (mysqli_query($sql)){
                $cont++;
            }
        }
        if ($cont == count($datas)){
            
        }
    }
    else{

    }
}