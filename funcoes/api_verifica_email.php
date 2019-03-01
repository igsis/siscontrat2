<?php
require_once 'funcoesConecta.php';
// require "../funcoes/";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json');

if(isset($_POST['email'])){

    #Recebe o Email Postado
    $emailPostado = $_POST['email'];

    #Conecta banco de dados
    $con = bancoMysqli();
    $sql = mysqli_query($con, "SELECT * FROM usuarios WHERE email = '{$emailPostado}'") or print mysql_error();

    #Se o retorno for maior do que zero, diz que já existe um.
    if(mysqli_num_rows($sql)>0)
        $email =  json_encode(array('email' => 'Email em uso!', 'ok' => 0));
    else
        $email = json_encode(array('email' => 'Email ok', 'ok' => 1));

    print_r($email);
}

