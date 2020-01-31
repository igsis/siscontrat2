<?php
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
$path = "../../siscontrat2/uploadsdocs/";
$con = bancoMysqli();

if(isset($_POST['idEvento'])){
    $idEvento = $_POST['idEvento'];
}

$tipo_pessoa = $_GET['tipo_pessoa'];
$idProponente = $_GET['idProponente'];

$data = date('YmdHis');
$nome_arquivo = $data.".zip";

$zip = new ZipArchive();

if( $zip->open( $nome_arquivo , ZipArchive::CREATE )  === true)
{

    if ($idEvento) {
        // arquivos do evento
        $sql = "SELECT arq.* FROM arquivos AS arq INNER JOIN lista_documentos ld on arq.lista_documento_id = ld.id WHERE arq.publicado = '1' AND origem_id = '$idEvento' AND ld.tipo_documento_id='3'";
        $query = mysqli_query($con, $sql);
        while ($arquivo = mysqli_fetch_array($query)) {
            $file = $path . $arquivo['arquivo'];
            $file2 = $arquivo['arquivo'];
            $zip->addFile($file, "evento/" . $file2);
        }

        // arquivos comunicação / produção
        $sql_com_prod = "SELECT arq.* FROM arquivos AS arq INNER JOIN lista_documentos ld on arq.lista_documento_id = ld.id WHERE arq.publicado = '1' AND origem_id = '$idEvento' AND ld.tipo_documento_id='8'";
        $query_com_prod = mysqli_query($con, $sql_com_prod);
        while ($arquivo = mysqli_fetch_array($query_com_prod)) {
            $file = $path . $arquivo['arquivo'];
            $file2 = $arquivo['arquivo'];
            $zip->addFile($file, "com_prod/" . $file2);
        }
    }

    if ($tipo_pessoa == 1){
        $sql_pf = "SELECT arq.* FROM arquivos AS arq INNER JOIN lista_documentos ld on arq.lista_documento_id = ld.id WHERE arq.publicado = '1' AND origem_id = '$idProponente' AND ld.tipo_documento_id='1'";
        $query_pf = mysqli_query($con,$sql_pf);
        foreach ($query_pf as $arquivo)
        {
            $file = $path.$arquivo['arquivo'];
            $file2 = $arquivo['arquivo'];
            $zip->addFile($file, "pf/".$file2);
        }
    }

    if ($tipo_pessoa == 2){
        $sql_pj = "SELECT arq.* FROM arquivos AS arq INNER JOIN lista_documentos ld on arq.lista_documento_id = ld.id WHERE arq.publicado = '1' AND origem_id = '$idProponente' AND ld.tipo_documento_id='2'";
        $query_pj = mysqli_query($con,$sql_pj);
        foreach ($query_pj as $arquivo)
        {
            $file = $path.$arquivo['arquivo'];
            $file2 = $arquivo['arquivo'];
            $zip->addFile($file, "pj/".$file2);
        }
    }

    $zip->close();
}

header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="'.$nome_arquivo.'"');
header('Content-Type: application/octet-stream');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($nome_arquivo));
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');


ob_end_clean(); //essas duas linhas antes do readfile
flush();

readfile($nome_arquivo);

unlink($data.".zip");