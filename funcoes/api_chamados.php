<?php 
    require_once 'funcoesConecta.php';
    // require "../funcoes/";

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: *');
	header('Content-Type: application/json');

	$conn = bancoPDO();
	
	if(isset($_GET['idEvento'])){
		$id = $_GET['idEvento'];

		$sql = "SELECT c.justificativa, u.nome_completo, DATE_FORMAT(c.data, '%d/%m/%Y') AS 'data' FROM usuarios AS u
                      INNER JOIN chamados AS c ON u.id = c.usuario_id
                      WHERE c.evento_id = :idEvento ORDER BY c.data DESC LIMIT 0,1";

		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':idEvento', $id);
		$stmt->execute(); 
		$res = $stmt->fetchAll();

		$chamado =  json_encode($res);

		print_r($chamado);

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
	
	

