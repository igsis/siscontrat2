SELECT
               e.id,
               e.protocolo AS 'Protocolo', 
               e.nome_evento AS 'Nome do Evento',
               l.local AS 'Local',
               u.fiscal AS 'Fiscal',
               suplente.nome_completo AS 'Suplente'
               FROM eventos AS e
               INNER JOIN pedidos AS p ON p.origem_id = e.id 
               INNER JOIN ocorrencias AS o ON o.origem_ocorrencia_id = e.id
               INNER JOIN locais AS l ON l.id = o.local_id
               INNER JOIN usuarios AS u ON e.fiscal_id
               INNER JOIN usuarios AS suplente ON e.suplente_id
               WHERE evento_status_id = 3 AND e.publicado = 1 AND p.status_pedido_id = 1;