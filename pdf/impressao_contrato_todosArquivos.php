<?php
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
$path = "../../siscontrat2/uploadsdocs/";
$con = bancoMysqli();

if (isset($_POST['idEvento'])) {
    $idEvento = $_POST['idEvento'];
}

$evento = recuperaDados('eventos', 'id', $idEvento);
$sql = "SELECT * FROM pedidos where origem_tipo_id = 1 AND origem_id = '$idEvento' AND publicado = 1";
$query = mysqli_query($con, $sql);
$pedido = mysqli_fetch_array($query);
$tipo_pessoa = $pedido['pessoa_tipo_id'];

if ($pedido['pessoa_tipo_id'] == 1) {
    $proponente = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);
    $idPf = $pedido['pessoa_fisica_id'];
} else {
    $proponente = recuperaDados('pessoa_juridicas', 'id', $pedido['pessoa_juridica_id']);
    $idPj = $pedido['pessoa_juridica_id'];
}


$data = date('YmdHis');
$nome_arquivo = $data . ".zip";

$zip = new ZipArchive();

if ($zip->open($nome_arquivo, ZipArchive::CREATE) === true) {

    if ($idEvento) {
        // arquivos do evento
        $sql = "SELECT arq.arquivo 
                FROM arquivos as arq
                INNER JOIN lista_documentos as ld ON arq.lista_documento_id = ld.id
                WHERE arq.origem_id = " . $pedido['id'] . " AND ld.tipo_documento_id = 3
                AND arq.publicado = 1";
        $query = mysqli_query($con, $sql);
        while ($arquivo = mysqli_fetch_array($query)) {
            $file = $path . $arquivo['arquivo'];
            $file2 = $arquivo['arquivo'];
            $zip->addFile($file, "evento/" . $file2);
        }
        // arquivos produção
        $sql_com_prod = "SELECT arq.arquivo FROM arquivos AS arq 
        INNER JOIN lista_documentos ld on arq.lista_documento_id = ld.id 
        WHERE arq.publicado = 1 AND origem_id = '$idEvento' AND ld.tipo_documento_id = 8";
        $query_com_prod = mysqli_query($con, $sql_com_prod);
        while ($arquivo = mysqli_fetch_array($query_com_prod)) {
            $file = $path . $arquivo['arquivo'];
            $file2 = $arquivo['arquivo'];
            $zip->addFile($file, "com_prod/" . $file2);
        }
    }
    // arquivos pf
    if ($tipo_pessoa == 1) {
        $sql_pf = "SELECT arq.arquivo FROM arquivos AS arq 
        INNER JOIN lista_documentos ld on arq.lista_documento_id = ld.id 
        WHERE arq.publicado = 1 AND origem_id = '$idPf' AND ld.tipo_documento_id = 1";
        $query_pf = mysqli_query($con, $sql_pf);
        while ($arquivo_pf = mysqli_fetch_array($query_pf)) {
            $file = $path . $arquivo_pf['arquivo'];
            $file2 = $arquivo_pf['arquivo'];
            $zip->addFile($file, "pf/" . $file2);
        }
    }
    // arquivos pj
    if ($tipo_pessoa == 2) {
        $sql_pj = "SELECT arq.arquivo FROM arquivos AS arq 
        INNER JOIN lista_documentos ld on arq.lista_documento_id = ld.id 
        WHERE arq.publicado = 1 AND origem_id = '$idPj' AND ld.tipo_documento_id = 2";
        $query_pj = mysqli_query($con, $sql_pj);
        while ($arquivo_pj = mysqli_fetch_array($query_pj)) {
            $file = $path . $arquivo_pj['arquivo'];
            $file2 = $arquivo_pj['arquivo'];
            $zip->addFile($file, "pj/" . $file2);
        }
    }
    $zip->close();
}

header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="' . $nome_arquivo . '"');
header('Content-Type: application/octet-stream');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($nome_arquivo));
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');


ob_end_clean(); //essas duas linhas antes do readfile
flush();

readfile($nome_arquivo);

unlink($data . ".zip");