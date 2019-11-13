<?php

require_once "../funcoes/funcoesConecta.php";
$path = "../../igsiscapac/uploadsdocs/";
$path2 = "../../igsiscapac/uploads/";


$con = bancoCapac();
$idEvento = $_GET['idEvento'];
$idPj = $_GET['idPj'];
$idPf = $_GET['idPf'];

$data = date('YmdHis');
$nome_arquivo = $data.".zip";

$zip = new ZipArchive();

if( $zip->open( $nome_arquivo , ZipArchive::CREATE )  === true)
{
   	// arquivos do evento
	$sql = "SELECT * FROM arquivos WHERE publicado = '1' AND origem_id = '$idEvento' AND lista_documento_id = '3'";
	$query = mysqli_query($con,$sql);

	$sql_evento = "SELECT * FROM eventos WHERE id = '$idEvento'";
	$query_evento = mysqli_query($con,$sql_evento);
	$evento = mysqli_fetch_array($query_evento);

	$sql_pf = "SELECT * FROM pessoa_fisicas WHERE id = ''";
	$query_pf = mysqli_query($con,$sql_pf);
	$sql_pj = "SELECT * FROM pessoa_juridicas WHERE id = ''";
	$query_pj = mysqli_query($con,$sql_pj);


	// arquivos comunicação / produção
	$sql_com_prod = "SELECT * FROM arquivos WHERE publicado = '1' AND origem_id = '$idEvento'";
	$query_com_prod = mysqli_query($con,$sql_com_prod);


	while($arquivo = mysqli_fetch_array($query))
	{
		$file = $path.$arquivo['arquivo'];
		$file2 = $arquivo['arquivo'];
		$zip->addFile($file, "evento/".$file2);
	}

	if ($evento['idPj'] != NULL)
    {
        if($evento['idPj'] != 0)
        {
            foreach ($query_pj as $arquivo)
            {
                $file = $path.$arquivo['arquivo'];
                $file2 = $arquivo['arquivo'];
                $zip->addFile($file, "pj/".$file2);
            }
        }
    }

	if ($evento['idPf'] != NULL)
	{
        if($evento['idPf'] != 0)
        {
            foreach ($query_pf as $arquivo)
            {
                $file = $path.$arquivo['arquivo'];
                $file2 = $arquivo['arquivo'];
                $zip->addFile($file, "pf/".$file2);
            }
        }
    }

	while($arquivo = mysqli_fetch_array($query_com_prod))
	{
		$file = $path2.$arquivo['arquivo'];
		$file2 = $arquivo['arquivo'];
		$zip->addFile($file, "com_prod/".$file2);
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

?>