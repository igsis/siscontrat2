<?php

include "../../../funcoes/funcoesConecta.php";
include "../../../funcoes/funcoesGerais.php";

if (isset($_POST['ficha'])){
    print_r(EditaFicha($_POST['ficha'],$_POST['id']));
} elseif (isset($_POST['release'])){
    print_r(EditaRelease($_POST['release'],$_POST['id']));
}


function EditaFicha($ficha,$id){
    $con = bancoMysqli();
    $ficha = addslashes(trim($ficha));
    $query = "UPDATE atracoes SET ficha_tecnica = '$ficha' WHERE id = '$id'";
    if ($alt = mysqli_query($con,$query)){
        return json_encode($alt);
    }
    return false;
}

function EditaRelease($release,$id){
    $con = bancoMysqli();
    $release = addslashes(trim($release));
    $query = "UPDATE atracoes SET release_comunicacao = '$release' WHERE id = '$id'";
    if ($alt = mysqli_query($con,$query)){
        return json_encode($alt);
    }
    return false;
}

