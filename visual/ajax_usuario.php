<?php
include "../funcoes/funcoesConecta.php";

$con = bancoMysqli();
//mysqli_set_charset($con,"utf8");

$usuarios = [];

$sql = "SELECT nome_completo
		FROM usuarios
		WHERE publicado = 1
		ORDER BY nome_completo";
$res = mysqli_query($con,$sql);

while ( $row = mysqli_fetch_array( $res ) ) {
    $usuarios[] = [
        'nome_completo'	=> $row['nome_completo'],
    ];
}

echo( json_encode( $usuarios ) );