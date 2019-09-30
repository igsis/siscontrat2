<?php
$con = bancoMysqli();

if(isset($_POST['alterar'])) {
    $idNivel = $_POST['idNivel'];
    $idUsuario = $_POST['idUsuario'];
    
    $sql = "INSERT INTO usuario_contratos VALUES ('$idUsuario', '$idNivel')";
    $deleteRelacaoUser = "DELETE FROM usuario_contratos WHERE usuario_id = '$idUsuario'";
    
    if(mysqli_query($con, $sql) && mysqli_query($con, $deleteRelacaoUser)){
        $mensagem = mensagem('', '');
        gravarLog($sql);
    } else {
        $mensagem = mensagem('', '');
    }
}

if(isset($_POST['excluir'])) {
    $idUsuario = $_POST['idUsuario'];

    $deleteRelacaoUser = "DELETE FROM usuario_contratos WHERE usuario_id = '$idUsuario'";
    
    if(mysqli_query($con, $deleteRelacaoUser)) {
        $mensagem = mensagem('', '');
    } else {
        $mensagem = mensagem('', '');
    }
}

$usuarios = "SELECT u.nome_completo FROM usuario_contratos uc INNER JOIN usuarios u ON u.id = uc.usuario_id WHERE u.publicado = 1";
$query = mysqli_query($con, $usuarios);
?>