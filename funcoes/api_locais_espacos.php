<?php 
    require_once 'funcoesConecta.php';
    // require "../funcoes/";

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: *');
	header('Content-Type: application/json');

	$conn = bancoPDO();
	
	if(isset($_GET['instituicao_id']) || isset($_POST['instituicao_id'])){
		$id = $_GET['instituicao_id'] ?? $_POST['instituicao_id'];

		$sql = "SELECT id, local FROM locais WHERE instituicao_id = :instituicao AND publicado = 1 order by local";

		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':instituicao', $id);
		$stmt->execute(); 
		$res = $stmt->fetchAll();

		$locais =  json_encode($res);

		print_r($locais);

	}

	if(isset($_GET['espaco_id'])){
		$id = $_GET['espaco_id'];

		$sql = "SELECT id, espaco FROM espacos WHERE local_id = :local_id AND publicado = 1 order by espaco";

		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':local_id', $id);
		$stmt->execute(); 
		$res = $stmt->fetchAll();

		$locais =  json_encode($res);

		print_r($locais);

	}
	
	

