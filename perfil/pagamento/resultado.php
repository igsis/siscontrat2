<?php
$con = bancoMysqli();

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

    $sql = "SELECT e.id, e.protocolo, p.numero_processo, p.pessoa_tipo_id, 
    p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, 
    p.valor_total, ps.status, p.operador_id, p.data_kit_pagamento
    FROM eventos e 
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN pedido_status ps on p.status_pedido_id = ps.id
    WHERE e.publicado = 1 
    AND p.publicado = 1 
    AND p.origem_tipo_id = 1
    AND e.evento_status_id = 3
    AND p.status_pedido_id NOT IN (1,3,20,21)
    $sqlProjeto $sqlUsuario $sqlOperador
    $sqlProtocolo $sqlNomeEvento $sqlProcesso";
    $resultado = $con->query($sql);
}
/* /.geral */
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Resultado de busca</h3>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
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
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($evento = mysqli_fetch_array($resultado)) {
                                if($evento['pessoa_tipo_id'] == 2){
                                    $idPj = $evento['pessoa_juridica_id'];
                                    $pj = $con->query("SELECT razao_social FROM pessoa_juridicas WHERE id = '$idPj'")->fetch_assoc();
                                    $proponente = $pj['razao_social'];
                                } else{
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
                                    <td><?= "operador faltando" ?></td>
                                    <td><?= $evento['data_kit_pagamento'] ? date('d/m/Y', strtotime($evento['data_kit_pagamento'])) : NULL ?></td>
                                </tr>
                            <?php
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