<?php
$con = bancoMysqli();

if(isset($_POST['busca'])){


    $sql = "SELECT e.protocolo, p.numero_processo, p.pessoa_tipo_id, 
    p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, 
    p.valor_total, e.evento_status_id FROM eventos e 
    INNER JOIN pedidos p on e.id = p.origem_id 
    WHERE e.publicado = 1 
    AND p.publicado = 1 
    AND p.origem_tipo_id = 1";
}


