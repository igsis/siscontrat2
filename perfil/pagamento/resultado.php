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

    $sqlProcesso = '';
    $sqlNomeEvento = '';
    $sqlProtocolo = '';
    $sqlProjeto = '';
    $sqlUsuario = '';
    $sqlOperador = '';

    if ($protocolo != null)
        $sqlProtocolo = " AND e.protocolo LIKE '%$protocolo%'";
    if ($num_processo != null)
        $sqlProcesso = " AND p.numero_processo LIKE '%$num_processo%'";
    if ($nome_evento != null)
        $sqlNomeEvento = " AND e.nome_evento LIKE '%$nome_evento%'";
    if ($projeto != null && $projeto != 0)
        $sqlProjeto = " AND e.projeto_especial_id = '$projeto'";
    if ($usuario != null && $usuario != 0)
        $sqlUsuario = " AND fiscal_id = '$usuario' OR suplente_id = '$usuario' OR usuario_id = '$usuario'";
    if ($operador_id != null && $operador_id != 0)
        $sqlOperador = "$ AND e.evento_operador_id = '$operador_id'";

    $sql = "SELECT e.id, p.id AS idPedido, e.protocolo, p.numero_processo, p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, p.valor_total, ps.status, u.nome_completo, p.data_kit_pagamento
    FROM eventos e 
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN pedido_status ps on p.status_pedido_id = ps.id
    LEFT JOIN usuario_pagamentos up on p.operador_pagamento_id = up.usuario_id
    LEFT JOIN usuarios u on up.usuario_id = u.id
    WHERE e.publicado = 1 
    AND p.publicado = 1 
    AND p.origem_tipo_id = 1
    AND e.evento_status_id = 3
    AND p.status_pedido_id NOT IN (1,3,20,21)
    $sqlProjeto $sqlUsuario $sqlOperador
    $sqlProtocolo $sqlNomeEvento $sqlProcesso";
    $resultado = $con->query($sql);
    $num_rows = mysqli_num_rows($resultado);
}
/* ************** /.geral ************** */

/* ************** periodo ************** */
if(isset($_POST['periodo'])){
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    // a data de início da ocorrência precisa estar entre a data_inicio e data_fim
    $sql = "SELECT e.id, p.id AS idPedido, e.protocolo, p.numero_processo, p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, p.valor_total, ps.status, u.nome_completo, p.data_kit_pagamento 
    FROM ocorrencias AS o 
    INNER JOIN eventos e on o.origem_ocorrencia_id = e.id
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN pedido_status ps on p.status_pedido_id = ps.id
    LEFT JOIN usuario_pagamentos up on p.operador_pagamento_id = up.usuario_id
    LEFT JOIN usuarios u on up.usuario_id = u.id
    WHERE e.publicado = 1 
    AND o.publicado = 1
    AND p.publicado = 1 
    AND o.tipo_ocorrencia_id = 1
    AND p.origem_tipo_id = 1
    AND e.evento_status_id = 3
    AND p.status_pedido_id NOT IN (1,3,20,21)
    AND o.data_inicio between '$data_inicio' AND '$data_fim'
    GROUP BY e.nome_evento";
    $resultado = $con->query($sql);
    $num_rows = mysqli_num_rows($resultado);
}
/* ************** /.periodo ************** */

/* ************** operador ************** */
if(isset($_POST['operador'])) {
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $operador_id = $_POST['operador_id'] ?? NULL;
    if ($operador_id != null && $operador_id != 0) {
        $sqlOperador = " AND operador_pagamento_id = '$operador_id'";
    } else{
        $sqlOperador = "";
    }
    $sql = "SELECT e.id, p.id AS idPedido, e.protocolo, p.numero_processo, p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, p.valor_total, ps.status, u.nome_completo, p.data_kit_pagamento
    FROM eventos e 
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN pedido_status ps on p.status_pedido_id = ps.id
    LEFT JOIN usuario_pagamentos up on p.operador_pagamento_id = up.usuario_id
    LEFT JOIN usuarios u on up.usuario_id = u.id
    WHERE e.publicado = 1 
    AND p.publicado = 1 
    AND p.origem_tipo_id = 1
    AND e.evento_status_id = 3
    AND p.status_pedido_id NOT IN (1,3,20,21)
    AND p.data_kit_pagamento between '$data_inicio' AND '$data_fim' $sqlOperador";
    $resultado = $con->query($sql);
    $num_rows = mysqli_num_rows($resultado);
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
                        <table id="tblResultado" class="table table-bordered table-striped">
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
                                    var_dump($parcela);
                                    if($parcela == NULL) {
                                        $botao = "parcela_unica";
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
                                        <td><?= retornaPeriodo($evento['id']) ?></td>
                                        <td><?= dinheiroParaBr($evento['valor_total']) ?></td>
                                        <td><?= $evento['status'] ?></td>
                                        <td><?= $evento['nome_completo'] ? strstr($evento['nome_completo'],' ', true) : NULL ?></td>
                                        <td><?= $evento['data_kit_pagamento'] ? date('d/m/Y', strtotime($evento['data_kit_pagamento'])) : NULL ?></td>
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