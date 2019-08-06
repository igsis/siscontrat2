<?php
require_once 'funcoesConecta.php';
// require "../funcoes/";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json');

if (isset($_POST['cep']) && $_POST['cep'] != null) {

    #Recebe o CEP postado
    $cepEnviado = $_POST['cep'];

    $enderecos = [];

    #Conecta banco de dados
    $con = bancoMysqli();
    $sql = mysqli_query($con, "SELECT * FROM locais WHERE cep = '{$cepEnviado}'") or print mysql_error();

    #Se o retorno for maior do que zero, diz que já existe um.
    if (mysqli_num_rows($sql) > 0) {
        while ($row = mysqli_fetch_array($sql)) {
            $enderecos[] = [
                'local' => $row['local'],
                'logradouro' => $row['logradouro'],
                'cep' => $row['cep'],
                'bairro' => $row['bairro'],
            ];
        }

        $cep = json_encode($enderecos);

    } else
        $cep = json_encode(array('email' => 'Email ok', 'ok' => 0));

    print_r($cep);
}

