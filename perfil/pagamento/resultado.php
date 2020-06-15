<?php
$con = bancoMysqli();
/* ************** geral ************** */
if (isset($_POST['geral'])){
    $protocolo = $_POST['protocolo'] ?? NULL;
    $num_processo = $_POST['num_processo'] ?? NULL;
    $nome_evento = $_POST['nome_evento'] ?? NULL;
    $projeto = $_POST['projeto'] ?? NULL;
    $usuario = $_POST['usuario'] ?? NULL;
    $operador_id = $_POST['operador_id'] ?? NULL;
    $status = $_POST['status'] ?? NULL;

    $sqlProcesso = '';
    $sqlNomeEvento = '';
    $sqlProtocolo = '';
    $sqlProjeto = '';
    $sqlUsuario = '';
    $sqlOperador = '';
    $sqlStatus = '';

    if ($protocolo != null)
        $sqlProtocolo = " AND e.protocolo LIKE '%$protocolo%'";
    if ($num_processo != null)
        $sqlProcesso = " AND p.numero_processo LIKE '%$num_processo%'";
    if ($nome_evento != null)
        $sqlNomeEvento = " AND e.nome_evento LIKE '%$nome_evento%'";
    if ($projeto != null && $projeto != 0)
        $sqlProjeto = " AND e.projeto_especial_id = '$projeto'";
    if ($usuario != null && $usuario != 0)
        $sqlUsuario = " AND (e.fiscal_id = '$usuario' OR e.suplente_id = '$usuario' OR e.usuario_id = '$usuario')";
    if ($operador_id != null && $operador_id != 0)
        $sqlOperador = " AND p.operador_id = '$operador_id'";
    if ($status != null) {
        $sqlStatus = " AND p.status_pedido_id = '$status'";
    }        

    $sql = "SELECT e.id, p.id AS idPedido, e.protocolo, p.numero_processo, p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, p.valor_total, ps.status, u.nome_completo, p.data_kit_pagamento, p.operador_id, p.operador_pagamento_id
    FROM eventos e 
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN pedido_status ps on p.status_pedido_id = ps.id
    LEFT JOIN usuario_pagamentos up on p.operador_pagamento_id = up.usuario_id
    LEFT JOIN usuarios u on up.usuario_id = u.id
    INNER JOIN evento_envios ee ON e.id = ee.evento_id 
    WHERE e.publicado = 1 
    AND p.publicado = 1 
    AND p.origem_tipo_id = 1
    AND e.evento_status_id = 3
    AND p.status_pedido_id NOT IN (1,3,20,21)
    $sqlProjeto $sqlUsuario $sqlOperador
    $sqlProtocolo $sqlNomeEvento $sqlProcesso $sqlStatus
    GROUP BY e.id";
    
    $resultado = $con->query($sql);
    $num_rows = mysqli_num_rows($resultado);
    echo $sql;
}
/* ************** /.geral ************** */

/* ************** periodo ************** */
if(isset($_POST['periodo'])){
    $data_inicio = $_POST['data_inicio'] ?? NULL;
    $data_fim = $_POST['data_fim'] ?? NULL;
    // a data de início da ocorrência precisa estar entre a data_inicio e data_fim
    $sql = "SELECT e.id, p.id AS idPedido, e.protocolo, p.numero_processo, p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, p.valor_total, ps.status, u.nome_completo, p.data_kit_pagamento, p.operador_id
    FROM eventos AS e 
    INNER JOIN ocorrencias o on o.origem_ocorrencia_id = e.id
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN evento_envios ee ON e.id = ee.evento_id 
    INNER JOIN pedido_status ps on p.status_pedido_id = ps.id
    LEFT JOIN usuario_pagamentos up on p.operador_pagamento_id = up.usuario_id
    LEFT JOIN usuarios u on up.usuario_id = u.id
    WHERE e.publicado = 1 
    AND o.publicado = 1
    AND p.publicado = 1 
    AND o.tipo_ocorrencia_id != 3
    AND p.origem_tipo_id = 1
    AND e.evento_status_id = 3
    AND p.status_pedido_id NOT IN (1,3,20,21)
    AND o.data_inicio between '$data_inicio' AND '$data_fim'
    GROUP BY e.id";
    $resultado = $con->query($sql);
    $num_rows = mysqli_num_rows($resultado);
}
/* ************** /.periodo ************** */

/* ************** operador ************** */
if(isset($_POST['operador'])) {
    $data_inicio = $_POST['data_inicio'] ?? NULL;
    $data_fim = $_POST['data_fim'] ?? NULL;
    $operador_id = $_POST['operador_id'] ?? NULL;

    if($data_fim != NULL || $data_fim != NULL){
        $sqlDatas = " AND p.data_kit_pagamento between '$data_inicio' AND '$data_fim'";
    }else{
        $sqlDatas = "";
    }

    if ($operador_id != null && $operador_id != 0) {
        $sqlOperador = " AND p.operador_id = '$operador_id'";
    } else{
        $sqlOperador = "";
    }
    $sql = "SELECT e.id, p.id AS idPedido, e.protocolo, p.numero_processo, p.operador_pagamento_id, p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, p.valor_total, ps.status, u.nome_completo, p.data_kit_pagamento,  p.operador_id
    FROM eventos e 
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN evento_envios ee ON e.id = ee.evento_id 
    INNER JOIN pedido_status ps on p.status_pedido_id = ps.id
    LEFT JOIN usuario_pagamentos up on p.operador_id = up.usuario_id
    LEFT JOIN usuarios u on up.usuario_id = u.id
    WHERE e.publicado = 1 
    AND p.publicado = 1 
    AND p.origem_tipo_id = 1
    AND e.evento_status_id = 3
    AND p.status_pedido_id NOT IN (1,3,20,21)
    $sqlOperador $sqlDatas
    GROUP BY e.id";
    $resultado = $con->query($sql);
    $num_rows = mysqli_num_rows($resultado);
    echo $sql;
}
/* ************** /.operador ************** */
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h3 class="box-title">Resultado de busca</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblResultado" style="text-align: left;font-size: 90%;" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Processo</th>
                                <th>Protocolo</th>
                                <th>Proponente</th>
                                <th>Nome do evento</th>
                                <th>Período</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Operador</th>
                                <th>Kit pagamento</th>
                                <th>N.E.</th>
                                <th>PGTO</th>
                                <th>LIQ.</th>
                            </tr>
                            </thead>
<!--                            <tbody>-->
                            <?php
                            if ($num_rows == 0) {
                                ?>
                                <tr>
                                    <th colspan="12"><p align="center">Não foram encontrados registros</p></th>
                                </tr>
                                <?php
                            } else {
                                while ($evento = mysqli_fetch_array($resultado)) {
                                    $idPedido = $evento['idPedido'];
                                    $parcela = $con->query("SELECT id FROM parcelas WHERE pedido_id = '$idPedido'")->fetch_assoc();
                                    if($parcela == NULL) {
                                        $botao = "integral";
                                    } else{
                                        $botao = "parcelado";
                                    }
                                    if ($evento['pessoa_tipo_id'] == 2) {
                                        $idPj = $evento['pessoa_juridica_id'];
                                        $pj = $con->query("SELECT razao_social FROM pessoa_juridicas WHERE id = '$idPj'")->fetch_assoc();
                                        $proponente = $pj['razao_social'];
                                    } else {
                                        $idPf = $evento['pessoa_fisica_id'];
                                        $pf = $con->query("SELECT nome FROM pessoa_fisicas WHERE id = '$idPf'")->fetch_assoc();
                                        $proponente = $pf['nome'];
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $evento['numero_processo'] ?></td>
                                        <td><?= $evento['protocolo'] ?></td>
                                        <td><?= $proponente ?></td>
                                        <td><?= $evento['nome_evento'] ?></td>
                                        <td><?= retornaPeriodoNovo($evento['id'], 'ocorrencias') ?></td>
                                        <td><?= dinheiroParaBr($evento['valor_total']) ?></td>
                                        <td><?= $evento['status'] ?></td>
                                        <?php
                                        if ($evento['operador_id'] == NULL) {
                                            $nome = "Não possui";
                                        } else {
                                            $operador = recuperaDados('usuarios', 'id', $evento['operador_id']);
                                            $nome= $operador['nome_completo'];
                                        }
                                        ?>
                                        <td><?= $nome ?></td>
                                        <td><?= $evento['data_kit_pagamento'] ? date('d/m/Y', strtotime($evento['data_kit_pagamento'])) : "Não possui" ?></td>
                                        <?php
                                        $sqlTesta = "SELECT pedido_id FROM pagamentos WHERE pedido_id = " . $evento['idPedido'];
                                        $queryTesta = mysqli_query($con,$sqlTesta);
                                        $num = mysqli_num_rows($queryTesta);
                                        if($num > 0){
                                            $action = "?perfil=pagamento&p=empenho_edita";
                                        }else{
                                            $action = "?perfil=pagamento&p=empenho";
                                        }
                                        ?>
                                        <td>
                                            <form method="POST" action="<?=$action?>">
                                                <button type="submit" class="btn btn-primary btn-block" name="idPedido" value="<?= $evento['idPedido'] ?>"><i class="fa fa-arrow-circle-right"></i></button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="POST" action="?perfil=pagamento&p=<?= $botao ?>">
                                                <button type="submit" class="btn btn-primary btn-block" name="idPedido" value="<?= $evento['idPedido'] ?>"><i class="fa fa-arrow-circle-right"></i></button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="POST" action="?perfil=pagamento&p=liquidacao">
                                                <button type="submit" class="btn btn-primary btn-block" name="idPedido" value="<?= $evento['idPedido'] ?>"><i class="fa fa-arrow-circle-right"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                             ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Processo</th>
                                    <th>Protocolo</th>
                                    <th>Proponente</th>
                                    <th>Nome do evento</th>
                                    <th>Período</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Operador</th>
                                    <th>Kit pagamento</th>
                                    <th>N.E.</th>
                                    <th>PGTO</th>
                                    <th>LIQ.</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
</div>