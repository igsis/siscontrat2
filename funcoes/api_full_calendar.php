<?php
require_once 'funcoesConecta.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json');

$conn = bancoPDO();

$sql = "SELECT eve.nome_evento nomeEvento,
 oco.data_inicio dataInicio,
  oco.data_fim dataFim,
   oco.horario_inicio horaInicio,
    oco.horario_fim horaFim
     FROM eventos eve 
     INNER JOIN ocorrencias oco ON oco.origem_ocorrencia_id = eve.id";

$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->fetchAll();

$eventos = json_encode($res);

print_r($eventos);

